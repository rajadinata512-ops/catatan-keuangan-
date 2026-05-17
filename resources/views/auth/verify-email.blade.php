<!DOCTYPE html>
<html>
<head>
    <title>Verify Email</title>
    @include('auth.style')
</head>

<body>

<div class="card">

    <div class="title">
        Verifikasi Email
    </div>

    <div class="subtitle">
        Cek email kamu lalu klik link verifikasi
    </div>

    <form method="POST">
        @csrf

        <button class="btn">
            Kirim Ulang Email
        </button>

    </form>

</div>

</body>
</html>