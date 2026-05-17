# CLAUDE PROMPT — ChattyAI (PRD Lengkap)

> Upload file ini ke sesi Claude baru. Claude akan membangun ulang website ChattyAI secara lengkap dari awal berdasarkan spesifikasi di bawah.

---

## Identitas Project

- **Nama:** ChattyAI
- **Konsep:** Web chat AI sederhana mirip ChatGPT — pengguna bisa register/login, buat multiple chat session, dan chat dengan AI menggunakan streaming response
- **Demo:** https://demo-chattyai.arifsiddikm.com
- **Stack:** Laravel 12 + PHP 8.3, SQLite/MySQL, Tailwind CSS CDN, Alpine.js, OpenAI API

---

## Stack & Dependency

```
- Laravel 12 (fresh install)
- PHP 8.3+
- Database: SQLite (default) atau MySQL
- Frontend: Tailwind CSS via CDN (tidak pakai build step)
- Alpine.js via CDN
- OpenAI PHP Client (openai-php/client)
- Google Fonts: Inter
- Tidak ada Livewire, tidak ada Inertia, tidak ada Filament
```

---

## Struktur Database

### Tabel `users`
```
- id (bigint PK)
- name (string)
- username (string, unique)
- email (string, unique)
- email_verified_at (timestamp, nullable)
- password (string, hashed)
- avatar (string, nullable)
- role (string, default: 'user') — nilai: 'user' | 'admin'
- remember_token
- timestamps
```

### Tabel `chat_sessions`
```
- id (bigint PK)
- user_id (FK → users)
- title (string, default: 'New Chat')
- model (string, default: 'gpt-4o') — nilai: gpt-4o | gpt-4o-mini | gpt-3.5-turbo
- is_vibe_coding (boolean, default: false)
- timestamps
```

### Tabel `chat_messages`
```
- id (bigint PK)
- chat_session_id (FK → chat_sessions)
- user_id (FK → users)
- role (string) — nilai: 'user' | 'assistant'
- content (text, nullable)
- attachment_path (string, nullable)
- attachment_type (string, nullable) — nilai: 'image' | 'pdf'
- attachment_name (string, nullable)
- has_code (boolean, default: false)
- code_content (longText, nullable)
- code_language (string, nullable)
- token_used (integer, nullable)
- timestamps
```

---

## Routes

```
GET  /                          → LandingController@index         [name: landing]
GET  /login                     → AuthController@showLogin        [name: login, middleware: guest]
POST /login                     → AuthController@login            [middleware: guest]
GET  /register                  → AuthController@showRegister     [name: register, middleware: guest]
POST /register                  → AuthController@register         [middleware: guest]
POST /logout                    → AuthController@logout           [name: logout, middleware: auth]

GET  /chat                      → ChatController@index            [name: chat.index, middleware: auth]
GET  /chat/{session}            → ChatController@show             [name: chat.show, middleware: auth]
POST /chat/new                  → ChatController@newSession       [name: chat.new, middleware: auth]
DELETE /chat/{session}          → ChatController@deleteSession    [name: chat.delete, middleware: auth]
PUT  /chat/{session}/rename     → ChatController@renameSession    [name: chat.rename, middleware: auth]
POST /chat/{session}/stream     → ChatController@stream           [name: chat.stream, middleware: auth]
```

---

## Controllers

### AuthController
- `showLogin()` → return view `auth.login`
- `login(Request)` → validasi email/username + password, login dengan `Auth::attempt`, redirect ke `chat.index`
  - Field login menerima email ATAU username (cek keduanya)
- `showRegister()` → return view `auth.register`
- `register(Request)` → validasi name/username/email/password, buat user, auto-login, redirect ke `chat.index`
- `logout()` → `Auth::logout()`, redirect ke `/`

### LandingController
- `index()` → return view `landing.index`

