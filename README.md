# ChattyAI — AI Chat Web App

Website chat AI sederhana mirip ChatGPT, dibangun dengan Laravel 12. Mendukung streaming response, multiple chat session, upload gambar, dan mode Vibe Coding.

🌐 **Live Demo:** [demo-chattyai.arifsiddikm.com](https://demo-chattyai.arifsiddikm.com)

---

## Tech Stack

- **Backend:** PHP 8.3 + Laravel 12
- **Database:** SQLite / MySQL
- **Frontend:** Tailwind CSS CDN · Alpine.js
- **AI:** OpenAI API (GPT-4o, GPT-4o-mini, GPT-3.5-turbo)
- **Font:** Inter + Custom Display Font (Google Fonts)

---

## Fitur

**Autentikasi**
- Register & Login (email atau username)
- Remember Me
- Autofill akun demo di halaman login

**Chat**
- Streaming response (Server-Sent Events / SSE)
- Multiple chat session dengan sidebar
- Rename & hapus session
- Auto-title session dari pesan pertama
- Riwayat percakapan (last 20 messages sebagai konteks)

**Upload & Attachment**
- Upload gambar (jpg, jpeg, png, gif, webp)
- Upload PDF
- Preview attachment sebelum kirim

**Vibe Coding Mode**
- Mode khusus untuk generate kode
- Live preview kode HTML/CSS/JS
- Deteksi otomatis blok kode dari response AI

**Pilihan Model AI**
- GPT-4o
- GPT-4o-mini
- GPT-3.5-turbo

---

## Instalasi

```bash
# 1. Clone repo
git clone https://github.com/arifsiddikm/chattyai.git
cd chattyai

# 2. Install dependencies
composer install

# 3. Konfigurasi .env
cp file env to .env and setting your password
php artisan key:generate

# 4. Isi OPENAI_API_KEY di .env
OPENAI_API_KEY=sk-...

# 5. Setup database (SQLite default)
touch database/database.sqlite
php artisan migrate
php artisan db:seed

# 6. Storage link
php artisan storage:link

# 7. Jalankan server
php artisan serve
```

Akses di `http://localhost:8000`

---

## Akun Demo

Setelah `db:seed`, akun berikut tersedia (autofill tersedia di halaman login):

| Nama       | Email                    | Password    | Role  |
|------------|--------------------------|-------------|-------|
| Demo User  | demo@chattyai.com        | demo1234    | user  |
| Budi       | budi@chattyai.com        | budi1234    | user  |
| Siti       | siti@chattyai.com        | siti1234    | user  |
| Andi       | andi@chattyai.com        | andi1234    | user  |
| Admin      | admin@chattyai.com       | admin1234   | admin |

---

## Konfigurasi MySQL (opsional)

Edit `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=chattyai
DB_USERNAME=root
DB_PASSWORD=
```

Lalu jalankan ulang:
```bash
php artisan migrate
php artisan db:seed
```

---

## Konfigurasi AI

Edit `.env`:
```env
OPENAI_API_KEY=sk-your-key-here
OPENAI_MODEL=gpt-4o
OPENAI_MAX_TOKENS=2048
```

---

### Support me on
<a href="https://saweria.co/arifsiddikm" target="_blank"><img src="https://user-images.githubusercontent.com/26188697/180601310-e82c63e4-412b-4c36-b7b5-7ba713c80380.png" alt="Sawer me" height="41" width="174"></a>
