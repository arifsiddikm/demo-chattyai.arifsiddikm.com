@extends('layouts.app')

@section('title', 'Daftar — Chatty AI')
@section('meta_description', 'Buat akun Chatty AI gratis dan mulai chat dengan AI canggih.')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-10" style="background: #080810;">
    <div class="glow-orb w-96 h-96 bg-purple-900/20 -top-20 right-0 opacity-50"></div>
    <div class="glow-orb w-80 h-80 bg-blue-900/20 bottom-0 left-0 opacity-40"></div>

    <div class="w-full max-w-md relative z-10">
        <div class="text-center mb-8">
            <a href="{{ route('landing') }}" class="inline-flex items-center gap-3 mb-6">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center" style="background: linear-gradient(135deg, #7c3aed, #3b82f6);">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="8" r="4" fill="white"/>
                        <ellipse cx="12" cy="16" rx="6" ry="3" fill="white" opacity="0.6"/>
                        <circle cx="9" cy="9" r="1" fill="#7c3aed"/>
                        <circle cx="15" cy="9" r="1" fill="#7c3aed"/>
                    </svg>
                </div>
                <span class="text-2xl font-bold font-display text-white">Chatty <span style="background: linear-gradient(135deg, #a78bfa, #60a5fa); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">AI</span></span>
            </a>
            <h1 class="font-display text-2xl font-bold text-white">Buat akun baru</h1>
            <p class="text-subtext text-sm mt-2">Gratis selamanya, tidak perlu kartu kredit</p>
        </div>

        <div class="rounded-2xl p-8 border" style="background: var(--card); border-color: var(--border);">
            @if($errors->any())
            <div class="mb-5 p-4 rounded-xl border text-sm" style="background: rgba(239,68,68,0.1); border-color: rgba(239,68,68,0.3); color: #fca5a5;">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--subtext);">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="John Doe" required
                        class="input-dark w-full rounded-xl px-4 py-3 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--subtext);">Username</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm" style="color: var(--muted);">@</span>
                        <input type="text" name="username" value="{{ old('username') }}" placeholder="username_kamu" required
                            class="input-dark w-full rounded-xl pl-8 pr-4 py-3 text-sm">
                    </div>
                    <p class="text-xs mt-1" style="color: var(--muted);">Hanya huruf, angka, dan underscore.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--subtext);">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="email@contoh.com" required
                        class="input-dark w-full rounded-xl px-4 py-3 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--subtext);">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" placeholder="Min. 6 karakter" required
                            class="input-dark w-full rounded-xl px-4 py-3 pr-12 text-sm">
                        <button type="button" onclick="togglePass('password')" class="absolute right-4 top-1/2 -translate-y-1/2" style="color: var(--muted);">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2" style="color: var(--subtext);">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Ulangi password" required
                        class="input-dark w-full rounded-xl px-4 py-3 text-sm">
                </div>

                <button type="submit" class="btn-glow w-full py-3.5 rounded-xl text-sm font-semibold text-white">
                    <span>Buat Akun Gratis</span>
                </button>
            </form>
        </div>

        <p class="text-center text-sm mt-6" style="color: var(--muted);">
            Sudah punya akun?
            <a href="{{ route('login') }}" style="color: #a78bfa;" class="font-medium hover:underline">Masuk di sini</a>
        </p>
    </div>
</div>

@push('scripts')
<script>
function togglePass(id) {
    const p = document.getElementById(id);
    p.type = p.type === 'password' ? 'text' : 'password';
}
</script>
@endpush
@endsection
