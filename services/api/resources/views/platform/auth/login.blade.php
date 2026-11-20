
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Platform Admin Login - Bless POS</title>

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
            padding: 20px;
            font-family: Arial, sans-serif;
            background: #f1f5f9;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 36px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.08);
        }

        .brand {
            margin-bottom: 30px;
            text-align: center;
        }

        .brand h1 {
            color: #2563eb;
            font-size: 30px;
            font-weight: 800;
        }

        .brand p {
            margin-top: 8px;
            color: #64748b;
            font-size: 14px;
        }

        h2 {
            margin-bottom: 8px;
            color: #0f172a;
            font-size: 22px;
        }

        .description {
            margin-bottom: 25px;
            color: #64748b;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #334155;
            font-size: 14px;
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
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 22px;
            color: #475569;
            font-size: 14px;
        }

        .remember label {
            margin: 0;
            font-weight: normal;
        }

        .error {
            margin-bottom: 18px;
            padding: 12px;
            border-radius: 8px;
            background: #fef2f2;
            color: #b91c1c;
            font-size: 13px;
        }

        button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 8px;
            background: #2563eb;
            color: white;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
        }

        button:hover {
            background: #1d4ed8;
        }

        .footer {
            margin-top: 25px;
            color: #94a3b8;
            font-size: 12px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="login-card">

        <div class="brand">
            <h1>Bless POS</h1>
            <p>Platform Administration</p>
        </div>

        <h2>Platform Admin Login</h2>
        <p class="description">
            Masuk untuk mengelola platform Bless POS.
        </p>

        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('platform.login.store') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Masukkan email"
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

            <div class="remember">
                <input type="checkbox" id="remember" name="remember" value="1">
                <label for="remember">Ingat saya</label>
            </div>

            <button type="submit">Masuk ke Platform</button>
        </form>

        <div class="footer">
            &copy; {{ date('Y') }} Bless POS. All rights reserved.
        </div>

    </div>
</body>
</html>
