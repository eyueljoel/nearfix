<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In · ServiLoc</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
            display: flex;
        }

        .auth-left {
            width: 420px;
            flex-shrink: 0;
            background: linear-gradient(160deg, #0f172a 0%, #1e3a5f 55%, #0ea5e9 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 48px 40px;
            color: white;
            position: sticky;
            top: 0;
            height: 100vh;
        }

        .brand { font-size: 36px; font-weight: 800; margin-bottom: 8px; }
        .brand span { color: #0ea5e9; }

        .brand-sub {
            font-size: 15px;
            opacity: 0.7;
            text-align: center;
            line-height: 1.5;
        }

        .feature-list { margin-top: 40px; width: 100%; }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 0;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .feature-item:last-child { border-bottom: none; }

        .feature-icon {
            font-size: 22px;
            width: 44px;
            height: 44px;
            background: rgba(255,255,255,0.08);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .feature-text .ft-title { font-weight: 600; font-size: 14px; }
        .feature-text .ft-sub   { font-size: 12px; opacity: 0.6; margin-top: 2px; }

        .auth-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 32px;
        }

        .form-card { width: 100%; max-width: 420px; }

        .form-title {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 6px;
        }

        .form-subtitle {
            color: #8895aa;
            font-size: 15px;
            margin-bottom: 32px;
        }

        .field { margin-bottom: 20px; }

        .field label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #555;
            margin-bottom: 7px;
        }

        .field input {
            width: 100%;
            padding: 12px 14px;
            border: 2px solid #e8ecf1;
            border-radius: 10px;
            font-size: 14px;
            font-family: inherit;
            color: #1a1a2e;
            background: #fafbfc;
            transition: all 0.2s;
            outline: none;
        }

        .field input:focus {
            border-color: #0ea5e9;
            background: white;
            box-shadow: 0 0 0 3px rgba(14,165,233,0.12);
        }

        .field-error {
            font-size: 12px;
            color: #0ea5e9;
            margin-top: 5px;
        }

        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #555;
            cursor: pointer;
        }

        .remember-label input { accent-color: #0ea5e9; }

        .forgot-link {
            font-size: 13px;
            color: #0ea5e9;
            text-decoration: none;
            font-weight: 600;
        }

        .forgot-link:hover { text-decoration: underline; }

        .btn-login {
            width: 100%;
            padding: 15px;
            background: #0ea5e9;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            font-family: inherit;
        }

        .btn-login:hover {
            background: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(233,69,96,0.35);
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #8895aa;
        }

        .register-link a {
            color: #0ea5e9;
            font-weight: 600;
            text-decoration: none;
        }

        .alert-error {
            background: #fff5f5;
            border: 1px solid #fed7d7;
            border-left: 4px solid #0ea5e9;
            border-radius: 10px;
            padding: 14px 16px;
            margin-bottom: 24px;
            font-size: 14px;
            color: #c53030;
        }

        .alert-status {
            background: #f0fff4;
            border: 1px solid #c6f6d5;
            border-left: 4px solid #38a169;
            border-radius: 10px;
            padding: 14px 16px;
            margin-bottom: 24px;
            font-size: 14px;
            color: #276749;
        }

        @media (max-width: 900px) { .auth-left { display: none; } }
        @media (max-width: 480px) { .auth-right { padding: 24px 16px; } }
    </style>
</head>
<body>

<div class="auth-left">
    <div class="brand">🛠️ Servi<span>Loc</span></div>
    <div class="brand-sub">Welcome back!<br>Sign in to manage your services.</div>

    <div class="feature-list">
        <div class="feature-item">
            <div class="feature-icon">📋</div>
            <div class="feature-text">
                <div class="ft-title">Manage Requests</div>
                <div class="ft-sub">Post and track service requests</div>
            </div>
        </div>
        <div class="feature-item">
            <div class="feature-icon">💬</div>
            <div class="feature-text">
                <div class="ft-title">Message Providers</div>
                <div class="ft-sub">Chat directly with service providers</div>
            </div>
        </div>
        <div class="feature-item">
            <div class="feature-icon">💳</div>
            <div class="feature-text">
                <div class="ft-title">Secure Payments</div>
                <div class="ft-sub">Pay safely in ETB</div>
            </div>
        </div>
        <div class="feature-item">
            <div class="feature-icon">🔔</div>
            <div class="feature-text">
                <div class="ft-title">Real-time Notifications</div>
                <div class="ft-sub">Stay updated on every activity</div>
            </div>
        </div>
    </div>
</div>

<div class="auth-right">
    <div class="form-card">
        <h1 class="form-title">Welcome back 👋</h1>
        <p class="form-subtitle">Sign in to your ServiLoc account</p>

        @if(session('status'))
            <div class="alert-status">{{ session('status') }}</div>
        @endif

        @if($errors->any())
            <div class="alert-error">
                @foreach($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="field">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                       placeholder="you@example.com" required autofocus autocomplete="username">
                @error('email')
                    <div class="field-error">⚠ {{ $message }}</div>
                @enderror
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password"
                       placeholder="Your password" required autocomplete="current-password">
                @error('password')
                    <div class="field-error">⚠ {{ $message }}</div>
                @enderror
            </div>

            <div class="form-footer">
                <label class="remember-label">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    Remember me
                </label>
                @if(Route::has('password.request'))
                    <a class="forgot-link" href="{{ route('password.request') }}">Forgot password?</a>
                @endif
            </div>

            <button type="submit" class="btn-login">Sign In →</button>
        </form>

        <div class="register-link">
            Don't have an account? <a href="{{ route('register') }}">Create one free</a>
        </div>
    </div>
</div>

</body>
</html>
