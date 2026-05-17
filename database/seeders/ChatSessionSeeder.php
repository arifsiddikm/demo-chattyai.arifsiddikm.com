<?php

namespace Database\Seeders;

use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\User;
use Illuminate\Database\Seeder;

class ChatSessionSeeder extends Seeder
{
    public function run(): void
    {
        $dummyChats = [
            // Akun demo@chattyai.com
            'demo@chattyai.com' => [
                [
                    'title'   => 'Cara buat REST API dengan Laravel',
                    'model'   => 'gpt-4o',
                    'is_vibe_coding' => false,
                    'messages' => [
                        ['role' => 'user',      'content' => 'Gimana cara bikin REST API sederhana pakai Laravel 12?'],
                        ['role' => 'assistant', 'content' => "Oke, gw jelasin step-by-step ya!\n\n**1. Buat route di `routes/api.php`**\n```php\nRoute::apiResource('posts', PostController::class);\n```\n\n**2. Buat controller**\n```bash\nphp artisan make:controller PostController --api\n```\n\n**3. Di controller, return JSON**\n```php\npublic function index() {\n    return response()->json(Post::all());\n}\n```\n\nMau gw jelasin lebih detail bagian mana?"],
                        ['role' => 'user',      'content' => 'Gimana cara handle authentication-nya?'],
                        ['role' => 'assistant', 'content' => "Untuk auth di Laravel API, pake **Sanctum** paling simpel:\n\n```bash\nphp artisan install:api\n```\n\nTerus di `User` model tambahin `HasApiTokens`. Buat endpoint login yang return token:\n```php\n\$token = \$user->createToken('api-token')->plainTextToken;\nreturn response()->json(['token' => \$token]);\n```\n\nDi route yang butuh auth, pakai middleware `auth:sanctum`. Simple kan? 🚀"],
                    ],
                ],
                [
                    'title'   => 'Perbedaan useState vs useReducer',
                    'model'   => 'gpt-4o',
                    'is_vibe_coding' => false,
                    'messages' => [
                        ['role' => 'user',      'content' => 'Kapan harus pakai useState dan kapan pakai useReducer di React?'],
                        ['role' => 'assistant', 'content' => "Singkatnya:\n\n**useState** → cocok untuk state sederhana, independen\n- Counter, toggle, input field, loading state\n\n**useReducer** → cocok kalau state kompleks atau saling bergantung\n- Form dengan banyak field\n- State yang ada multiple actions (add, remove, update, reset)\n- Mirip Redux tapi lokal\n\nRule of thumb: kalau state update-mu makin ribet dan banyak kondisi, saatnya pindah ke `useReducer`. 👍"],
                    ],
                ],
                [
                    'title'   => 'Vibe Coding: Landing Page Keren',
                    'model'   => 'gpt-4o',
                    'is_vibe_coding' => true,
                    'messages' => [
                        ['role' => 'user',      'content' => 'Buatin landing page modern dengan hero section dan CTA button, pakai Tailwind'],
                        ['role' => 'assistant', 'content' => "Nih, landing page modern siap pakai:\n\n```html\n<!DOCTYPE html>\n<html lang=\"id\">\n<head>\n    <meta charset=\"UTF-8\">\n    <script src=\"https://cdn.tailwindcss.com\"></script>\n</head>\n<body class=\"bg-gray-950 text-white\">\n    <section class=\"min-h-screen flex flex-col items-center justify-center text-center px-6\">\n        <span class=\"text-purple-400 text-sm font-semibold tracking-widest uppercase mb-4\">✦ Selamat Datang</span>\n        <h1 class=\"text-5xl md:text-7xl font-extrabold leading-tight mb-6\">\n            Bangun Sesuatu<br>\n            <span class=\"text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-blue-400\">yang Luar Biasa</span>\n        </h1>\n        <p class=\"text-gray-400 text-lg max-w-xl mb-10\">Platform terbaik untuk mewujudkan ide-idemu menjadi produk nyata.</p>\n        <a href=\"#\" class=\"px-8 py-4 rounded-full bg-gradient-to-r from-purple-600 to-blue-600 font-bold text-lg hover:scale-105 transition-transform shadow-lg shadow-purple-500/30\">\n            Mulai Sekarang →\n        </a>\n    </section>\n</body>\n</html>\n```\n\nTinggal copy-paste, langsung jalan! 🔥"],
                    ],
                ],
            ],
            // Akun budi@chattyai.com
            'budi@chattyai.com' => [
                [
                    'title'   => 'Optimasi query MySQL',
                    'model'   => 'gpt-4o',
                    'is_vibe_coding' => false,
                    'messages' => [
                        ['role' => 'user',      'content' => 'Query MySQL gw lambat banget, ada tips optimasi?'],
                        ['role' => 'assistant', 'content' => "Beberapa tips optimasi query MySQL:\n\n1. **Gunakan INDEX** di kolom yang sering di-WHERE atau JOIN\n```sql\nCREATE INDEX idx_user_email ON users(email);\n```\n\n2. **EXPLAIN** dulu sebelum optimize\n```sql\nEXPLAIN SELECT * FROM orders WHERE user_id = 1;\n```\n\n3. **Hindari SELECT \*** — pilih kolom yang perlu saja\n\n4. **Limit JOIN** — jangan terlalu banyak table join dalam 1 query\n\n5. **Gunakan eager loading** di Laravel, hindari N+1 problem\n```php\n\$users = User::with('orders')->get();\n```\n\nCoba share query-nya, gw bantu analyze! 🔍"],
                    ],
                ],
            ],
            // Akun siti@chattyai.com
            'siti@chattyai.com' => [
                [
                    'title'   => 'Resep nasi goreng spesial',
                    'model'   => 'gpt-4o',
                    'is_vibe_coding' => false,
                    'messages' => [
                        ['role' => 'user',      'content' => 'Kasih resep nasi goreng spesial dong!'],
                        ['role' => 'assistant', 'content' => "**Nasi Goreng Spesial** 🍳\n\n**Bahan:**\n- 2 piring nasi putih (sebaiknya nasi dingin/sisa)\n- 2 butir telur\n- 3 siung bawang putih\n- 4 siung bawang merah\n- 2 sdm kecap manis\n- 1 sdm saus tiram\n- Garam, gula, lada secukupnya\n- Minyak goreng\n\n**Cara masak:**\n1. Tumis bawang merah & putih hingga harum\n2. Masukkan telur, orak-arik\n3. Masukkan nasi, aduk rata\n4. Tambahkan kecap, saus tiram, bumbu\n5. Masak dengan api besar, aduk terus 3-5 menit\n6. Sajikan dengan kerupuk & acar 😋"],
                    ],
                ],
                [
                    'title'   => 'Tips belajar bahasa Inggris cepat',
                    'model'   => 'gpt-4o',
                    'is_vibe_coding' => false,
                    'messages' => [
                        ['role' => 'user',      'content' => 'Gimana cara belajar bahasa Inggris efektif dan cepat?'],
                        ['role' => 'assistant', 'content' => "**Tips Belajar English Cepat & Efektif:**\n\n🎯 **Immersion Method**\n- Ganti bahasa HP ke English\n- Tonton series/film pakai subtitle English\n- Dengerin podcast bahasa Inggris 30 menit/hari\n\n📚 **Belajar Kosakata**\n- Target 10 kata baru per hari\n- Pakai Anki (flashcard app) untuk mengingat\n\n🗣️ **Speaking Practice**\n- Latihan ngomong sendiri di depan kaca\n- Cari language exchange partner\n- Join komunitas English di Discord\n\n✍️ **Writing**\n- Tulis jurnal harian dalam bahasa Inggris\n- Gunakan Grammarly untuk cek grammar\n\nKonsistensi > Intensitas. 30 menit tiap hari lebih efektif dari 3 jam sekali seminggu! 💪"],
                    ],
                ],
            ],
        ];

        foreach ($dummyChats as $email => $sessions) {
            $user = User::where('email', $email)->first();
            if (!$user) continue;

            foreach ($sessions as $sessionData) {
                $session = ChatSession::create([
                    'user_id'        => $user->id,
                    'title'          => $sessionData['title'],
                    'model'          => $sessionData['model'],
                    'is_vibe_coding' => $sessionData['is_vibe_coding'],
                ]);

                foreach ($sessionData['messages'] as $msg) {
                    $hasCode = str_contains($msg['content'], '```');
                    $codeContent = null;
                    $codeLang = null;

                    if ($hasCode && preg_match('/```(\w+)?\n(.*?)```/s', $msg['content'], $m)) {
                        $codeLang    = $m[1] ?: 'text';
                        $codeContent = trim($m[2]);
                    }

                    ChatMessage::create([
                        'chat_session_id' => $session->id,
                        'user_id'         => $user->id,
                        'role'            => $msg['role'],
                        'content'         => $msg['content'],
                        'has_code'        => $hasCode,
                        'code_content'    => $codeContent,
                        'code_language'   => $codeLang,
                    ]);
                }
            }
        }
    }
}
