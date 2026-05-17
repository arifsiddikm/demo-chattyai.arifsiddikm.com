@extends('layouts.app')

@section('title', isset($session) ? $session->title . ' — Chatty AI' : 'Chat — Chatty AI')

@section('content')

@php
    $username    = auth()->user()->username ?? '';
    $userName    = auth()->user()->name ?? '';
    $userInitial = strtoupper(substr($userName, 0, 1));
@endphp

<div class="flex h-screen overflow-hidden" style="background: #080810;">

    <!-- MOBILE OVERLAY -->
    <div id="sidebar-overlay" class="fixed inset-0 z-30 bg-black/60 hidden" onclick="closeSidebar()"></div>

    <!-- ======= SIDEBAR =======
         Desktop (lg+) : relative, visible by default, toggleable
         Mobile (<lg)  : fixed overlay, hidden by default
    -->
    <aside id="sidebar"
        class="flex flex-col w-72 shrink-0 border-r z-40 transition-transform duration-300"
        style="background: var(--panel); border-color: var(--border);">

        <!-- Sidebar header -->
        <div class="flex items-center justify-between p-4 border-b" style="border-color: var(--border);">
            <a href="{{ route('landing') }}" class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, #7c3aed, #3b82f6);">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="8" r="4" fill="white"/>
                        <ellipse cx="12" cy="16" rx="6" ry="3" fill="white" opacity="0.6"/>
                        <circle cx="9" cy="9" r="1" fill="#7c3aed"/>
                        <circle cx="15" cy="9" r="1" fill="#7c3aed"/>
                    </svg>
                </div>
                <span class="font-display font-bold text-white text-sm">Chatty AI</span>
            </a>
        </div>

        <!-- New Chat button -->
        <div class="p-3">
            <button onclick="createNewChat()" class="btn-glow w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-white">
                <span class="text-lg">✦</span>
                <span>New Chat</span>
            </button>
        </div>

        <!-- Mode toggle -->
        <div class="px-3 pb-3">
            <div class="flex rounded-xl overflow-hidden border" style="border-color: var(--border);">
                <button id="mode-chat" onclick="setMode('chat')" class="flex-1 py-2 text-xs font-medium transition-all">
                    💬 Chat
                </button>
                <button id="mode-vibe" onclick="setMode('vibe')" class="flex-1 py-2 text-xs font-medium transition-all">
                    ⚡ Vibe Coding
                </button>
            </div>
        </div>

        <!-- Search -->
        <div class="px-3 pb-3">
            <div class="relative">
                <svg class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2" style="color: var(--muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="search-chats" placeholder="Cari chat..."
                    class="input-dark w-full rounded-lg pl-8 pr-3 py-2 text-xs"
                    oninput="searchChats(this.value)">
            </div>
        </div>

        <!-- Chat history -->
        <div class="flex-1 overflow-y-auto px-2 pb-4 space-y-0.5" id="chat-list">
            <p class="text-xs px-2 py-1 font-medium mb-1" style="color: var(--muted);">Riwayat Chat</p>
            @forelse($sessions as $s)
            <div class="sidebar-item group flex items-center gap-2 px-3 py-2.5 rounded-xl cursor-pointer transition-all duration-150 {{ isset($session) && $session->id === $s->id ? 'sidebar-item-active' : '' }}"
                 onclick="loadSession({{ $s->id }})"
                 data-session-id="{{ $s->id }}"
                 data-title="{{ $s->title }}">
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium truncate {{ isset($session) && $session->id === $s->id ? 'text-white' : '' }}"
                       style="{{ isset($session) && $session->id === $s->id ? '' : 'color: var(--subtext);' }}">
                        {{ $s->is_vibe_coding ? '⚡ ' : '' }}{{ $s->title }}
                    </p>
                    <p class="text-xs mt-0.5" style="color: var(--muted);">{{ $s->updated_at->diffForHumans() }}</p>
                </div>
                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button onclick="event.stopPropagation(); renameChat({{ $s->id }}, '{{ addslashes($s->title) }}')"
                            class="p-1 rounded-lg hover:bg-white/10" style="color: var(--muted);" title="Rename">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    <button onclick="event.stopPropagation(); deleteChat({{ $s->id }})"
                            class="p-1 rounded-lg hover:bg-red-500/20" style="color: var(--muted);" title="Hapus">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>
            @empty
            <p class="text-xs text-center py-8" style="color: var(--muted);">Belum ada chat. Mulai yang baru!</p>
            @endforelse
        </div>

        <!-- User section -->
        <div class="border-t p-3" style="border-color: var(--border);">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold text-white shrink-0"
                     style="background: linear-gradient(135deg, #7c3aed, #6366f1);">
                    {{ $userInitial }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-white truncate">{{ $userName }}</p>
                    {{-- FIX: gunakan &#64; supaya @ tidak diinterpretasikan sebagai Blade directive --}}
                    <p class="text-xs truncate" style="color: var(--muted);">&#64;{{ $username }}</p>
                </div>
                <button onclick="confirmLogout()"
                        class="p-1.5 rounded-lg hover:bg-red-500/20 transition-colors" style="color: var(--muted);" title="Logout">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </div>
        </div>
    </aside>

    <!-- ======= MAIN CHAT AREA ======= -->
    <main class="flex-1 flex flex-col min-w-0 overflow-hidden">

        <!-- Top bar — ONE single hamburger button -->
        <div class="flex items-center justify-between px-4 py-3 border-b shrink-0" style="background: var(--panel); border-color: var(--border);">
            <div class="flex items-center gap-3">
                <button onclick="toggleSidebar()"
                        class="p-2 rounded-lg hover:bg-white/5 transition-colors"
                        style="color: var(--muted);" title="Toggle sidebar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div>
                    <h2 class="font-display font-semibold text-white text-sm" id="chat-title">
                        {{ isset($session) ? $session->title : 'Chatty AI' }}
                    </h2>
                    <p class="text-xs" style="color: var(--muted);" id="chat-mode-label">
                        {{ isset($session) && $session->is_vibe_coding ? '⚡ Vibe Coding Mode' : '💬 Chat Mode' }}
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs px-2.5 py-1 rounded-full" style="background: rgba(124,58,237,0.15); color: #a78bfa;">GPT-4o</span>
            </div>
        </div>

        <!-- Messages area — space-y-8 untuk jarak lebih lega antar chat -->
        <div id="messages-container" class="flex-1 overflow-y-auto px-4 py-8" style="scroll-behavior: smooth;">

            <!-- Welcome screen -->
            <div id="welcome-screen"
                 class="{{ isset($messages) && $messages->count() > 0 ? 'hidden' : '' }} flex flex-col items-center justify-center min-h-full text-center py-12">
                <div class="text-6xl mb-4 animate-float">🤖</div>
                <h2 class="font-display text-2xl font-bold text-white mb-3">Halo, {{ $userName }}!</h2>
                <p class="text-subtext max-w-md text-sm leading-relaxed">
                    Aku Chatty AI, siap membantu kamu. Bisa chat biasa atau gunakan mode <strong class="text-white">Vibe Coding</strong> untuk membuat website instan.
                </p>
                <div class="grid grid-cols-2 gap-3 mt-8 max-w-lg w-full">
                    @foreach(['Jelaskan cara kerja machine learning', 'Buat landing page startup tech', 'Tulis fungsi Python untuk sorting', 'Buat form login dengan HTML/CSS modern'] as $suggestion)
                    <button onclick="setSuggestion('{{ $suggestion }}')"
                            class="text-left p-3 rounded-xl text-xs border hover:border-purple-600/50 transition-all"
                            style="background: var(--card); border-color: var(--border); color: var(--subtext);">
                        {{ $suggestion }}
                    </button>
                    @endforeach
                </div>
            </div>

            <!-- Messages list — space-y-8 margin antar chat -->
            <div id="messages-list" class="space-y-8 max-w-4xl mx-auto">
                @if(isset($messages))
                    @foreach($messages as $msg)
                        @include('chat.partials.message', ['msg' => $msg])
                    @endforeach
                @endif
            </div>

            <!-- AI typing indicator -->
            <div id="typing-indicator" class="hidden max-w-4xl mx-auto mt-8">
                <div class="flex gap-4">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0"
                         style="background: linear-gradient(135deg, #7c3aed, #3b82f6);">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="8" r="4" fill="white"/>
                            <ellipse cx="12" cy="16" rx="6" ry="3" fill="white" opacity="0.6"/>
                        </svg>
                    </div>
                    <div class="msg-ai rounded-2xl px-4 py-3 text-sm" style="color: var(--subtext);">
                        <div class="flex gap-1.5 items-center">
                            <span class="w-2 h-2 rounded-full bg-purple-500 animate-bounce" style="animation-delay: 0ms;"></span>
                            <span class="w-2 h-2 rounded-full bg-indigo-500 animate-bounce" style="animation-delay: 150ms;"></span>
                            <span class="w-2 h-2 rounded-full bg-blue-500 animate-bounce" style="animation-delay: 300ms;"></span>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- end messages-container -->

        <!-- ======= INPUT AREA ======= -->
        <div class="px-4 pb-4 pt-2 shrink-0" style="background: var(--surface);">
            <!-- Attachment preview -->
            <div id="attachment-preview" class="hidden mb-2 p-2 rounded-xl flex items-center gap-2 text-sm border"
                 style="background: var(--card); border-color: var(--border); color: var(--subtext);">
                <span id="attachment-icon" class="text-lg">📎</span>
                <span id="attachment-name" class="flex-1 truncate text-xs"></span>
                <button onclick="removeAttachment()" class="p-1 hover:text-red-400 transition-colors" title="Hapus">✕</button>
            </div>

            <div class="max-w-4xl mx-auto">
                <div class="relative rounded-2xl border transition-all duration-200 overflow-hidden"
                     style="background: var(--card); border-color: var(--border);">
                    <textarea
                        id="message-input"
                        placeholder="Ketik pesan... (Enter kirim, Shift+Enter baris baru)"
                        rows="1"
                        class="w-full bg-transparent px-5 py-4 pr-36 text-sm resize-none focus:outline-none"
                        style="color: var(--text); max-height: 200px; min-height: 56px;"
                        oninput="autoResize(this)"
                        onkeydown="handleKeyDown(event)"
                    ></textarea>
                    <div class="absolute right-3 bottom-3 flex items-center gap-2">
                        <label class="p-2 rounded-xl cursor-pointer transition-colors hover:bg-white/5"
                               style="color: var(--muted);" title="Upload foto/PDF">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                            <input type="file" id="file-input" class="hidden" accept="image/*,.pdf" onchange="handleFileSelect(this)">
                        </label>
                        <button id="send-btn" onclick="sendMessage()"
                                class="btn-glow p-2.5 rounded-xl text-white" title="Kirim (Enter)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <p class="text-center text-xs mt-2" style="color: var(--muted);">
                    Chatty AI dapat membuat kesalahan. Verifikasi informasi penting.
                </p>
            </div>
        </div>
    </main>

    <!-- ======= CODE PREVIEW MODAL ======= -->
    <div id="preview-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4"
         style="background: rgba(0,0,0,0.8); backdrop-filter: blur(8px);">
        <div class="w-full max-w-6xl h-full max-h-[90vh] flex flex-col rounded-2xl border overflow-hidden"
             style="background: var(--panel); border-color: var(--border);">
            <div class="flex items-center justify-between px-4 py-3 border-b" style="border-color: var(--border);">
                <div class="flex items-center gap-3">
                    <span class="text-sm font-medium text-white font-display">⚡ Vibe Coding Preview</span>
                    <span id="preview-lang" class="text-xs px-2 py-0.5 rounded-full"
                          style="background: rgba(124,58,237,0.2); color: #a78bfa;">HTML</span>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="copyPreviewCode()"
                            class="px-3 py-1.5 text-xs rounded-lg border transition-colors hover:bg-white/5"
                            style="border-color: var(--border); color: var(--subtext);">📋 Copy Kode</button>
                    <button onclick="closePreview()"
                            class="p-2 rounded-lg hover:bg-red-500/20 transition-colors" style="color: var(--muted);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="flex border-b" style="border-color: var(--border);">
                <button onclick="switchTab('preview')" id="tab-preview"
                        class="px-4 py-2 text-xs font-medium border-b-2 transition-colors"
                        style="border-color: #7c3aed; color: white;">🖥 Preview</button>
                <button onclick="switchTab('code')" id="tab-code"
                        class="px-4 py-2 text-xs font-medium border-b-2 transition-colors"
                        style="border-color: transparent; color: var(--muted);">&lt;/&gt; Kode</button>
            </div>
            <div class="flex-1 overflow-hidden">
                <iframe id="preview-frame" class="w-full h-full bg-white" style="border: none;"></iframe>
                <div id="code-view" class="hidden h-full overflow-auto p-4">
                    <pre class="text-xs font-mono" style="color: var(--text); white-space: pre-wrap; word-wrap: break-word;" id="raw-code"></pre>
                </div>
            </div>
        </div>
    </div>

</div><!-- end flex root -->

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>

<input type="hidden" id="current-session-id" value="{{ isset($session) ? $session->id : '' }}">
<input type="hidden" id="current-mode"       value="{{ isset($session) && $session->is_vibe_coding ? 'vibe' : 'chat' }}">
<input type="hidden" id="csrf-token"         value="{{ csrf_token() }}">
<input type="hidden" id="user-initial"       value="{{ $userInitial }}">

@push('styles')
<style>
/* Sidebar responsive behaviour via JS — base style */
#sidebar {
    /* Desktop: relative, visible */
    /* Mobile:  fixed, translated off-screen */
}

/* On desktop, when sidebar is open it participates in flexbox */
@media (min-width: 1024px) {
    #sidebar {
        position: relative !important;
    }
    #sidebar-overlay {
        display: none !important;
    }
}

@media (max-width: 1023px) {
    #sidebar {
        position: fixed;
        top: 0; left: 0;
        height: 100%;
    }
}
</style>
@endpush

