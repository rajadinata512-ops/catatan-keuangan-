<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    @include('auth.style')
</head>

<body>

<div class="card">

    <div class="title">
        Forgot Password
    </div>

    <div class="subtitle">
        Masukkan email untuk reset password
    </div>

    <form method="POST">
        @csrf

        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>

        <button class="btn">
            Kirim Link Reset
        </button>

    </form>

</div>

</body>
</html>