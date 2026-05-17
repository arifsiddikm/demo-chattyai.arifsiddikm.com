<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Services\AIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    public function __construct(protected AIService $aiService) {}

    public function index()
    {
        $sessions = ChatSession::where('user_id', Auth::id())
            ->orderByDesc('updated_at')
            ->get();

        return view('chat.index', compact('sessions'));
    }

    public function show(ChatSession $session)
    {
        abort_if($session->user_id !== Auth::id(), 403);

        $sessions = ChatSession::where('user_id', Auth::id())
            ->orderByDesc('updated_at')
            ->get();

        $messages = $session->messages()->orderBy('created_at')->get();

        return view('chat.index', compact('sessions', 'session', 'messages'));
    }

    public function newSession(Request $request)
    {
        $session = ChatSession::create([
            'user_id'         => Auth::id(),
            'title'           => 'New Chat',
            'model'           => $request->input('model', 'gpt-4o'),
            'is_vibe_coding'  => $request->boolean('is_vibe_coding'),
        ]);

        return response()->json(['session_id' => $session->id, 'redirect' => route('chat.show', $session)]);
    }

    public function deleteSession(ChatSession $session)
    {
        abort_if($session->user_id !== Auth::id(), 403);
        $session->delete();
        return response()->json(['success' => true]);
    }

    public function renameSession(Request $request, ChatSession $session)
    {
        abort_if($session->user_id !== Auth::id(), 403);
        $request->validate(['title' => 'required|string|max:100']);
        $session->update(['title' => $request->title]);
        return response()->json(['success' => true]);
    }

    /**
     * Main stream endpoint - sends SSE (Server-Sent Events)
     */
    public function stream(Request $request, ChatSession $session)
    {
        abort_if($session->user_id !== Auth::id(), 403);

        $request->validate([
            'message'    => 'nullable|string|max:10000',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf|max:10240',
        ]);

        $userMessage = $request->input('message', '');
        $attachmentPath = null;
        $attachmentType = null;
        $attachmentName = null;
        $attachmentBase64 = null;

        // Handle file upload
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentType = str_starts_with($file->getMimeType(), 'image/') ? 'image' : 'pdf';
            $attachmentName = $file->getClientOriginalName();
            $attachmentPath = $file->store('chat-attachments', 'public');

            if ($attachmentType === 'image') {
                $attachmentBase64 = base64_encode(file_get_contents($file->getRealPath()));
            }
        }

        // Save user message
        ChatMessage::create([
            'chat_session_id' => $session->id,
            'user_id'         => Auth::id(),
            'role'            => 'user',
            'content'         => $userMessage,
            'attachment_path' => $attachmentPath,
            'attachment_type' => $attachmentType,
            'attachment_name' => $attachmentName,
        ]);

        // Build history (last 20 messages for context)
        $history = $session->messages()
            ->orderBy('created_at')
            ->take(20)
            ->get()
            ->map(fn($m) => ['role' => $m->role, 'content' => $m->content])
            ->toArray();

        $messages = $this->aiService->buildMessages(
            array_slice($history, 0, -1), // exclude last (just saved user msg)
            $userMessage,
            $attachmentBase64,
            $attachmentType
        );

        $isVibeCoding = $session->is_vibe_coding;

        // Auto-title session after first message
        if ($session->title === 'New Chat' && !empty($userMessage)) {
            $session->update(['title' => Str::limit($userMessage, 40)]);
        }

        // Stream SSE response
        return response()->stream(function () use ($messages, $isVibeCoding, $session) {
            $fullResponse = '';

            foreach ($this->aiService->streamChat($messages, $isVibeCoding) as $chunk) {
                if (isset($chunk['error'])) {
                    echo "data: " . json_encode(['error' => $chunk['error']]) . "\n\n";
                    ob_flush();
                    flush();
                    break;
                }

                $content = $chunk['content'];
                $fullResponse .= $content;

                echo "data: " . json_encode(['content' => $content]) . "\n\n";
                ob_flush();
                flush();
            }

            // Detect code in response
            $hasCode = str_contains($fullResponse, '<code-block') || str_contains($fullResponse, '```');
            $codeContent = null;
            $codeLang = null;

            if (preg_match('/<code-block lang="([^"]+)">(.*?)<\/code-block>/s', $fullResponse, $m)) {
                $codeLang = $m[1];
                $codeContent = trim($m[2]);
                $hasCode = true;
            } elseif (preg_match('/```(\w+)?\n(.*?)```/s', $fullResponse, $m)) {
                $codeLang = $m[1] ?: 'html';
                $codeContent = trim($m[2]);
                $hasCode = true;
            }

            // Save AI response
            ChatMessage::create([
                'chat_session_id' => $session->id,
                'user_id'         => auth()->id(),
                'role'            => 'assistant',
                'content'         => $fullResponse,
                'has_code'        => $hasCode,
                'code_content'    => $codeContent,
                'code_language'   => $codeLang,
            ]);

            // Update session timestamp
            $session->touch();

            echo "data: " . json_encode(['done' => true, 'has_code' => $hasCode]) . "\n\n";
            ob_flush();
            flush();

        }, 200, [
            'Content-Type'      => 'text/event-stream',
            'Cache-Control'     => 'no-cache',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}