@push('scripts')
<script>
const CSRF      = document.getElementById('csrf-token').value;
const USER_INIT = document.getElementById('user-initial').value;
let currentSessionId = document.getElementById('current-session-id').value;
let currentMode      = document.getElementById('current-mode').value;
let isStreaming  = false;
let selectedFile = null;
let currentPreviewCode = '';

// ===== SIDEBAR TOGGLE =====
// sidebarOpen tracks the LOGICAL state (open/closed)
let sidebarOpen = window.innerWidth >= 1024; // desktop open by default

function applyDesktopSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.style.position  = 'relative';
    sidebar.style.transform = sidebarOpen ? 'translateX(0)' : 'translateX(-100%)';
    // When collapsed on desktop use width 0 with overflow hidden so layout shifts
    sidebar.style.width     = sidebarOpen ? '18rem' : '0';
    sidebar.style.overflow  = sidebarOpen ? '' : 'hidden';
    document.getElementById('sidebar-overlay').classList.add('hidden');
}

function applyMobileSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.style.position  = 'fixed';
    sidebar.style.width     = '18rem';
    sidebar.style.overflow  = '';
    sidebar.style.transform = sidebarOpen ? 'translateX(0)' : 'translateX(-100%)';
    const overlay = document.getElementById('sidebar-overlay');
    if (sidebarOpen) {
        overlay.classList.remove('hidden');
    } else {
        overlay.classList.add('hidden');
    }
}

