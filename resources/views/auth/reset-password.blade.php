<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    @include('auth.style')
</head>

<body>

<div class="card">

    <div class="title">
        Reset Password
    </div>

    <div class="subtitle">
        Buat password baru
    </div>

    <form method="POST">
        @csrf

        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>

        <div class="input-group">
            <label>Password Baru</label>
            <input type="password" name="password" required>
        </div>

        <div class="input-group">
            <label>Konfirmasi Password</label>
            <input type="password" name="password_confirmation" required>
        </div>

        <button class="btn">
            Reset Password
        </button>

    </form>

</div>

</body>
</html>