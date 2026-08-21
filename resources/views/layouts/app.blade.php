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

        @if (session('status'))
            <div class="status">{{ session('status') }}</div>
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
