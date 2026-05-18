<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

</head>
<body>

<div class="box">

    <h1>Reset <span>Password</span></h1>

    <p>Buat password baru</p>

    <form method="POST" action="{{ route('password.store') }}">

        @csrf

        <input type="hidden" name="token" value="{{ request()->route('token') }}">

        <input type="email" name="email" placeholder="Email" required>

        <input type="password" name="password" placeholder="Password Baru" required>

        <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required>

        <button type="submit">Reset Password</button>

    </form>

</div>

</body>
</html>
