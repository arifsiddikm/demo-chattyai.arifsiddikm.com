<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    protected string $apiKey;
    protected string $model;
    protected string $apiUrl = 'https://api.openai.com/v1/chat/completions';

    public function __construct()
    {
        $this->apiKey = config('services.openai.key', env('OPENAI_API_KEY'));
        $this->model  = config('services.openai.model', env('OPENAI_MODEL', 'gpt-4o'));
    }

    /**
     * Send messages to OpenAI and return streaming response.
     * We use PHP's stream context so Laravel can yield chunks to frontend via SSE.
     */
    public function streamChat(array $messages, bool $isVibeCoding = false): \Generator
    {
        $systemPrompt = $isVibeCoding
            ? "You are Chatty AI, an expert full-stack developer and UI/UX designer. When the user asks to build something, respond with a detailed explanation AND provide the complete working HTML/CSS/JS code wrapped in a <code-block lang=\"html\"> tag. Structure your response so the code can be previewed live in an iframe. Always write clean, modern, responsive code."
            : "You are Chatty AI, an advanced AI assistant. You are helpful, creative, and knowledgeable. Respond in the same language the user uses. Format responses clearly with markdown when appropriate.";

        $payload = [
            'model'  => $this->model,
            'stream' => true,
            'messages' => array_merge(
                [['role' => 'system', 'content' => $systemPrompt]],
                $messages
            ),
            'max_tokens' => 4096,
            'temperature' => 0.7,
        ];

        $context = stream_context_create([
            'http' => [
                'method'  => 'POST',
                'header'  => implode("\r\n", [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $this->apiKey,
                    'Accept: text/event-stream',
                ]),
                'content' => json_encode($payload),
                'timeout' => 120,
            ],
        ]);

        $stream = @fopen($this->apiUrl, 'r', false, $context);

        if (!$stream) {
            yield ['error' => 'Could not connect to AI service. Check your API key.'];
            return;
        }

        while (!feof($stream)) {
            $line = fgets($stream);
            if ($line === false) break;

            $line = trim($line);
            if (empty($line) || $line === 'data: [DONE]') continue;

            if (str_starts_with($line, 'data: ')) {
                $json = substr($line, 6);
                $data = json_decode($json, true);

                if (isset($data['choices'][0]['delta']['content'])) {
                    yield ['content' => $data['choices'][0]['delta']['content']];
                }

                if (isset($data['error'])) {
                    yield ['error' => $data['error']['message'] ?? 'AI error occurred'];
                    break;
                }
            }
        }

        fclose($stream);
    }

    /**
     * Build messages array from chat history + new user message
     */
    public function buildMessages(array $history, string $userMessage, ?string $attachmentBase64 = null, ?string $attachmentType = null): array
    {
        $messages = [];

        foreach ($history as $msg) {
            $messages[] = [
                'role'    => $msg['role'],
                'content' => $msg['content'],
            ];
        }

        // Build current user message
        if ($attachmentBase64 && $attachmentType === 'image') {
            $messages[] = [
                'role' => 'user',
                'content' => [
                    ['type' => 'text', 'text' => $userMessage ?: 'Please analyze this image.'],
                    [
                        'type'      => 'image_url',
                        'image_url' => ['url' => 'data:image/jpeg;base64,' . $attachmentBase64],
                    ],
                ],
            ];
        } else {
            $content = $userMessage;
            if ($attachmentBase64 && $attachmentType === 'pdf') {
                $content = $userMessage . "\n\n[PDF Content Attached - Please analyze the document content I'm describing above]";
            }
            $messages[] = ['role' => 'user', 'content' => $content];
        }

        return $messages;
    }
}
