<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta -->
    <title>@yield('title', 'Chatty AI') — Chatty AI</title>
    <meta name="description" content="@yield('meta_description', 'Chatty AI — AI chatbot canggih berbasis GPT dengan fitur Vibe Coding. Chat cerdas, buat website instan dengan AI.')">
    <meta name="keywords" content="chatbot AI, vibe coding, AI chat, GPT, Chatty AI, coding AI">
    <meta name="author" content="Chatty AI">
    <meta property="og:title" content="@yield('title', 'Chatty AI')">
    <meta property="og:description" content="@yield('meta_description', 'AI chatbot canggih dengan fitur Vibe Coding.')">
    <meta property="og:type" content="website">
    <meta name="theme-color" content="#0f0f1a">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.svg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,400&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans:  ['DM Sans', 'sans-serif'],
                        display: ['Syne', 'sans-serif'],
                        mono:  ['JetBrains Mono', 'monospace'],
                    },
                    colors: {
                        void: '#080810',
                        surface: '#0f0f1a',
                        panel: '#14141f',
                        card: '#1a1a2e',
                        border: '#252540',
                        muted: '#6b6b8a',
                        text: '#e2e2f0',
                        subtext: '#9090b0',
                        accent: {
                            purple: '#7c3aed',
                            blue:   '#3b82f6',
                            indigo: '#6366f1',
                            glow:   '#a78bfa',
                        }
                    },
                    animation: {
                        'shimmer': 'shimmer 2s linear infinite',
                        'pulse-glow': 'pulseGlow 2s ease-in-out infinite',
                        'float': 'float 3s ease-in-out infinite',
                        'typing': 'typing 0.05s steps(1) infinite',
                    },
                    keyframes: {
                        shimmer: {
                            '0%': { backgroundPosition: '-200% 0' },
                            '100%': { backgroundPosition: '200% 0' },
                        },
                        pulseGlow: {
                            '0%, 100%': { boxShadow: '0 0 10px rgba(124,58,237,0.3)' },
                            '50%': { boxShadow: '0 0 30px rgba(124,58,237,0.7), 0 0 60px rgba(99,102,241,0.3)' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-8px)' },
                        }
                    },
                    backgroundImage: {
                        'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
                    }
                }
            }
        }
    </script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Marked.js for markdown -->
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <!-- Highlight.js for code -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/tokyo-night-dark.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>

    <style>
        * { box-sizing: border-box; }

        :root {
            --surface: #0f0f1a;
            --panel: #14141f;
            --card: #1a1a2e;
            --border: #252540;
            --accent-purple: #7c3aed;
            --accent-blue: #3b82f6;
            --accent-indigo: #6366f1;
            --accent-glow: #a78bfa;
            --text: #e2e2f0;
            --subtext: #9090b0;
            --muted: #6b6b8a;
        }

        html, body { height: 100%; background: var(--surface); color: var(--text); font-family: 'DM Sans', sans-serif; }

        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--muted); }

        /* Glow orb background effect */
        .glow-orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
            z-index: 0;
        }

        /* Shimmer gradient for borders/buttons */
        .shimmer-border {
            background: linear-gradient(90deg, var(--border) 0%, var(--accent-glow) 50%, var(--border) 100%);
            background-size: 200% 100%;
            animation: shimmer 3s linear infinite;
        }

        /* Glowing button */
        .btn-glow {
            background: linear-gradient(135deg, var(--accent-purple), var(--accent-indigo));
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .btn-glow::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, var(--accent-indigo), var(--accent-blue));
            opacity: 0;
            transition: opacity 0.3s;
        }
        .btn-glow:hover::before { opacity: 1; }
        .btn-glow:hover { box-shadow: 0 0 25px rgba(124,58,237,0.5), 0 0 50px rgba(99,102,241,0.2); transform: translateY(-1px); }
        .btn-glow span { position: relative; z-index: 1; }

        /* Input styles */
        .input-dark {
            background: var(--card);
            border: 1px solid var(--border);
            color: var(--text);
            transition: all 0.2s;
        }
        .input-dark:focus {
            outline: none;
            border-color: var(--accent-indigo);
            box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
        }
        .input-dark::placeholder { color: var(--muted); }

        /* Chat message bubble */
        .msg-user {
            background: linear-gradient(135deg, rgba(124,58,237,0.2), rgba(99,102,241,0.15));
            border: 1px solid rgba(124,58,237,0.3);
        }
        .msg-ai {
            background: var(--card);
            border: 1px solid var(--border);
        }

        /* Cursor blink for AI typing */
        .typing-cursor::after {
            content: '▋';
            display: inline;
            color: var(--accent-glow);
            animation: blink 0.7s step-end infinite;
        }
        @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0; } }

        /* Sidebar active item */
        .sidebar-item-active {
            background: linear-gradient(90deg, rgba(124,58,237,0.2), transparent);
            border-left: 2px solid var(--accent-purple);
        }
        .sidebar-item:hover { background: rgba(255,255,255,0.04); }

        /* Code block */
        .code-block pre {
            background: #0d0d1a !important;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 1rem;
            overflow-x: auto;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.85rem;
        }

        /* Markdown prose */
        .prose-dark { color: var(--text); line-height: 1.7; }
        .prose-dark h1, .prose-dark h2, .prose-dark h3 { font-family: 'Syne', sans-serif; margin: 0.8em 0 0.4em; color: #fff; }
        .prose-dark h1 { font-size: 1.4em; }
        .prose-dark h2 { font-size: 1.2em; }
        .prose-dark h3 { font-size: 1.05em; }
        .prose-dark p { margin: 0.5em 0; }
        .prose-dark ul, .prose-dark ol { padding-left: 1.5rem; margin: 0.5em 0; }
        .prose-dark li { margin: 0.25em 0; }
        .prose-dark code:not(pre code) {
            background: rgba(99,102,241,0.15);
            color: var(--accent-glow);
            padding: 0.15em 0.4em;
            border-radius: 4px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.875em;
        }
        .prose-dark blockquote {
            border-left: 3px solid var(--accent-purple);
            padding-left: 1rem;
            color: var(--subtext);
            margin: 0.75em 0;
        }
        .prose-dark table { width: 100%; border-collapse: collapse; margin: 0.75em 0; font-size: 0.9em; }
        .prose-dark th { background: var(--card); padding: 0.5rem 0.75rem; border: 1px solid var(--border); text-align: left; }
        .prose-dark td { padding: 0.5rem 0.75rem; border: 1px solid var(--border); }
        .prose-dark a { color: var(--accent-glow); text-decoration: underline; }
        .prose-dark hr { border: none; border-top: 1px solid var(--border); margin: 1em 0; }

        /* SweetAlert dark theme override */
        .swal2-popup { background: var(--card) !important; color: var(--text) !important; border: 1px solid var(--border) !important; border-radius: 16px !important; }
        .swal2-title { color: var(--text) !important; font-family: 'Syne', sans-serif !important; }
        .swal2-html-container { color: var(--subtext) !important; }
        .swal2-confirm { background: linear-gradient(135deg, var(--accent-purple), var(--accent-indigo)) !important; border: none !important; border-radius: 8px !important; }
        .swal2-cancel { background: var(--border) !important; color: var(--text) !important; border: none !important; border-radius: 8px !important; }
        .swal2-icon { border-color: var(--accent-purple) !important; color: var(--accent-purple) !important; }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
    </style>

    @stack('styles')
</head>
<body class="h-full antialiased">
    @yield('content')

    @stack('scripts')
    <script>
        // Configure SweetAlert globally
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });

        // Configure marked
        marked.setOptions({
            highlight: function(code, lang) {
                if (lang && hljs.getLanguage(lang)) {
                    return hljs.highlight(code, {language: lang}).value;
                }
                return hljs.highlightAuto(code).value;
            },
            breaks: true,
            gfm: true,
        });
    </script>
</body>
</html>
