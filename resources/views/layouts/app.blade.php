<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Todo List')</title>
    <style>
        :root {
            --bg: #f1efeeb7;
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
        /* ── Toast System (top-right) ── */
        #toast-container {
            position: fixed;
            top: 1.25rem;
            right: 1.25rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
            pointer-events: none;
            max-width: 380px;
            width: calc(100vw - 2.5rem);
        }
        .toast {
            pointer-events: all;
            display: flex;
            flex-direction: column;
            background: #1f2430;
            color: #f9fafb;
            border-radius: 12px;
            font-size: 0.9rem;
            box-shadow: 0 8px 30px rgba(0,0,0,0.22);
            overflow: hidden;
            animation: toast-in 0.35s cubic-bezier(.34,1.56,.64,1) both;
        }
        .toast.hiding {
            animation: toast-out 0.3s ease forwards;
        }
        .toast.toast-error  { background: #7f1d1d; border-left: 4px solid #ef4444; }
        .toast.toast-success { background: #14532d; border-left: 4px solid #22c55e; }
        .toast.toast-info   { background: #1e3a5f; border-left: 4px solid var(--primary); }
        .toast-body {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 0.8rem 1rem;
        }
        .toast-icon { font-size: 1.1rem; flex-shrink: 0; margin-top: 1px; }
        .toast-msg { flex: 1; line-height: 1.4; }
        .toast-msg ul { margin: 0.3rem 0 0; padding-left: 1.1rem; }
        .toast-msg ul li { margin-bottom: 0.15rem; }
        .toast-close {
            background: transparent;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            font-size: 1.15rem;
            line-height: 1;
            padding: 0;
            flex-shrink: 0;
            transition: color 0.15s;
        }
        .toast-close:hover { color: #f9fafb; }
        .toast-actions { padding: 0 1rem 0.75rem; display: flex; gap: 0.5rem; }
        .toast-undo {
            background: rgba(255,255,255,0.15);
            color: #fff;
            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 6px;
            padding: 0.3rem 0.8rem;
            font-size: 0.82rem;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.15s;
        }
        .toast-undo:hover { background: rgba(255,255,255,0.25); }
        .toast-progress {
            height: 3px;
            background: rgba(255,255,255,0.1);
        }
        .toast-progress-bar {
            height: 100%;
            background: rgba(255,255,255,0.45);
            transition: width linear;
        }
        @keyframes toast-in {
            from { opacity: 0; transform: translateX(24px) scale(0.97); }
            to   { opacity: 1; transform: translateX(0) scale(1); }
        }
        @keyframes toast-out {
            from { opacity: 1; transform: translateX(0) scale(1); }
            to   { opacity: 0; transform: translateX(24px) scale(0.96); }
        }
        /* errors now shown as toasts */
        label { display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.35rem; color: var(--text); }
        input[type=text], input[type=email], input[type=password], input[type=date], textarea, select {
            width: 100%;
            padding: 0.6rem 0.75rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 0.95rem;
            font-family: inherit;
            background: var(--surface);
            color: var(--text);
        }
        input:focus, textarea:focus, select:focus {
            outline: 3px solid rgba(79, 70, 229, 0.16);
            border-color: var(--primary);
        }
        textarea { resize: vertical; min-height: 90px; }
        .field {
            margin-bottom: 1.1rem; 
            
        }
        .field-error {
            display: none;
            margin: 0.35rem 0 0;
            color: var(--danger);
            font-size: 0.82rem;
        }
        .field.has-error input {
            border-color: var(--danger);
        }
        .field.has-error .field-error {
            display: block;
        }
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
        .auth-card {
            max-width: 430px;
            margin: 2rem auto 0;
            padding: 2rem;
        }
        .auth-heading {
            margin: 0 0 0.45rem;
            text-align: center;
            font-size: 1.6rem;
        }
        .auth-subtitle {
            margin: 0 0 1.75rem;
            color: var(--muted);
            text-align: center;
            font-size: 0.92rem;
        }
        .auth-footer {
            margin-top: 1.25rem;
            color: var(--muted);
            text-align: center;
            font-size: 0.88rem;
        }
        .auth-footer a { color: var(--primary); font-weight: 600; }
        .auth-actions { margin-top: 1.5rem; }
        .auth-actions .btn { width: 100%; }
        @media (max-width: 480px) {
            .container { padding: 1.5rem 1rem 3rem; }
            .auth-card { margin-top: 1rem; padding: 1.35rem; }
            .navbar { flex-direction: column; gap: 1rem; padding: 1rem; }
            .navbar-menu { width: 100%; justify-content: center; }
        }
        
        /* Layout Improvements */
        .app-wrapper { display: flex; flex-direction: column; min-height: 100vh; }
        .main-content { flex: 1; }
        .navbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
            letter-spacing: -0.5px;
        }
        .navbar-brand svg { width: 2rem; height: 2rem; fill: currentColor; }
        .navbar-menu { display: flex; align-items: center; gap: 1.5rem; }
        .user-profile { display: flex; align-items: center; gap: 0.75rem; }
        .avatar {
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1rem;
            text-transform: uppercase;
        }
        .footer {
            background: var(--surface);
            border-top: 1px solid var(--border);
            padding: 1.5rem;
            text-align: center;
            color: var(--muted);
            margin-top: auto;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
    <div class="app-wrapper">
        <nav class="navbar">
            <a href="{{ Auth::check() ? route('todos.index') : route('login') }}" class="navbar-brand">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-9 14l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
                TODOlist
            </a>
            
            <div class="navbar-menu">
                @auth
                    <div class="user-profile">
                        <div class="avatar">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <span style="font-weight: 500; color: var(--text);">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" style="margin: 0; margin-left: 0.5rem;">
                            @csrf
                            <button type="submit" class="btn btn-secondary btn-sm">Log out</button>
                        </form>
                    </div>
                @endauth
            </div>
        </nav>

        <main class="main-content">
            <div class="container">

        {{-- ── Unified Toast Container ── --}}
        <div id="toast-container"></div>

        <script>
        (function() {
            const container = document.getElementById('toast-container');
            let counter = 0;

            window.showToast = function({ type = 'info', icon, message, html, duration = 5000, actions = '' }) {
                const id = 'toast-' + (++counter);
                const icons = { success: '✅', error: '❌', info: 'ℹ️', warning: '⚠️' };
                const toastIcon = icon || icons[type] || 'ℹ️';

                const el = document.createElement('div');
                el.className = 'toast toast-' + type;
                el.id = id;
                el.innerHTML = `
                    <div class="toast-body">
                        <span class="toast-icon">${toastIcon}</span>
                        <span class="toast-msg">${html || message}</span>
                        <button class="toast-close" onclick="dismissToast('${id}')" aria-label="Dismiss">&times;</button>
                    </div>
                    ${actions ? '<div class="toast-actions">' + actions + '</div>' : ''}
                    <div class="toast-progress"><div class="toast-progress-bar" id="bar-${id}" style="width:100%"></div></div>
                `;
                container.appendChild(el);

                // Animate progress bar
                const bar = document.getElementById('bar-' + id);
                bar.style.transitionDuration = duration + 'ms';
                bar.getBoundingClientRect();
                bar.style.width = '0%';

                const timer = setTimeout(() => dismissToast(id), duration);
                el._timer = timer;
            };

            window.dismissToast = function(id) {
                const el = document.getElementById(id);
                if (!el) return;
                clearTimeout(el._timer);
                el.classList.add('hiding');
                setTimeout(() => el && el.remove(), 320);
            };

            // ── Boot toasts from server session ──
            @if (session('status') && !session('deleted_todo_id'))
                showToast({ type: 'success', message: @json(session('status')), duration: 5000 });
            @endif

            @if (session('deleted_todo_id'))
                showToast({
                    type: 'info',
                    icon: '🗑️',
                    message: '<strong>{{ addslashes(session("deleted_todo_title")) }}</strong> deleted',
                    duration: 10000,
                    actions: `<form method="POST" action="{{ route('todos.restore', session('deleted_todo_id')) }}" style="display:inline">
                        @csrf
                        <input type="hidden" name="status" value="{{ request('status','all') }}">
                        <button type="submit" class="toast-undo">Undo</button>
                    </form>`
                });
            @endif

            @if ($errors->any())
                @php
                    $errorHtml = '<strong>Please fix the following:</strong><ul>';
                    foreach($errors->all() as $error) {
                        $errorHtml .= '<li>' . e($error) . '</li>';
                    }
                    $errorHtml .= '</ul>';
                @endphp
                showToast({
                    type: 'error',
                    duration: 8000,
                    html: {!! json_encode($errorHtml) !!}
                });
            @endif
        })();
        </script>

        <script>
        (function () {
            const initialize = () => {
            const passwordMessage = 'Password must be at least 6 characters and include uppercase, lowercase, a number, and a symbol.';
            const emailPattern = /^[a-zA-Z0-9.]+@[a-zA-Z0-9.]+\.[a-zA-Z]{2,}$/;

            document.querySelectorAll('form[data-auth-form]').forEach((form) => {
                const requiresStrongPassword = ['register', 'reset-password'].includes(form.dataset.authForm);
                const fields = Array.from(form.querySelectorAll('input[required]'));
                form.noValidate = true;

                fields.forEach((input) => {
                    const field = input.closest('.field');
                    const error = document.createElement('p');
                    error.className = 'field-error';
                    error.id = input.id + '-error';
                    error.setAttribute('role', 'alert');
                    field.appendChild(error);
                    input.setAttribute('aria-describedby', error.id);

                    input.addEventListener('input', () => validateField(input));
                    input.addEventListener('blur', () => validateField(input));
                });

                function getMessage(input) {
                    const value = input.value.trim();
                    if (!value) return input.labels[0].textContent.trim() + ' is required.';

                    if (input.type === 'email' && !emailPattern.test(value)) {
                        return 'Enter a valid email address.';
                    }

                    if (requiresStrongPassword && input.name === 'password') {
                        const validPassword = value.length >= 6
                            && /[a-z]/.test(value)
                            && /[A-Z]/.test(value)
                            && /\d/.test(value)
                            && /[^A-Za-z0-9]/.test(value);
                        if (!validPassword) return passwordMessage;
                    }

                    if (requiresStrongPassword && input.name === 'password_confirmation'
                        && value !== form.elements.password.value) {
                        return 'Passwords do not match.';
                    }

                    if (form.dataset.authForm === 'otp' && input.name === 'otp' && !/^\d{6}$/.test(value)) {
                        return 'Enter the 6-digit OTP.';
                    }

                    return '';
                }

                function validateField(input) {
                    const message = getMessage(input);
                    const field = input.closest('.field');
                    const error = field.querySelector('.field-error');
                    error.textContent = message;
                    field.classList.toggle('has-error', Boolean(message));
                    input.setAttribute('aria-invalid', Boolean(message));
                    return !message;
                }

                form.addEventListener('submit', (event) => {
                    const validationResults = fields.map((input) => validateField(input));
                    const valid = validationResults.every(Boolean);
                    if (!valid) {
                        event.preventDefault();
                    }
                });

                form.querySelectorAll('button[type="submit"]').forEach((button) => {
                    button.addEventListener('click', (event) => {
                        const validationResults = fields.map((input) => validateField(input));
                        if (validationResults.some((isValid) => !isValid)) {
                            event.preventDefault();
                        }
                    });
                });

                if (requiresStrongPassword) {
                    form.elements.password.addEventListener('input', () => validateField(form.elements.password_confirmation));
                }
            });
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initialize);
            } else {
                initialize();
            }
        })();
        </script>

        @yield('content')
            </div>
        </main>

        <footer class="footer">
            <p>&copy; {{ date('Y') }} TODOlist Application. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>
