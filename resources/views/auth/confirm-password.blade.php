<!DOCTYPE html>
<html>
<head>
    <title>Confirm Password</title>
    @include('auth.style')
</head>

<body>

<div class="card">

    <div class="title">
        Confirm Password
    </div>

    <div class="subtitle">
        Masukkan password untuk melanjutkan
    </div>

    <form method="POST">
        @csrf

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <button class="btn">
            Confirm
        </button>

    </form>

</div>

</body>
</html>