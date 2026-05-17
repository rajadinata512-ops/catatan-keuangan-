<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    @include('auth.style')
</head>

<body>

<div class="card">

    <div class="title">
        Register
    </div>

    <div class="subtitle">
        Buat akun baru
    </div>

    <form method="POST" action="/register">
        @csrf

        <div class="input-group">
            <label>Nama</label>
            <input type="text" name="name" required>
        </div>

        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <div class="input-group">
            <label>Konfirmasi Password</label>
            <input type="password" name="password_confirmation" required>
        </div>

        <button class="btn">
            Register
        </button>

    </form>

    <div class="link">
        Sudah punya akun?
        <a href="/login">Login</a>
    </div>

</div>

</body>
</html>