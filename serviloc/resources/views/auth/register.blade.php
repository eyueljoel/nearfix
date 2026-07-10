<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account · ServiLoc</title>
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

        /* ── Left panel ── */
        .auth-left {
            width: 420px;
            flex-shrink: 0;
            background: linear-gradient(160deg, #0f172a 0%, #1e3a5f 60%, #0ea5e9 100%);
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

        .brand {
            font-size: 36px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .brand span { color: #0ea5e9; }

        .brand-sub {
            font-size: 15px;
            opacity: 0.7;
            text-align: center;
            line-height: 1.5;
        }

        .feature-list {
            margin-top: 40px;
            width: 100%;
        }

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

        /* ── Right panel ── */
        .auth-right {
            flex: 1;
            overflow-y: auto;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 48px 32px;
        }

        .form-card {
            width: 100%;
            max-width: 560px;
        }

        .form-title {
            font-size: 28px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 6px;
        }

        .form-subtitle {
            color: #8895aa;
            font-size: 15px;
            margin-bottom: 32px;
        }

        /* Role selector */
        .role-selector {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 28px;
        }

        .role-option { position: relative; }

        .role-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
        }

        .role-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            padding: 18px 12px;
            border: 2px solid #e8ecf1;
            border-radius: 14px;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
            background: white;
        }

        .role-label:hover {
            border-color: #0ea5e9;
            background: #fff8f9;
        }

        .role-option input[type="radio"]:checked + .role-label {
            border-color: #0ea5e9;
            background: #fff0f3;
        }

        .role-icon { font-size: 28px; }
        .role-name { font-weight: 700; font-size: 14px; color: #0f172a; }
        .role-desc { font-size: 11px; color: #8895aa; line-height: 1.3; }

        /* Section divider */
        .section-label {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #8895aa;
            margin-bottom: 14px;
            margin-top: 24px;
        }

        /* Fields */
        .field-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .field {
            margin-bottom: 18px;
        }

        .field label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #555;
            margin-bottom: 7px;
        }

        .field input,
        .field select {
            width: 100%;
            padding: 11px 14px;
            border: 2px solid #e8ecf1;
            border-radius: 10px;
            font-size: 14px;
            font-family: inherit;
            color: #0f172a;
            background: #fafbfc;
            transition: all 0.2s;
            outline: none;
        }

        .field input:focus,
        .field select:focus {
            border-color: #0ea5e9;
            background: white;
            box-shadow: 0 0 0 4px rgba(233,69,96,0.06);
        }

        .field input.error,
        .field select.error {
            border-color: #0ea5e9;
            background: #fff5f5;
        }

        .field-error {
            font-size: 12px;
            color: #0ea5e9;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Password strength */
        .password-strength {
            margin-top: 6px;
            height: 4px;
            background: #e8ecf1;
            border-radius: 4px;
            overflow: hidden;
        }

        .strength-bar {
            height: 100%;
            border-radius: 4px;
            transition: all 0.3s;
            width: 0%;
        }

        .strength-text {
            font-size: 11px;
            margin-top: 4px;
            font-weight: 500;
        }

        /* Terms */
        .terms-check {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 24px;
            margin-top: 4px;
        }

        .terms-check input[type="checkbox"] {
            margin-top: 2px;
            accent-color: #0ea5e9;
            flex-shrink: 0;
        }

        .terms-check label {
            font-size: 13px;
            color: #555;
            line-height: 1.5;
        }

        .terms-check a { color: #0ea5e9; text-decoration: none; font-weight: 600; }

        /* Submit */
        .btn-register {
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

        .btn-register:hover {
            background: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(233,69,96,0.35);
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #8895aa;
        }

        .login-link a {
            color: #0ea5e9;
            font-weight: 600;
            text-decoration: none;
        }

        /* Alert */
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

        /* Responsive */
        @media (max-width: 900px) {
            .auth-left { display: none; }
        }

        @media (max-width: 540px) {
            .field-row { grid-template-columns: 1fr; }
            .role-selector { grid-template-columns: 1fr; }
            .auth-right { padding: 24px 16px; }
        }
    </style>
</head>
<body>

<div class="auth-left">
    <div class="brand">🛠️ Servi<span>Loc</span></div>
    <div class="brand-sub">Your local service marketplace.<br>Find or offer services near you.</div>

    <div class="feature-list">
        <div class="feature-item">
            <div class="feature-icon">🔍</div>
            <div class="feature-text">
                <div class="ft-title">Post Service Requests</div>
                <div class="ft-sub">Describe what you need and get offers</div>
            </div>
        </div>
        <div class="feature-item">
            <div class="feature-icon">🤝</div>
            <div class="feature-text">
                <div class="ft-title">Get Matched with Providers</div>
                <div class="ft-sub">Compare offers and pick the best one</div>
            </div>
        </div>
        <div class="feature-item">
            <div class="feature-icon">💳</div>
            <div class="feature-text">
                <div class="ft-title">Secure Payments</div>
                <div class="ft-sub">Pay safely — released only on completion</div>
            </div>
        </div>
        <div class="feature-item">
            <div class="feature-icon">⭐</div>
            <div class="feature-text">
                <div class="ft-title">Verified Reviews</div>
                <div class="ft-sub">Rate providers after every service</div>
            </div>
        </div>
    </div>
</div>

<div class="auth-right">
    <div class="form-card">
        <h1 class="form-title">Create your account</h1>
        <p class="form-subtitle">Join ServiLoc and get started in minutes</p>

        @if($errors->any())
            <div class="alert-error">
                <strong>Please fix the following:</strong>
                <ul style="margin-top: 6px; padding-left: 16px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- ── Role Selection ── --}}
            <div class="section-label">I want to join as</div>

            <div class="role-selector">
                <div class="role-option">
                    <input type="radio" id="role_customer" name="role" value="customer"
                        {{ old('role', 'customer') === 'customer' ? 'checked' : '' }}>
                    <label class="role-label" for="role_customer">
                        <span class="role-icon">👤</span>
                        <span class="role-name">Customer</span>
                        <span class="role-desc">I need services done</span>
                    </label>
                </div>
                <div class="role-option">
                    <input type="radio" id="role_provider" name="role" value="provider"
                        {{ old('role') === 'provider' ? 'checked' : '' }}>
                    <label class="role-label" for="role_provider">
                        <span class="role-icon">🔧</span>
                        <span class="role-name">Provider</span>
                        <span class="role-desc">I offer services</span>
                    </label>
                </div>
            </div>
            @error('role')
                <div class="field-error" style="margin-top:-16px; margin-bottom:16px;">⚠ {{ $message }}</div>
            @enderror

            {{-- ── Basic Info ── --}}
            <div class="section-label">Basic Information</div>

            <div class="field-row">
                <div class="field">
                    <label for="name">Full Name <span style="color:#0ea5e9;">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                           placeholder="John Doe" required autofocus
                           class="{{ $errors->has('name') ? 'error' : '' }}">
                    @error('name')
                        <div class="field-error">⚠ {{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                           placeholder="+251 9XX XXX XXX"
                           class="{{ $errors->has('phone') ? 'error' : '' }}">
                    @error('phone')
                        <div class="field-error">⚠ {{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="field">
                <label for="email">Email Address <span style="color:#0ea5e9;">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                       placeholder="you@example.com" required autocomplete="username"
                       class="{{ $errors->has('email') ? 'error' : '' }}">
                @error('email')
                    <div class="field-error">⚠ {{ $message }}</div>
                @enderror
            </div>

            <div class="field">
                <label for="address">Address / Location</label>
                <input type="text" id="address" name="address" value="{{ old('address') }}"
                       placeholder="e.g. Bole, Addis Ababa"
                       class="{{ $errors->has('address') ? 'error' : '' }}">
                @error('address')
                    <div class="field-error">⚠ {{ $message }}</div>
                @enderror
            </div>

            {{-- ── Provider-only: Bio ── --}}
            <div id="provider-fields" style="{{ old('role') === 'provider' ? '' : 'display:none;' }}">
                <div class="field">
                    <label for="bio">About You / Skills</label>
                    <input type="text" id="bio" name="bio" value="{{ old('bio') }}"
                           placeholder="e.g. Experienced electrician with 5 years of expertise">
                    @error('bio')
                        <div class="field-error">⚠ {{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- ── Security ── --}}
            <div class="section-label" style="margin-top:24px;">Security</div>

            <div class="field-row">
                <div class="field">
                    <label for="password">Password <span style="color:#0ea5e9;">*</span></label>
                    <input type="password" id="password" name="password"
                           placeholder="Min. 8 characters" required autocomplete="new-password"
                           oninput="checkStrength(this.value)"
                           class="{{ $errors->has('password') ? 'error' : '' }}">
                    <div class="password-strength">
                        <div class="strength-bar" id="strengthBar"></div>
                    </div>
                    <div class="strength-text" id="strengthText" style="color:#8895aa;"></div>
                    @error('password')
                        <div class="field-error">⚠ {{ $message }}</div>
                    @enderror
                </div>
                <div class="field">
                    <label for="password_confirmation">Confirm Password <span style="color:#0ea5e9;">*</span></label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           placeholder="Repeat your password" required autocomplete="new-password">
                </div>
            </div>

            {{-- ── Terms ── --}}
            <div class="terms-check">
                <input type="checkbox" id="terms" name="terms" required
                       {{ old('terms') ? 'checked' : '' }}>
                <label for="terms">
                    I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.
                    I confirm that all information provided is accurate.
                </label>
            </div>

            <button type="submit" class="btn-register">
                Create Account →
            </button>
        </form>

        <div class="login-link">
            Already have an account? <a href="{{ route('login') }}">Sign in here</a>
        </div>
    </div>
</div>

<script>
    // Show/hide bio field based on role
    document.querySelectorAll('input[name="role"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const providerFields = document.getElementById('provider-fields');
            providerFields.style.display = this.value === 'provider' ? '' : 'none';
        });
    });

    // Password strength checker
    function checkStrength(val) {
        const bar  = document.getElementById('strengthBar');
        const text = document.getElementById('strengthText');
        let score  = 0;

        if (val.length >= 8)  score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        const levels = [
            { pct: '0%',   color: '',        label: '' },
            { pct: '25%',  color: '#0ea5e9', label: 'Weak' },
            { pct: '50%',  color: '#ff9800', label: 'Fair' },
            { pct: '75%',  color: '#2196f3', label: 'Good' },
            { pct: '100%', color: '#4caf50', label: 'Strong 💪' },
        ];

        const level = levels[score] || levels[0];
        bar.style.width  = level.pct;
        bar.style.background = level.color;
        text.textContent = level.label;
        text.style.color = level.color;
    }
</script>
</body>
</html>
