<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'TableBook — Restaurant Reservations' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        :root {
            --gold: #c9a84c;
            --gold-light: #dfc778;
            --dark: #1a1a1a;
            --darker: #0f0f0f;
            --warm: #2a2218;
        }
        body { font-family: 'Georgia', 'Times New Roman', serif; background: var(--darker); color: #e8e0d4; }
        .font-display { font-family: 'Georgia', serif; }
        .font-sans { font-family: system-ui, -apple-system, sans-serif; }
        .text-gold { color: var(--gold); }
        .bg-gold { background-color: var(--gold); }
        .border-gold { border-color: var(--gold); }
        .bg-warm { background-color: var(--warm); }
        .btn-gold {
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            color: var(--darker);
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 0.25rem;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 0.8rem;
            font-family: system-ui, sans-serif;
        }
        .btn-gold:hover { transform: translateY(-1px); box-shadow: 0 4px 20px rgba(201, 168, 76, 0.3); }
        .card {
            background: var(--dark);
            border: 1px solid rgba(201, 168, 76, 0.15);
            border-radius: 0.5rem;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card:hover { border-color: rgba(201, 168, 76, 0.4); transform: translateY(-2px); box-shadow: 0 8px 30px rgba(0,0,0,0.4); }
        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
            opacity: 0.3;
        }
        .star { color: var(--gold); }
        .slot-btn {
            border: 1px solid rgba(201, 168, 76, 0.3);
            background: transparent;
            color: var(--gold);
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            font-family: system-ui, sans-serif;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .slot-btn:hover, .slot-btn.active { background: var(--gold); color: var(--darker); }
        input, select, textarea {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(201, 168, 76, 0.2);
            color: #e8e0d4;
            padding: 0.65rem 1rem;
            border-radius: 0.25rem;
            font-family: system-ui, sans-serif;
            width: 100%;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--gold);
            box-shadow: 0 0 0 2px rgba(201, 168, 76, 0.15);
        }
        label { font-family: system-ui, sans-serif; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; color: rgba(232,224,212,0.6); margin-bottom: 0.35rem; display: block; }
    </style>
</head>
<body class="min-h-screen" x-data>
    <nav class="border-b border-white/5 backdrop-blur-sm sticky top-0 z-50" style="background: rgba(15,15,15,0.95);">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-sm bg-gold flex items-center justify-center">
                    <span class="text-sm font-bold" style="color: var(--darker);">T</span>
                </div>
                <span class="font-display text-lg tracking-wide text-gold">TableBook</span>
            </a>
            <div class="flex items-center gap-8 font-sans text-sm">
                <a href="{{ route('tables.index') }}" class="text-white/60 hover:text-gold transition-colors">Tables</a>
                <a href="{{ route('reviews.index') }}" class="text-white/60 hover:text-gold transition-colors">Reviews</a>
                <a href="/admin" class="text-white/60 hover:text-gold transition-colors">Admin</a>
            </div>
        </div>
    </nav>

    <main>
        {{ $slot }}
    </main>

    <footer class="border-t border-white/5 mt-20">
        <div class="max-w-6xl mx-auto px-6 py-10 flex items-center justify-between font-sans text-sm text-white/30">
            <span>&copy; {{ date('Y') }} TableBook — Restaurant Reservation System</span>
            <span>Demo by <span class="text-gold/60">sbarron.com</span></span>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