### ChatController
- `index()` → ambil semua session milik user, return `chat.index`
- `show(ChatSession)` → authorize user_id, ambil sessions + messages, return `chat.index`
- `newSession(Request)` → buat session baru, return JSON `{session_id, redirect}`
- `deleteSession(ChatSession)` → authorize, delete, return JSON
- `renameSession(Request, ChatSession)` → authorize, update title, return JSON
- `stream(Request, ChatSession)` → **SSE Streaming**:
  1. Authorize user
  2. Validasi message (nullable|string|max:10000) + attachment (nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf|max:10240)
  3. Handle file upload → simpan ke `chat-attachments` di storage public
  4. Simpan user message ke DB
  5. Ambil history 20 pesan terakhir
  6. Build messages array untuk OpenAI (support vision jika ada image attachment)
  7. Auto-title session jika masih 'New Chat' dari Str::limit($userMessage, 40)
  8. Stream SSE response, simpan full response ke DB setelah selesai
  9. Deteksi blok kode di response (```lang\ncode``` atau <code-block lang="">)
  10. Response header: Content-Type: text/event-stream, Cache-Control: no-cache, X-Accel-Buffering: no

### AIService
- `buildMessages(array $history, string $userMessage, ?string $base64Image, ?string $attachmentType)` → build messages array untuk OpenAI
- `streamChat(array $messages, bool $isVibeCoding)` → generator, stream dari OpenAI API
- System prompt normal: AI assistant ramah, jawab dalam bahasa yang sama dengan user
- System prompt vibe coding: fokus generate kode, output dalam tag `<code-block lang="html">...</code-block>` atau markdown code block

---

## Views & UI Design

### Design System
```
Background utama : #080810 (very dark navy/black)
Card background  : rgba(255,255,255,0.04) atau var(--card)
Border           : rgba(255,255,255,0.08) atau var(--border)
Text utama       : #f1f1f3 (var(--text))
Subtext          : #a0a0b0 (var(--subtext))
Muted            : #606070 (var(--muted))
Accent/Primary   : gradient linear-gradient(135deg, #7c3aed, #3b82f6) — purple ke blue
Accent color     : #7c3aed (purple-600)

Font             : Inter (Google Fonts), class font-sans
Display font     : font-display (bisa pakai juga Inter dengan font-weight 800)
Border radius    : rounded-2xl untuk cards, rounded-xl untuk inputs/buttons
```

### layouts/app.blade.php
- Meta tags lengkap (title, description, og:tags)
- Load Tailwind CSS CDN, Alpine.js CDN
- Load Google Fonts Inter
- CSS Variables di `:root` untuk color system
- Global CSS: `.glow-orb`, `.btn-glow`, `.input-dark`, `.font-display`, dll
- `@yield('content')` dan `@stack('scripts')`
- Tidak ada navbar global (landing dan chat punya header masing-masing)

### landing/index.blade.php
- Hero section: judul besar "Chat dengan AI, Lebih Cerdas" dengan gradient text, subtitle, 2 CTA button (Mulai Gratis → /register, Coba Demo → /login)
- Glow orbs animasi di background
- Fitur section: 3-4 feature card (Streaming Real-time, Multiple Session, Upload File, Vibe Coding)
- CTA bottom section
- Footer sederhana

### auth/login.blade.php
- Dark background #080810 dengan glow orbs
- Logo ChattyAI di atas form
- **Autofill demo accounts**: box kecil di atas form card berisi tombol-tombol akun demo (Demo User, Budi, Siti, Andi, Admin). Klik tombol → animasi efek ketik mengisi field login & password otomatis
- Form: field "Email atau Username" (name=login) + Password + Remember Me checkbox
- Toggle show/hide password
- Link ke register
- Validasi error di atas form

### auth/register.blade.php
- Mirip login, form: Nama Lengkap, Username, Email, Password, Konfirmasi Password
- Link ke login

### chat/index.blade.php (halaman utama chat — layout 2 panel)

