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

.card{
    width:100%;
    max-width:430px;
    background:rgba(255,255,255,.03);
    border:1px solid rgba(255,255,255,.08);
    backdrop-filter:blur(18px);
    border-radius:32px;
    padding:40px 30px;
    box-shadow:0 0 40px rgba(139,92,246,.15);
}

.title{
    font-size:40px;
    font-weight:700;
    text-align:center;
    margin-bottom:10px;
    color:white;
}

.title span{
    background:linear-gradient(135deg,#8b5cf6,#6366f1);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}

.subtitle{
    text-align:center;
    color:#9ca3af;
    margin-bottom:30px;
    font-size:15px;
}

.input-group{
    margin-bottom:18px;
}

.input-group label{
    display:block;
    margin-bottom:8px;
    color:#9ca3af;
    font-size:14px;
}

.input-group input{
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

.input-group input:focus{
    border:1px solid #8b5cf6;
    box-shadow:0 0 25px rgba(139,92,246,.25);
}

.input-group input::placeholder{
    color:#6b7280;
}

.btn{
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

.btn:hover{
    transform:translateY(-2px);
    box-shadow:0 0 30px rgba(139,92,246,.4);
}

.link{
    text-align:center;
    margin-top:20px;
    font-size:14px;
    color:#9ca3af;
}

.link a{
    color:#8b5cf6;
    text-decoration:none;
}

.error{
    background:rgba(239,68,68,.12);
    border:1px solid rgba(239,68,68,.3);
    color:#f87171;
    padding:12px 16px;
    border-radius:14px;
    margin-bottom:20px;
    font-size:14px;
}
</style>
