@if($msg->role === 'user')
<div class="flex gap-4 max-w-4xl mx-auto justify-end">
    <div class="max-w-[80%]">
        @if($msg->attachment_path)
        <div class="mb-2">
            @if($msg->attachment_type === 'image')
            <img src="{{ asset('storage/' . $msg->attachment_path) }}" class="max-w-xs max-h-48 rounded-xl object-cover" alt="{{ $msg->attachment_name }}">
            @else
            <div class="flex items-center gap-2 text-xs p-2 rounded-lg" style="background: var(--card); color: var(--subtext);">
                <span>📄</span><span>{{ $msg->attachment_name }}</span>
            </div>
            @endif
        </div>
        @endif
        @if($msg->content)
        <div class="msg-user rounded-2xl rounded-tr-md px-4 py-3 text-sm" style="color: var(--text);">
            {!! nl2br(e($msg->content)) !!}
        </div>
        @endif
    </div>
    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold text-white shrink-0 mt-1" style="background: linear-gradient(135deg, #7c3aed, #6366f1);">
        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
    </div>
</div>
@else
<div class="flex gap-4 max-w-4xl mx-auto ai-message-wrapper">
    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 mt-1" style="background: linear-gradient(135deg, #7c3aed, #3b82f6);">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
            <circle cx="12" cy="8" r="4" fill="white"/>
            <ellipse cx="12" cy="16" rx="6" ry="3" fill="white" opacity="0.6"/>
        </svg>
    </div>
    <div class="flex-1">
        <div class="msg-ai rounded-2xl rounded-tl-md px-4 py-3">
            <div class="msg-content prose-dark text-sm" id="msg-{{ $msg->id }}">
                {!! \Illuminate\Support\Str::markdown($msg->content) !!}
            </div>
        </div>
        @if($msg->has_code && $msg->code_content)
        <div class="mt-2 flex gap-2">
            <button onclick="openPreview({{ json_encode($msg->code_content) }}, '{{ $msg->code_language ?? 'html' }}')"
                class="btn-glow px-4 py-2 text-xs font-medium rounded-xl text-white flex items-center gap-2">
                <span>🖥</span> Preview Hasil
            </button>
            <button onclick="copyCode({{ json_encode($msg->code_content) }})"
                class="px-4 py-2 text-xs font-medium rounded-xl border transition-colors hover:bg-white/5"
                style="border-color: var(--border); color: var(--subtext);">
                📋 Copy Kode
            </button>
        </div>
        @endif
    </div>
</div>
@endif