function applySidebar() {
    if (window.innerWidth >= 1024) {
        applyDesktopSidebar();
    } else {
        applyMobileSidebar();
    }
}

function openSidebar() {
    sidebarOpen = true;
    applySidebar();
}

function closeSidebar() {
    sidebarOpen = false;
    applySidebar();
}

function toggleSidebar() {
    sidebarOpen = !sidebarOpen;
    applySidebar();
}

// Re-apply on window resize
window.addEventListener('resize', applySidebar);

// Init
applySidebar();

// ===== SEARCH =====
function searchChats(q) {
    document.querySelectorAll('#chat-list .sidebar-item').forEach(el => {
        const title = el.dataset.title || '';
        el.style.display = title.toLowerCase().includes(q.toLowerCase()) ? '' : 'none';
    });
}

// ===== MODE =====
function setMode(mode) {
    currentMode = mode;
    const chatBtn = document.getElementById('mode-chat');
    const vibeBtn = document.getElementById('mode-vibe');
    chatBtn.style.background = mode === 'chat' ? 'rgba(124,58,237,0.3)' : '';
    chatBtn.style.color      = mode === 'chat' ? 'white' : 'var(--muted)';
    vibeBtn.style.background = mode === 'vibe' ? 'rgba(124,58,237,0.3)' : '';
    vibeBtn.style.color      = mode === 'vibe' ? 'white' : 'var(--muted)';
    document.getElementById('chat-mode-label').textContent =
        mode === 'vibe' ? '⚡ Vibe Coding Mode' : '💬 Chat Mode';
}

