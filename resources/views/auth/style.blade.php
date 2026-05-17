<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial,sans-serif;
}

body{
    background:#f1f5f9;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

.card{
    width:400px;
    background:white;
    padding:35px;
    border-radius:16px;
    box-shadow:0 4px 20px rgba(0,0,0,0.08);
}

.title{
    font-size:28px;
    font-weight:bold;
    color:#1e293b;
    margin-bottom:10px;
    text-align:center;
}

.subtitle{
    text-align:center;
    color:#64748b;
    margin-bottom:30px;
    font-size:14px;
}

.input-group{
    margin-bottom:18px;
}

.input-group label{
    display:block;
    margin-bottom:8px;
    color:#334155;
    font-size:14px;
}

.input-group input{
    width:100%;
    padding:12px;
    border:1px solid #cbd5e1;
    border-radius:10px;
    font-size:14px;
}

.input-group input:focus{
    outline:none;
    border-color:#2563eb;
}

.btn{
    width:100%;
    padding:12px;
    border:none;
    border-radius:10px;
    background:#2563eb;
    color:white;
    font-weight:bold;
    cursor:pointer;
    transition:.2s;
}

.btn:hover{
    background:#1d4ed8;
}

.link{
    text-align:center;
    margin-top:18px;
    font-size:14px;
}

.link a{
    color:#2563eb;
    text-decoration:none;
}

.error{
    background:#fee2e2;
    color:#b91c1c;
    padding:10px;
    border-radius:10px;
    margin-bottom:20px;
}
</style>