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

        .input-wrap{
            position:relative;
            margin-bottom:18px;
        }

        input{
            width:100%;
            height:60px;
            border:none;
            outline:none;
            border-radius:18px;
            padding:0 20px;
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

        input.has-toggle{
            padding-right:55px;
        }

        .toggle-pw{
            position:absolute;
            right:18px;
            top:50%;
            transform:translateY(-50%);
            background:none;
            border:none;
            cursor:pointer;
            color:#9ca3af;
            width:auto;
            height:auto;
            padding:0;
            font-size:20px;
            display:flex;
            align-items:center;
        }

        .toggle-pw:hover{
            color:white;
        }

        .error-box{
            background:rgba(239,68,68,.12);
            border:1px solid rgba(239,68,68,.3);
            color:#f87171;
            padding:12px 16px;
            border-radius:14px;
            margin-bottom:18px;
            font-size:14px;
        }

        .forgot{
            display:block;
            text-align:right;
            color:#8b5cf6;
            font-size:13px;
            text-decoration:none;
            margin-bottom:18px;
            margin-top:-10px;
        }

        .forgot:hover{
            text-decoration:underline;
        }

        button[type="submit"]{
            width:100%;
            height:60px;
            border:none;
            border-radius:18px;
            background:linear-gradient(135deg,#6366f1,#a855f7);
            color:white;
            font-size:18px;
            font-weight:600;
            cursor:pointer;
            transition:.3s;
        }

        button[type="submit"]:hover{
            transform:translateY(-2px);
            box-shadow:0 0 30px rgba(139,92,246,.4);
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

    @if ($errors->any())
    <div class="error-box">
        {{ $errors->first() }}
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}" autocomplete="off">

        @csrf

        <div class="input-wrap">
            <input type="email" name="email" placeholder="Email" autocomplete="off" value="{{ old('email') }}" required>
        </div>

        <div class="input-wrap">
            <input type="password" name="password" id="password" placeholder="Password" class="has-toggle" autocomplete="new-password" required>
            <button type="button" class="toggle-pw" onclick="togglePassword('password', this)"></button>
        </div>

        <a href="{{ route('password.request') }}" class="forgot">Lupa password?</a>

        <button type="submit">Login</button>

    </form>

    <div class="bottom">
        Belum punya akun?
        <a href="{{ route('register') }}">Register</a>
    </div>

</div>

<script>
const eyeOpen = `<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>`;
const eyeClosed = `<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>`;

function togglePassword(id, btn) {
    const input = document.getElementById(id);
    if (input.type === 'password') {
        input.type = 'text';
        btn.innerHTML = eyeOpen;
    } else {
        input.type = 'password';
        btn.innerHTML = eyeClosed;
    }
}

// Set initial icon
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.toggle-pw').forEach(btn => {
        btn.innerHTML = eyeClosed;
    });
});
</script>

</body>
</html>