// ===== INPUT =====
function autoResize(el) {
    el.style.height = 'auto';
    el.style.height = Math.min(el.scrollHeight, 200) + 'px';
}
function handleKeyDown(e) {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
}
function setSuggestion(text) {
    const inp = document.getElementById('message-input');
    inp.value = text;
    autoResize(inp);
    inp.focus();
}

// ===== FILE =====
function handleFileSelect(input) {
    if (input.files && input.files[0]) {
        selectedFile = input.files[0];
        const isImg  = selectedFile.type.startsWith('image/');
        document.getElementById('attachment-icon').textContent = isImg ? '🖼' : '📄';
        document.getElementById('attachment-name').textContent  = selectedFile.name;
        document.getElementById('attachment-preview').classList.remove('hidden');
    }
}
function removeAttachment() {
    selectedFile = null;
    document.getElementById('file-input').value = '';
    document.getElementById('attachment-preview').classList.add('hidden');
}

// ===== NEW CHAT =====
async function createNewChat() {
    const res  = await fetch('{{ route("chat.new") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ is_vibe_coding: currentMode === 'vibe' })
    });
    const data = await res.json();
    window.location.href = data.redirect;
}
function loadSession(id) { window.location.href = `/chat/${id}`; }

// ===== DELETE =====
async function deleteChat(id) {
    const result = await Swal.fire({
        title: 'Hapus Chat?', text: 'Chat ini akan dihapus permanen.',
        icon: 'warning', showCancelButton: true,
        confirmButtonText: 'Ya, hapus', cancelButtonText: 'Batal',
    });
    if (!result.isConfirmed) return;
    await fetch(`/chat/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': CSRF } });
    if (currentSessionId == id) {
        window.location.href = '{{ route("chat.index") }}';
    } else {
        document.querySelector(`[data-session-id="${id}"]`)?.remove();
        Toast.fire({ icon: 'success', title: 'Chat dihapus' });
    }
}

// ===== RENAME =====
async function renameChat(id, oldTitle) {
    const { value: title } = await Swal.fire({
        title: 'Rename Chat', input: 'text', inputValue: oldTitle,
        inputLabel: 'Judul baru', showCancelButton: true, confirmButtonText: 'Simpan',
    });
    if (!title) return;
    await fetch(`/chat/${id}/rename`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ title })
    });
    const el = document.querySelector(`[data-session-id="${id}"] p:first-child`);
    if (el) el.textContent = title;
    if (currentSessionId == id) document.getElementById('chat-title').textContent = title;
    Toast.fire({ icon: 'success', title: 'Nama diubah' });
}

// ===== LOGOUT =====
function confirmLogout() {
    Swal.fire({
        title: 'Keluar?', text: 'Kamu akan logout dari Chatty AI.',
        icon: 'question', showCancelButton: true,
        confirmButtonText: 'Ya, logout', cancelButtonText: 'Batal',
    }).then(r => { if (r.isConfirmed) document.getElementById('logout-form').submit(); });
}

// ===== SEND =====
async function sendMessage() {
    if (isStreaming) return;
    const input   = document.getElementById('message-input');
    const message = input.value.trim();
    if (!message && !selectedFile) return;

    if (!currentSessionId) {
        const res  = await fetch('{{ route("chat.new") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ is_vibe_coding: currentMode === 'vibe' })
        });
        const data = await res.json();
        currentSessionId = data.session_id;
        document.getElementById('current-session-id').value = currentSessionId;
        addToSidebar(currentSessionId, message || 'New Chat');
    }

    document.getElementById('welcome-screen').classList.add('hidden');

    const formData = new FormData();
    formData.append('message', message);
    if (selectedFile) formData.append('attachment', selectedFile);

    appendUserMessage(message, selectedFile);
    input.value = '';
    input.style.height = 'auto';
    removeAttachment();

    isStreaming = true;
    document.getElementById('typing-indicator').classList.remove('hidden');
    scrollToBottom();

    const aiMsgEl = appendAIMessage();

    try {
        const response = await fetch(`/chat/${currentSessionId}/stream`, {
            method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF }, body: formData,
        });
        const reader  = response.body.getReader();
        const decoder = new TextDecoder();
        let fullText  = '';
        let hasCode   = false;

        document.getElementById('typing-indicator').classList.add('hidden');

        while (true) {
            const { done, value } = await reader.read();
            if (done) break;
            const lines = decoder.decode(value, { stream: true }).split('\n');
            for (const line of lines) {
                if (!line.startsWith('data: ')) continue;
                try {
                    const data = JSON.parse(line.slice(6).trim());
                    if (data.error) {
                        aiMsgEl.querySelector('.msg-content').innerHTML =
                            `<span style="color:#f87171;">⚠️ Error: ${data.error}</span>`;
                        break;
                    }
                    if (data.done) { hasCode = data.has_code; break; }
                    if (data.content) {
                        fullText += data.content;
                        renderStreamingMessage(aiMsgEl, fullText);
                        scrollToBottom();
                    }
                } catch {}
            }
        }
        finalizeAIMessage(aiMsgEl, fullText, hasCode);
        scrollToBottom();
    } catch {
        document.getElementById('typing-indicator').classList.add('hidden');
        aiMsgEl.querySelector('.msg-content').innerHTML =
            `<span style="color:#f87171;">Koneksi terputus. Coba lagi.</span>`;
    }
    isStreaming = false;
    document.getElementById('message-input').focus();
}

// ===== MESSAGE RENDERING =====
function appendUserMessage(text, file) {
    const container = document.getElementById('messages-list');
    const div = document.createElement('div');
    div.className = 'flex gap-4 justify-end';
    let fileHtml = '';
    if (file) {
        if (file.type.startsWith('image/')) {
            fileHtml = `<div class="mb-2"><img src="${URL.createObjectURL(file)}" class="max-w-xs max-h-48 rounded-xl object-cover"/></div>`;
        } else {
            fileHtml = `<div class="mb-2 flex items-center gap-2 text-xs" style="color:var(--subtext)"><span>📄</span><span>${file.name}</span></div>`;
        }
    }
    div.innerHTML = `
        <div class="max-w-[80%]">
            ${fileHtml}
            ${text ? `<div class="msg-user rounded-2xl rounded-tr-md px-4 py-3 text-sm" style="color:var(--text)">${escapeHtml(text).replace(/\n/g,'<br>')}</div>` : ''}
        </div>
        <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold text-white shrink-0 mt-1"
             style="background:linear-gradient(135deg,#7c3aed,#6366f1)">${USER_INIT}</div>`;
    container.appendChild(div);
}

function appendAIMessage() {
    const container = document.getElementById('messages-list');
    const div = document.createElement('div');
    div.className = 'flex gap-4';
    div.innerHTML = `
        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 mt-1" style="background:linear-gradient(135deg,#7c3aed,#3b82f6)">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="8" r="4" fill="white"/><ellipse cx="12" cy="16" rx="6" ry="3" fill="white" opacity="0.6"/></svg>
        </div>
        <div class="flex-1">
            <div class="msg-ai rounded-2xl rounded-tl-md px-4 py-3">
                <div class="msg-content prose-dark text-sm typing-cursor"></div>
            </div>
            <div class="code-actions hidden mt-2 flex gap-2"></div>
        </div>`;
    container.appendChild(div);
    return div;
}

function renderStreamingMessage(el, text) {
    el.querySelector('.msg-content').innerHTML =
        marked.parse(text.replace(/<code-block[^>]*>[\s\S]*?<\/code-block>/g, '*[Generating code...]*'));
}

function finalizeAIMessage(el, text, hasCode) {
    const contentEl = el.querySelector('.msg-content');
    contentEl.classList.remove('typing-cursor');
    let codeContent = null, codeLang = 'html', displayText = text;
    const cbMatch = text.match(/<code-block lang="([^"]+)">([\s\S]*?)<\/code-block>/);
    const mdMatch = text.match(/```(\w+)?\n([\s\S]*?)```/);
    if (cbMatch) {
        codeLang = cbMatch[1]; codeContent = cbMatch[2].trim();
        displayText = text.replace(cbMatch[0], `\n\n> 📦 Kode **${codeLang.toUpperCase()}** siap di-preview!\n`);
    } else if (mdMatch) {
        codeLang = mdMatch[1] || 'html'; codeContent = mdMatch[2].trim();
    }
    contentEl.innerHTML = marked.parse(displayText);
    contentEl.querySelectorAll('pre code').forEach(b => hljs.highlightElement(b));
    if (hasCode && codeContent) {
        const actEl = el.querySelector('.code-actions');
        actEl.classList.remove('hidden');
        actEl.innerHTML = `
            <button onclick="openPreview(${JSON.stringify(codeContent).replace(/</g,'\\u003c')},'${codeLang}')"
                class="btn-glow px-4 py-2 text-xs font-medium rounded-xl text-white flex items-center gap-2">
                <span>🖥</span> Preview Hasil
            </button>
            <button onclick="copyCode(${JSON.stringify(codeContent).replace(/</g,'\\u003c')})"
                class="px-4 py-2 text-xs font-medium rounded-xl border transition-colors hover:bg-white/5"
                style="border-color:var(--border);color:var(--subtext)">📋 Copy Kode</button>`;
        currentPreviewCode = codeContent;
    }
}

// ===== PREVIEW =====
function openPreview(code, lang) {
    currentPreviewCode = code;
    document.getElementById('preview-lang').textContent = lang.toUpperCase();
    document.getElementById('preview-frame').srcdoc      = code;
    document.getElementById('raw-code').textContent      = code;
    document.getElementById('preview-modal').classList.remove('hidden');
    switchTab('preview');
}
function closePreview() { document.getElementById('preview-modal').classList.add('hidden'); }
function switchTab(tab) {
    document.getElementById('preview-frame').classList.toggle('hidden', tab !== 'preview');
    document.getElementById('code-view').classList.toggle('hidden',    tab !== 'code');
    document.getElementById('tab-preview').style.borderColor = tab === 'preview' ? '#7c3aed' : 'transparent';
    document.getElementById('tab-preview').style.color       = tab === 'preview' ? 'white' : 'var(--muted)';
    document.getElementById('tab-code').style.borderColor    = tab === 'code'    ? '#7c3aed' : 'transparent';
    document.getElementById('tab-code').style.color          = tab === 'code'    ? 'white'   : 'var(--muted)';
}
async function copyPreviewCode() { await navigator.clipboard.writeText(currentPreviewCode); Toast.fire({icon:'success',title:'Kode disalin!'}); }
async function copyCode(code)    { await navigator.clipboard.writeText(code); Toast.fire({icon:'success',title:'Kode disalin!'}); }

// ===== UTILS =====
function scrollToBottom() { const c = document.getElementById('messages-container'); c.scrollTop = c.scrollHeight; }
function escapeHtml(s) { return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
function addToSidebar(id, title) {
    const list = document.getElementById('chat-list');
    const div  = document.createElement('div');
    div.className = 'sidebar-item sidebar-item-active group flex items-center gap-2 px-3 py-2.5 rounded-xl cursor-pointer transition-all duration-150';
    div.dataset.sessionId = id; div.dataset.title = title;
    div.onclick = () => loadSession(id);
    div.innerHTML = `<div class="flex-1 min-w-0">
        <p class="text-xs font-medium truncate text-white">${currentMode==='vibe'?'⚡ ':''}${title}</p>
        <p class="text-xs mt-0.5" style="color:var(--muted)">Baru saja</p></div>`;
    list.insertBefore(div, list.children[1]);
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') closePreview(); });
scrollToBottom();
setMode(currentMode);
</script>
@endpush
@endsection