**Sidebar (kiri, w-72)**
- Header: Logo ChattyAI + tombol "Chat Baru"
- List session: tiap item tampilkan title, relative time, ikon tong sampah (delete) + ikon edit (rename)
- Rename inline (click → input field)
- Active session highlight
- Tombol logout di bawah sidebar
- User info (nama + email) di bottom sidebar

**Main panel (kanan)**
- Header: nama session aktif + dropdown pilih model (gpt-4o, gpt-4o-mini, gpt-3.5-turbo) + toggle Vibe Coding
- Area pesan (scrollable):
  - Pesan user: bubble kanan, background accent transparan
  - Pesan assistant: bubble kiri dengan avatar AI icon
  - Render markdown di pesan AI (bold, code inline, heading)
  - Blok kode dengan syntax highlight + tombol copy
  - Attachment image: tampil sebagai thumbnail
  - Attachment PDF: tampil sebagai label file
  - Animasi cursor blinking saat streaming
- Empty state: tampil saat belum ada session/pesan, ada quick prompt suggestions
- Input area (bottom):
  - Textarea auto-resize (max 6 baris)
  - Tombol attach file (paperclip icon) — preview attachment sebelum kirim
  - Tombol kirim (send icon, disabled saat kosong atau sedang streaming)
  - Kirim dengan Enter (Shift+Enter untuk newline)
  - Indikator "AI sedang mengetik..."

**Vibe Coding Mode** (aktif jika is_vibe_coding = true)
- Split view atau tab: kiri chat, kanan live preview iframe
- Tombol toggle fullscreen preview
- Auto-update preview saat response selesai (inject kode ke iframe)
- Tombol copy kode dan download

---

## Seeder Data

### UserSeeder
5 akun dummy:
```
Demo User  | demo  | demo@chattyai.com  | demo1234  | user
Budi       | budi  | budi@chattyai.com  | budi1234  | user
Siti       | siti  | siti@chattyai.com  | siti1234  | user
Andi       | andi  | andi@chattyai.com  | andi1234  | user
Admin      | admin | admin@chattyai.com | admin1234 | admin
```

### ChatSessionSeeder
Buat dummy chat sessions + messages untuk akun demo, budi, siti — berisi percakapan realistis dalam Bahasa Indonesia tentang:
- Tutorial Laravel/PHP
- Tips React/JavaScript
- Pertanyaan umum (resep, belajar bahasa, dll)
- Minimal 1 session Vibe Coding dengan kode HTML

---

## Fitur Teknis Penting

1. **SSE Streaming**: Gunakan `response()->stream()` Laravel, echo `data: {json}\n\n`, flush tiap chunk
2. **OpenAI Vision**: Jika ada image attachment, include sebagai `image_url` dengan base64 data URI di messages
3. **Auth Login Fleksibel**: Coba login dengan email dulu, jika gagal coba dengan username
4. **Auto-title**: Setelah pesan pertama, update title session dengan `Str::limit($userMessage, 40)`
5. **Kode Detection**: Deteksi blok ``` di response, simpan ke `code_content` dan `code_language`
6. **File Upload**: Store di `storage/app/public/chat-attachments`, buat symlink dengan `php artisan storage:link`
7. **Authorization**: Selalu cek `session->user_id === Auth::id()` di ChatController, abort 403 jika tidak match

---

## .env Variables yang Diperlukan

```env
APP_NAME=ChattyAI
APP_ENV=local
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite

OPENAI_API_KEY=sk-your-key-here
OPENAI_MODEL=gpt-4o
OPENAI_MAX_TOKENS=2048
```

---

## Catatan Implementasi

- Tidak perlu build step (tidak ada npm run build) — semua CDN
- Tailwind CDN: `<script src="https://cdn.tailwindcss.com"></script>`
- Alpine.js CDN: `<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>`
- Storage public disk, bukan private
- Session driver: file atau database (bukan cookie)
- Tidak ada admin panel khusus — role admin hanya untuk pembeda di masa depan
- Markdown rendering di frontend pakai library `marked.js` via CDN

---

*Generated by ChattyAI — arifsiddikm.com*
