<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Platform Admin Login | Bless POS</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            color: #172033;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 36px;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 15px 40px rgba(15, 23, 42, .08);
        }

        .brand {
            margin-bottom: 30px;
            text-align: center;
        }

        .brand h1 {
            font-size: 28px;
            font-weight: 800;
            color: #2563eb;
            letter-spacing: -1px;
        }

        .brand p {
            margin-top: 8px;
            font-size: 13px;
            color: #64748b;
        }

        .heading {
            margin-bottom: 24px;
        }

        .heading h2 {
            font-size: 21px;
            margin-bottom: 7px;
        }

        .heading p {
            font-size: 14px;
            color: #64748b;
        }

        .form-group {
            margin-bottom: 19px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 600;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 13px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            outline: none;
            font-size: 14px;
        }

        input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .1);
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 22px;
            font-size: 13px;
            color: #475569;
        }

        .remember input {
            width: 15px;
            height: 15px;
        }

        .error {
            margin-bottom: 18px;
            padding: 12px;
            border-radius: 8px;
            background: #fef2f2;
            color: #b91c1c;
            font-size: 13px;
        }

        .login-button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 8px;
            background: #2563eb;
            color: white;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
        }

        .login-button:hover {
            background: #1d4ed8;
        }

        .footer {
            margin-top: 26px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 26px 22px;
            }
        }
    </style>
</head>
<body>
    <main class="login-card">
        <div class="brand">
            <h1>Bless POS</h1>
            <p>Platform Administration</p>
        </div>

        <div class="heading">
            <h2>Selamat Datang</h2>
            <p>Masuk ke akun Platform Administrator.</p>
        </div>

        @if ($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('platform.login.store') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email Administrator</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="admin@example.com"
                    autocomplete="username"
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Masukkan password"
                    autocomplete="current-password"
                    required
                >
            </div>

            <label class="remember">
                <input type="checkbox" name="remember" value="1">
                Ingat saya
            </label>

            <button type="submit" class="login-button">
                Masuk ke Platform Admin
            </button>
        </form>

        <div class="footer">
            &copy; {{ date('Y') }} Bless POS. All rights reserved.
        </div>
    </main>
</body>
</html>
