<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Todo List')</title>
    <style>
        :root {
            --bg: #f4f5f7;
            --surface: #ffffff;
            --border: #e2e4e9;
            --text: #1f2430;
            --muted: #6b7280;
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --danger: #dc2626;
            --success: #16a34a;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: var(--bg);
            color: var(--text);
        }
        .container {
            max-width: 720px;
            margin: 0 auto;
            padding: 2.5rem 1.5rem 4rem;
        }
        h1 { font-size: 1.75rem; margin: 0 0 1.5rem; }
        h1 a { color: inherit; text-decoration: none; }
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 1.5rem;
        }
        .status {
            background: #ecfdf3;
            border: 1px solid #bbf7d0;
            color: #166534;
            padding: 0.65rem 1rem;
            border-radius: 8px;
            margin-bottom: 1.25rem;
            font-size: 0.9rem;
        }
        /* ── Undo Toast ── */
        .toast-wrap {
            position: fixed;
            bottom: 1.75rem;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0;
            pointer-events: none;
        }
        .toast {
            pointer-events: all;
            display: flex;
            align-items: center;
            gap: 0.85rem;
            background: #1f2430;
            color: #f9fafb;
            padding: 0.75rem 1.1rem;
            border-radius: 12px;
            font-size: 0.9rem;
            box-shadow: 0 8px 30px rgba(0,0,0,0.22);
            min-width: 280px;
            max-width: 420px;
            animation: toast-in 0.3s cubic-bezier(.34,1.56,.64,1) both;
        }
        .toast.hiding {
            animation: toast-out 0.3s ease forwards;
        }
        .toast-msg { flex: 1; }
        .toast-undo {
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 7px;
            padding: 0.35rem 0.85rem;
            font-size: 0.82rem;
            font-weight: 700;
            cursor: pointer;
            white-space: nowrap;
            transition: background 0.15s;
        }
        .toast-undo:hover { background: var(--primary-hover); }
        .toast-close {
            background: transparent;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            font-size: 1.1rem;
            line-height: 1;
            padding: 0 0.1rem;
            transition: color 0.15s;
        }
        .toast-close:hover { color: #f9fafb; }
        .toast-progress {
            height: 3px;
            width: 100%;
            border-radius: 0 0 12px 12px;
            background: rgba(255,255,255,0.12);
            overflow: hidden;
        }
        .toast-progress-bar {
            height: 100%;
            background: var(--primary);
            border-radius: 0 0 12px 12px;
            transition: width linear;
        }
        @keyframes toast-in {
            from { opacity: 0; transform: translateY(16px) scale(0.95); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes toast-out {
            from { opacity: 1; transform: translateY(0) scale(1); }
            to   { opacity: 0; transform: translateY(8px) scale(0.96); }
        }
        .errors {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 1.25rem;
            font-size: 0.9rem;
        }
        .errors ul { margin: 0; padding-left: 1.1rem; }
        label { display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.35rem; color: var(--text); }
        input[type=text], input[type=date], textarea, select {
            width: 100%;
            padding: 0.6rem 0.75rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 0.95rem;
            font-family: inherit;
            background: var(--surface);
            color: var(--text);
        }
        textarea { resize: vertical; min-height: 90px; }
        .field { margin-bottom: 1.1rem; }
        .actions { display: flex; gap: 0.6rem; margin-top: 1.5rem; }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.55rem 1.1rem;
            border-radius: 8px;
            border: 1px solid transparent;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-primary { background: var(--primary); color: #fff; }
        .btn-primary:hover { background: var(--primary-hover); }
        .btn-secondary { background: var(--surface); color: var(--text); border-color: var(--border); }
        .btn-secondary:hover { background: #f3f4f6; }
        .btn-danger { background: #fff; color: var(--danger); border-color: #fecaca; }
        .btn-danger:hover { background: #fef2f2; }
        .btn-sm { padding: 0.35rem 0.7rem; font-size: 0.8rem; }
        .topbar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; }
        .tabs { display: flex; gap: 0.4rem; }
        .tab {
            padding: 0.4rem 0.85rem;
            border-radius: 999px;
            font-size: 0.85rem;
            text-decoration: none;
            color: var(--muted);
            border: 1px solid var(--border);
        }
        .tab.active { background: var(--primary); color: #fff; border-color: var(--primary); }
        .todo-list { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 0.7rem; }
        .todo-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 0.9rem 1rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--surface);
        }
        .todo-item.completed { opacity: 0.6; }
        .todo-item.completed .todo-title { text-decoration: line-through; }
        .todo-main { flex: 1; min-width: 0; }
        .todo-title { font-weight: 600; margin: 0 0 0.2rem; word-break: break-word; }
        .todo-desc { color: var(--muted); font-size: 0.88rem; margin: 0 0 0.4rem; word-break: break-word; }
        .todo-meta { display: flex; gap: 0.5rem; flex-wrap: wrap; align-items: center; font-size: 0.78rem; }
        .badge { padding: 0.15rem 0.55rem; border-radius: 999px; font-weight: 600; }
        .badge-high { background: #fee2e2; color: #991b1b; }
        .badge-medium { background: #fef3c7; color: #92400e; }
        .badge-low { background: #e0f2fe; color: #075985; }
        .due { color: var(--muted); }
        .todo-actions { display: flex; gap: 0.4rem; align-items: center; }
        .checkbox-form { padding-top: 0.15rem; }
        .checkbox-form input[type=checkbox] { width: 1.15rem; height: 1.15rem; cursor: pointer; }
        .empty { text-align: center; color: var(--muted); padding: 2.5rem 1rem; }
        form.inline { display: inline; }
    </style>
</head>
<body>
    <div class="container">
        <h1><a href="{{ route('todos.index') }}">Todo List</a></h1>

        @if (session('status') && !session('deleted_todo_id'))
            <div class="status">{{ session('status') }}</div>
        @endif

        @if (session('deleted_todo_id'))
        <div class="toast-wrap" id="toastWrap">
            <div class="toast" id="undoToast">
                <span class="toast-msg">🗑️ <strong>{{ session('deleted_todo_title') }}</strong> deleted</span>
                <form method="POST" action="{{ route('todos.restore', session('deleted_todo_id')) }}" style="display:inline">
                    @csrf
                    <input type="hidden" name="status" value="{{ request('status','all') }}">
                    <button type="submit" class="toast-undo">Undo</button>
                </form>
                <button class="toast-close" onclick="dismissToast()" aria-label="Dismiss">&times;</button>
            </div>
            <div class="toast-progress">
                <div class="toast-progress-bar" id="toastBar" style="width:100%"></div>
            </div>
        </div>
        <script>
            (function () {
                const DURATION = 6000;
                const bar  = document.getElementById('toastBar');
                const wrap = document.getElementById('toastWrap');
                let timer;

                // Animate progress bar
                bar.style.transitionDuration = DURATION + 'ms';
                // Force reflow so transition fires
                bar.getBoundingClientRect();
                bar.style.width = '0%';

                timer = setTimeout(dismissToast, DURATION);

                window.dismissToast = function () {
                    clearTimeout(timer);
                    const toast = document.getElementById('undoToast');
                    toast.classList.add('hiding');
                    setTimeout(() => wrap && wrap.remove(), 300);
                };
            })();
        </script>
        @endif

        @if ($errors->any())
            <div class="errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>
