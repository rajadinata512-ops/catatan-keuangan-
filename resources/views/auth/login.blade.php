<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

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
            box-shadow:0 0 40px rgba(139,92,246,.15);
        }

        h1{
            font-size:42px;
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

        input:focus{
            border:1px solid #8b5cf6;
            box-shadow:0 0 25px rgba(139,92,246,.25);
        }

        input::placeholder{
            color:#9ca3af;
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

        .bottom{
            margin-top:20px;
            text-align:center;
            color:#9ca3af;
        }

        .bottom a{
            color:#8b5cf6;
            text-decoration:none;
        }

    </style>
</head>
<body>

<div class="box">

    <h1>Catatan <span>Keuangan</span></h1>

    <p>Login ke akun kamu</p>

    <form method="POST" action="{{ route('login') }}">

        @csrf

        <input type="email" name="email" placeholder="Email" required>

        <input type="password" name="password" placeholder="Password" required>

        <button type="submit">Login</button>

    </form>

    <div class="bottom">
        Belum punya akun?
        <a href="{{ route('register') }}">Register</a>
    </div>

</div>

</body>
</html>
```
