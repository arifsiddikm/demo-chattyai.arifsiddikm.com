@extends('layouts.app')

@section('title', 'Chatty AI — AI Chatbot & Vibe Coding')
@section('meta_description', 'Chatty AI adalah AI chatbot canggih berbasis GPT dengan fitur revolusioner Vibe Coding. Chat pintar, buat website instan hanya dengan instruksi teks.')

@section('content')
<!-- Glow orbs -->
<div class="glow-orb w-96 h-96 bg-purple-900/20 -top-20 -left-20 opacity-60"></div>
<div class="glow-orb w-80 h-80 bg-blue-900/20 top-1/2 right-0 opacity-40"></div>
<div class="glow-orb w-64 h-64 bg-indigo-900/30 bottom-0 left-1/3 opacity-50"></div>

<div class="relative z-10 min-h-screen flex flex-col" style="background: #080810;">

    <!-- Navbar -->
    <nav class="sticky top-0 z-50 border-b border-white/5" style="background: rgba(8,8,16,0.85); backdrop-filter: blur(20px);">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #7c3aed, #3b82f6);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="8" r="4" fill="white"/>
                        <path d="M8 8c0-2.2 1.8-4 4-4s4 1.8 4 4" stroke="white" stroke-width="1.5" fill="none"/>
                        <ellipse cx="12" cy="16" rx="6" ry="3" fill="white" opacity="0.6"/>
                        <circle cx="9" cy="9" r="1" fill="#7c3aed"/>
                        <circle cx="15" cy="9" r="1" fill="#7c3aed"/>
                        <path d="M10 11.5c.5.5 1 .7 2 .7s1.5-.2 2-.7" stroke="#7c3aed" stroke-width="1.2" fill="none" stroke-linecap="round"/>
                    </svg>
                </div>
                <span class="text-xl font-bold font-display text-white">Chatty <span style="background: linear-gradient(135deg, #a78bfa, #60a5fa); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">AI</span></span>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-subtext hover:text-white transition-colors duration-200">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="btn-glow px-5 py-2.5 text-sm font-semibold text-white rounded-xl">
                    <span>Mulai Gratis</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="flex-1 flex flex-col items-center justify-center text-center px-6 py-24">
        <!-- Badge -->
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-medium mb-8 border" style="background: rgba(124,58,237,0.1); border-color: rgba(124,58,237,0.3); color: #a78bfa;">
            <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
            Powered by GPT-4o · Vibe Coding AI
        </div>

        <!-- Heading -->
        <h1 class="font-display text-5xl md:text-7xl font-bold text-white mb-6 leading-tight max-w-4xl">
            Chat Cerdas.<br>
            <span style="background: linear-gradient(135deg, #a78bfa 0%, #60a5fa 50%, #818cf8 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-size: 200% 100%; animation: shimmer 3s linear infinite; background-clip: text;">
                Kode Instan.
            </span>
        </h1>

        <p class="text-lg md:text-xl text-subtext max-w-2xl mb-10 leading-relaxed">
            Chatty AI bukan sekadar chatbot. Dengan fitur <strong class="text-white">Vibe Coding</strong>, cukup jelaskan apa yang kamu mau — AI langsung buatkan kode website yang bisa kamu preview seketika.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 mb-20">
            <a href="{{ route('register') }}" class="btn-glow px-8 py-4 text-base font-semibold text-white rounded-2xl inline-flex items-center gap-2">
                <span>Mulai Chat Sekarang</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
            <a href="{{ route('login') }}" class="px-8 py-4 text-base font-medium rounded-2xl transition-all duration-200 border" style="border-color: var(--border); color: var(--subtext);" onmouseover="this.style.borderColor='rgba(124,58,237,0.5)'; this.style.color='white';" onmouseout="this.style.borderColor='var(--border)'; this.style.color='var(--subtext)';">
                Sudah punya akun? Login
            </a>
        </div>

        <!-- AI Robot mascot SVG -->
        <div class="relative mb-16 animate-float">
            <div class="absolute inset-0 rounded-full blur-3xl opacity-30" style="background: radial-gradient(circle, #7c3aed, #3b82f6); transform: scale(1.5);"></div>
            <svg width="180" height="200" viewBox="0 0 180 200" fill="none" xmlns="http://www.w3.org/2000/svg" class="relative z-10 drop-shadow-2xl">
                <!-- Body glow -->
                <ellipse cx="90" cy="180" rx="60" ry="12" fill="url(#glow)" opacity="0.4"/>
                <!-- Antenna -->
                <line x1="90" y1="20" x2="90" y2="40" stroke="#a78bfa" stroke-width="2"/>
                <circle cx="90" cy="16" r="5" fill="#a78bfa"/>
                <circle cx="90" cy="16" r="3" fill="#c4b5fd" class="animate-pulse"/>
                <!-- Head -->
                <rect x="45" y="40" width="90" height="75" rx="20" fill="url(#headGrad)" stroke="#a78bfa" stroke-width="1.5"/>
                <!-- Eyes -->
                <ellipse cx="70" cy="72" rx="12" ry="12" fill="url(#eyeGrad)"/>
                <ellipse cx="110" cy="72" rx="12" ry="12" fill="url(#eyeGrad)"/>
                <circle cx="70" cy="72" r="5" fill="white"/>
                <circle cx="110" cy="72" r="5" fill="white"/>
                <circle cx="72" cy="70" r="2.5" fill="#1a1a2e"/>
                <circle cx="112" cy="70" r="2.5" fill="#1a1a2e"/>
                <!-- Mouth smile -->
                <path d="M75 90 Q90 102 105 90" stroke="#a78bfa" stroke-width="2.5" fill="none" stroke-linecap="round"/>
                <!-- Cheeks -->
                <ellipse cx="55" cy="82" rx="8" ry="5" fill="#ec4899" opacity="0.3"/>
                <ellipse cx="125" cy="82" rx="8" ry="5" fill="#ec4899" opacity="0.3"/>
                <!-- Neck -->
                <rect x="78" y="115" width="24" height="10" rx="4" fill="#252540"/>
                <!-- Body -->
                <rect x="35" y="125" width="110" height="65" rx="16" fill="url(#bodyGrad)" stroke="#252540" stroke-width="1"/>
                <!-- Chest panel -->
                <rect x="60" y="140" width="60" height="35" rx="8" fill="#0f0f1a" stroke="#3b82f6" stroke-width="1" opacity="0.8"/>
                <!-- Chest lights -->
                <circle cx="73" cy="150" r="4" fill="#a78bfa" class="animate-pulse"/>
                <circle cx="90" cy="150" r="4" fill="#60a5fa"/>
                <circle cx="107" cy="150" r="4" fill="#818cf8" class="animate-pulse"/>
                <!-- Chest signal bar -->
                <rect x="68" y="160" width="6" height="8" rx="1" fill="#7c3aed" opacity="0.8"/>
                <rect x="77" y="156" width="6" height="12" rx="1" fill="#6366f1" opacity="0.8"/>
                <rect x="86" y="158" width="6" height="10" rx="1" fill="#818cf8" opacity="0.8"/>
                <rect x="95" y="155" width="6" height="13" rx="1" fill="#3b82f6" opacity="0.9"/>
                <!-- Arms -->
                <rect x="8" y="128" width="28" height="50" rx="12" fill="url(#armGrad)" stroke="#252540" stroke-width="1"/>
                <rect x="144" y="128" width="28" height="50" rx="12" fill="url(#armGrad)" stroke="#252540" stroke-width="1"/>
                <!-- Hand circles -->
                <circle cx="22" cy="182" r="10" fill="#1a1a2e" stroke="#a78bfa" stroke-width="1.5"/>
                <circle cx="158" cy="182" r="10" fill="#1a1a2e" stroke="#a78bfa" stroke-width="1.5"/>

                <defs>
                    <linearGradient id="headGrad" x1="45" y1="40" x2="135" y2="115" gradientUnits="userSpaceOnUse">
                        <stop offset="0%" stop-color="#1a1a2e"/>
                        <stop offset="100%" stop-color="#14141f"/>
                    </linearGradient>
                    <linearGradient id="eyeGrad" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0%" stop-color="#7c3aed"/>
                        <stop offset="100%" stop-color="#3b82f6"/>
                    </linearGradient>
                    <linearGradient id="bodyGrad" x1="35" y1="125" x2="145" y2="190" gradientUnits="userSpaceOnUse">
                        <stop offset="0%" stop-color="#1a1a2e"/>
                        <stop offset="100%" stop-color="#0f0f1a"/>
                    </linearGradient>
                    <linearGradient id="armGrad" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0%" stop-color="#1e1e30"/>
                        <stop offset="100%" stop-color="#14141f"/>
                    </linearGradient>
                    <radialGradient id="glow">
                        <stop offset="0%" stop-color="#7c3aed"/>
                        <stop offset="100%" stop-color="transparent"/>
                    </radialGradient>
                </defs>
            </svg>
        </div>

        <!-- Features grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl w-full mb-20">
            @php
            $features = [
                ['icon' => '💬', 'title' => 'Chat AI Canggih', 'desc' => 'Chat dengan GPT-4o, upload foto & PDF untuk dianalisis AI secara real-time dengan streaming response.'],
                ['icon' => '⚡', 'title' => 'Vibe Coding', 'desc' => 'Deskripsikan website yang kamu mau, AI langsung generate kode HTML/CSS/JS yang bisa preview seketika.'],
                ['icon' => '🔒', 'title' => 'Riwayat Aman', 'desc' => 'Semua percakapan tersimpan dan terorganisir. Akses kembali chat history kapan saja.'],
            ];
            @endphp
            @foreach($features as $f)
            <div class="rounded-2xl p-6 border text-left group hover:border-purple-600/50 transition-all duration-300" style="background: var(--card); border-color: var(--border);">
                <div class="text-3xl mb-4">{{ $f['icon'] }}</div>
                <h3 class="font-display font-semibold text-white mb-2">{{ $f['title'] }}</h3>
                <p class="text-sm leading-relaxed" style="color: var(--subtext);">{{ $f['desc'] }}</p>
            </div>
            @endforeach
        </div>

        <!-- CTA Bottom -->
        <div class="w-full max-w-2xl rounded-3xl p-8 text-center border" style="background: linear-gradient(135deg, rgba(124,58,237,0.1), rgba(59,130,246,0.05)); border-color: rgba(124,58,237,0.2);">
            <h2 class="font-display text-2xl font-bold text-white mb-3">Siap mencoba Chatty AI?</h2>
            <p class="text-subtext mb-6 text-sm">Daftar gratis, tidak perlu kartu kredit.</p>
            <a href="{{ route('register') }}" class="btn-glow px-8 py-3.5 text-sm font-semibold text-white rounded-xl inline-flex items-center gap-2">
                <span>Buat Akun Gratis</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t py-8 text-center text-sm" style="border-color: var(--border); color: var(--muted);">
        <p>© {{ date('Y') }} Chatty AI. Dibuat dengan ❤️ menggunakan Laravel & AI.</p>
    </footer>
</div>

<style>
@keyframes shimmer {
    0% { background-position: -200% center; }
    100% { background-position: 200% center; }
}
</style>
@endsection
