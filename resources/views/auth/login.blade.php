<!DOCTYPE html>
<html>
<head>
    <title>Catatan Keuangan</title>

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial, sans-serif;
        }

        body{
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            background:linear-gradient(135deg,#0f172a,#1e293b);
        }

        .container{
            display:flex;
            width:900px;
            height:500px;
            background:white;
            border-radius:20px;
            overflow:hidden;
            box-shadow:0 10px 30px rgba(0,0,0,0.3);
        }

        .left{
            flex:1;
            background:linear-gradient(135deg,#2563eb,#1d4ed8);
            color:white;
            display:flex;
            flex-direction:column;
            justify-content:center;
            align-items:center;
            padding:40px;
        }

        .left h1{
            font-size:42px;
            margin-bottom:15px;
        }

        .left p{
            text-align:center;
            line-height:1.6;
            opacity:0.9;
        }

        .money{
            font-size:90px;
            margin-bottom:20px;
        }

        .right{
            flex:1;
            display:flex;
            justify-content:center;
            align-items:center;
            padding:40px;
            background:#f8fafc;
        }

        .login-box{
            width:100%;
            max-width:320px;
        }

        .login-box h2{
            margin-bottom:30px;
            color:#0f172a;
            font-size:32px;
        }

        .input-group{
            margin-bottom:20px;
        }

        .input-group label{
            display:block;
            margin-bottom:8px;
            color:#334155;
            font-size:14px;
        }

        .input-group input{
            width:100%;
            padding:14px;
            border:1px solid #cbd5e1;
            border-radius:10px;
            font-size:15px;
            transition:0.3s;
        }

        .input-group input:focus{
            outline:none;
            border-color:#2563eb;
            box-shadow:0 0 0 3px rgba(37,99,235,0.2);
        }

        .btn{
            width:100%;
            padding:14px;
            border:none;
            border-radius:10px;
            background:#2563eb;
            color:white;
            font-size:16px;
            cursor:pointer;
            transition:0.3s;
            font-weight:bold;
        }

        .btn:hover{
            background:#1d4ed8;
            transform:translateY(-2px);
        }

        .footer{
            margin-top:20px;
            text-align:center;
            color:#64748b;
            font-size:14px;
        }

        .error{
            background:#fee2e2;
            color:#b91c1c;
            padding:12px;
            border-radius:10px;
            margin-bottom:20px;
        }

        @media(max-width:768px){
            .container{
                flex-direction:column;
                width:95%;
                height:auto;
            }

            .left{
                padding:30px;
            }

            .money{
                font-size:60px;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="left">
        <div class="money">💰</div>

        <h1>Catatan Keuangan</h1>

        <p>
            Kelola pemasukan dan pengeluaranmu
            dengan mudah, rapi, dan modern.
        </p>
    </div>

    <div class="right">

        <div class="login-box">

            <h2>Login</h2>

            @if ($errors->any())
                <div class="error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="/login">
                @csrf

                <div class="input-group">
                    <label>Email</label>

                    <input
                        type="email"
                        name="email"
                        placeholder="Masukkan email"
                        required
                    >
                </div>

                <div class="input-group">
                    <label>Password</label>

                    <input
                        type="password"
                        name="password"
                        placeholder="Masukkan password"
                        required
                    >
                </div>

                <button class="btn" type="submit">
                    Masuk
                </button>

            </form>

            <div class="footer">
                Sistem Informasi Keuangan
            </div>

        </div>

    </div>

</div>

</body>
</html>