<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:'Poppins',sans-serif;
        }

        body{
            min-height:100vh;
            background:
            radial-gradient(circle at top left, rgba(124,58,237,.25), transparent 30%),
            radial-gradient(circle at bottom right, rgba(168,85,247,.22), transparent 30%),
            #020617;

            display:flex;
            justify-content:center;
            align-items:center;
            padding:20px;
            color:white;
        }

        .box{
            width:100%;
            max-width:430px;
            background:rgba(255,255,255,.03);
            border:1px solid rgba(255,255,255,.08);
            backdrop-filter:blur(18px);
            border-radius:32px;
            padding:40px 30px;
        }

        h1{
            font-size:40px;
            text-align:center;
            margin-bottom:10px;
        }

        h1 span{
            background:linear-gradient(135deg,#8b5cf6,#6366f1);
            -webkit-background-clip:text;
            -webkit-text-fill-color:transparent;
        }

        p{
            text-align:center;
            color:#9ca3af;
            margin-bottom:30px;
        }

        input{
            width:100%;
            height:60px;
            border:none;
            outline:none;
            border-radius:18px;
            padding:0 20px;
            margin-bottom:18px;
            background:rgba(255,255,255,.04);
            border:1px solid rgba(255,255,255,.06);
            color:white;
            font-size:16px;
        }

        button{
            width:100%;
            height:60px;
            border:none;
            border-radius:18px;
            background:linear-gradient(135deg,#6366f1,#a855f7);
            color:white;
            font-size:18px;
            font-weight:600;
            cursor:pointer;
        }

    </style>

</head>
<body>

<div class="box">

    <h1>Lupa <span>Password</span></h1>

    <p>Masukkan email untuk reset password</p>

    @if (session('status'))
    <div style="background:rgba(139,92,246,.12);border:1px solid rgba(139,92,246,.3);color:#c4b5fd;padding:12px 16px;border-radius:14px;margin-bottom:18px;font-size:14px;text-align:center;">
        ✅ {{ session('status') }}
    </div>
    @endif

    @if ($errors->any())
    <div style="background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.3);color:#f87171;padding:12px 16px;border-radius:14px;margin-bottom:18px;font-size:14px;">
        {{ $errors->first() }}
    </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">

        @csrf

        <input type="email" name="email" placeholder="Email" required>

        <button type="submit">Kirim Link Reset</button>

    </form>

</div>

</body>
</html>