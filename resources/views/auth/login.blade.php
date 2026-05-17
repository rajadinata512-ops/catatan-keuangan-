<!DOCTYPE html>
<html>
<head>
    <title>Login</title>

    <style>
        body{
            margin:0;
            font-family:Arial, sans-serif;
            background:#f3f4f6;
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
        }

        .card{
            background:white;
            padding:30px;
            border-radius:10px;
            width:350px;
            box-shadow:0 4px 10px rgba(0,0,0,0.1);
        }

        h1{
            text-align:center;
            margin-bottom:20px;
        }

        label{
            display:block;
            margin-bottom:5px;
        }

        input{
            width:100%;
            padding:10px;
            margin-bottom:15px;
            border:1px solid #ccc;
            border-radius:5px;
            box-sizing:border-box;
        }

        button{
            width:100%;
            padding:10px;
            background:#3b82f6;
            color:white;
            border:none;
            border-radius:5px;
            cursor:pointer;
            font-size:16px;
        }

        button:hover{
            background:#2563eb;
        }

        .error{
            background:#fee2e2;
            color:#b91c1c;
            padding:10px;
            border-radius:5px;
            margin-bottom:15px;
        }
    </style>
</head>

<body>

<div class="card">

    <h1>Login</h1>

    @if ($errors->any())
        <div class="error">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="/login">
        @csrf

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">
            Login
        </button>
    </form>

</div>

</body>
</html>