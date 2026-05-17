@extends('layouts.app')

@section('title', 'Masuk — Chatty AI')
@section('meta_description', 'Masuk ke akun Chatty AI dan mulai chat dengan AI canggih.')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4" style="background: #080810;">
    <!-- Glow orbs -->
    <div class="glow-orb w-96 h-96 bg-purple-900/20 -top-20 -left-20 opacity-60"></div>
    <div class="glow-orb w-80 h-80 bg-blue-900/20 bottom-0 right-0 opacity-40"></div>

    <div class="w-full max-w-md relative z-10">
        <!-- Logo header -->
        <div class="text-center mb-8">
            <a href="{{ route('landing') }}" class="inline-flex items-center gap-3 mb-6">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center" style="background: linear-gradient(135deg, #7c3aed, #3b82f6);">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="8" r="4" fill="white"/>
                        <path d="M8 8c0-2.2 1.8-4 4-4s4 1.8 4 4" stroke="white" stroke-width="1.5" fill="none"/>
                        <ellipse cx="12" cy="16" rx="6" ry="3" fill="white" opacity="0.6"/>
                        <circle cx="9" cy="9" r="1" fill="#7c3aed"/>
                        <circle cx="15" cy="9" r="1" fill="#7c3aed"/>
                        <path d="M10 11.5c.5.5 1 .7 2 .7s1.5-.2 2-.7" stroke="#7c3aed" stroke-width="1.2" fill="none" stroke-linecap="round"/>
                    </svg>
                </div>
                <span class="text-2xl font-bold font-display text-white">Chatty <span style="background: linear-gradient(135deg, #a78bfa, #60a5fa); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">AI</span></span>
            </a>
            <h1 class="font-display text-2xl font-bold text-white">Selamat datang kembali</h1>
            <p class="text-subtext text-sm mt-2">Masuk untuk melanjutkan chat dengan AI</p>
        </div>

        <!-- Demo accounts autofill -->
        <div class="mb-4 rounded-2xl p-4 border" style="background: rgba(124,58,237,0.08); border-color: rgba(124,58,237,0.25);">
            <p class="text-xs font-semibold mb-3 flex items-center gap-1.5" style="color: #a78bfa;">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Autofill akun demo — klik untuk login cepat
            </p>
            <div class="flex flex-wrap gap-2">
                @php
                $demoAccounts = [
                    ['label' => 'Demo User',    'login' => 'demo@chattyai.com',  'password' => 'demo1234'],
                    ['label' => 'Budi',         'login' => 'budi@chattyai.com',  'password' => 'budi1234'],
                    ['label' => 'Siti',         'login' => 'siti@chattyai.com',  'password' => 'siti1234'],
                    ['label' => 'Andi',         'login' => 'andi@chattyai.com',  'password' => 'andi1234'],
                    ['label' => '🛡️ Admin',     'login' => 'admin@chattyai.com', 'password' => 'admin1234'],
                ];
                @endphp
                @foreach($demoAccounts as $acc)
                <button
                    type="button"
                    onclick="autofill('{{ $acc['login'] }}', '{{ $acc['password'] }}')"
                    class="autofill-btn px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-150"
                    style="background: rgba(124,58,237,0.15); color: #c4b5fd; border: 1px solid rgba(124,58,237,0.3);"
                    onmouseover="this.style.background='rgba(124,58,237,0.35)'"
                    onmouseout="this.style.background='rgba(124,58,237,0.15)'"
                >
                    {{ $acc['label'] }}
                </button>
                @endforeach
            </div>
        </div>

        <!-- Form card -->
        <div class="rounded-2xl p-8 border" style="background: var(--card); border-color: var(--border);">
            @if($errors->any())
            <div class="mb-5 p-4 rounded-xl border text-sm" style="background: rgba(239,68,68,0.1); border-color: rgba(239,68,68,0.3); color: #fca5a5;">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5" id="loginForm">
                @csrf

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--subtext);">Email atau Username</label>
                    <input
                        type="text"
                        name="login"
                        id="login"
                        value="{{ old('login') }}"
                        placeholder="email@contoh.com atau username"
                        required
                        class="input-dark w-full rounded-xl px-4 py-3 text-sm focus:ring-0"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--subtext);">Password</label>
                    <div class="relative">
                        <input
                            type="password"
                            name="password"
                            id="password"
                            placeholder="Password kamu"
                            required
                            class="input-dark w-full rounded-xl px-4 py-3 pr-12 text-sm focus:ring-0"
                        >
                        <button type="button" onclick="togglePass()" class="absolute right-4 top-1/2 -translate-y-1/2" style="color: var(--muted);" title="Toggle password">
                            <svg id="eye-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded" style="accent-color: #7c3aed;">
                        <span class="text-sm" style="color: var(--subtext);">Ingat saya</span>
                    </label>
                </div>

                <button type="submit" class="btn-glow w-full py-3.5 rounded-xl text-sm font-semibold text-white">
                    <span>Masuk Sekarang</span>
                </button>
            </form>
        </div>

        <p class="text-center text-sm mt-6" style="color: var(--muted);">
            Belum punya akun?
            <a href="{{ route('register') }}" style="color: #a78bfa;" class="font-medium hover:underline">Daftar gratis</a>
        </p>
    </div>
</div>

@push('scripts')
<script>
function togglePass() {
    const p = document.getElementById('password');
    p.type = p.type === 'password' ? 'text' : 'password';
}

function autofill(login, password) {
    const loginInput    = document.getElementById('login');
    const passwordInput = document.getElementById('password');

    // Animasi efek ketik
    loginInput.value    = '';
    passwordInput.value = '';
    loginInput.style.borderColor    = '#7c3aed';
    passwordInput.style.borderColor = '#7c3aed';

    let i = 0;
    const typeLogin = setInterval(() => {
        loginInput.value += login[i++];
        if (i >= login.length) {
            clearInterval(typeLogin);
            let j = 0;
            const typePass = setInterval(() => {
                passwordInput.value += password[j++];
                if (j >= password.length) {
                    clearInterval(typePass);
                    loginInput.style.borderColor    = '';
                    passwordInput.style.borderColor = '';
                }
            }, 30);
        }
    }, 25);
}
</script>
@endpush
@endsection
