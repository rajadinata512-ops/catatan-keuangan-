<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    @include('auth.style')
</head>

<body>

<div class="card">

    <div class="title">
        Catatan Keuangan
    </div>

    <div class="subtitle">
        Login ke akun kamu
    </div>

    @if ($errors->any())
        <div class="error">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="/login">
        @csrf

        <div class="input-group">
            <label>Email</label>

            <input type="email" name="email" required>
        </div>

        <div class="input-group">
            <label>Password</label>

            <input type="password" name="password" required>
        </div>

        <button class="btn">
            Login
        </button>

    </form>

    <div class="link">
        Belum punya akun?
        <a href="/register">Register</a>
    </div>

</div>

</body>
</html>