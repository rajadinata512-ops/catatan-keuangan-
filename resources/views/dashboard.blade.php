<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Catatan Keuangan</title>

    <!-- FONT -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- FLATPICKR -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">

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
            radial-gradient(circle at top left, rgba(124,58,237,0.22), transparent 30%),
            radial-gradient(circle at bottom right, rgba(168,85,247,0.22), transparent 30%),
            #020617;

            color:white;
            overflow-x:hidden;
        }

        .container{
            width:90%;
            max-width:1200px;
            margin:auto;
            padding:70px 0;
        }

        .title{
            font-size:68px;
            font-weight:700;
            line-height:1;
            margin-bottom:12px;
        }

        .title span{
            background:linear-gradient(135deg,#8b5cf6,#6366f1);
            -webkit-background-clip:text;
            -webkit-text-fill-color:transparent;
        }

        .subtitle{
            color:#9ca3af;
            margin-bottom:60px;
            font-size:20px;
        }

        .cards{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(320px,1fr));
            gap:24px;
            margin-bottom:40px;
        }

        .card{
            background:rgba(255,255,255,0.03);
            border:1px solid rgba(255,255,255,0.08);
            backdrop-filter:blur(18px);
            border-radius:28px;
            padding:28px;
            transition:.3s;

            min-width:0;
        }

        .card:hover{
            transform:translateY(-6px);
            box-shadow:0 0 40px rgba(139,92,246,.18);
        }

        .card h3{
            color:#9ca3af;
            margin-bottom:18px;
            font-weight:500;
        }

        .income{
            border-color:rgba(34,197,94,.4);
        }

        .expense{
            border-color:rgba(239,68,68,.4);
        }

        .balance{
            border-color:rgba(139,92,246,.4);
        }

        .income .value{
            color:#22c55e;
        }

        .expense .value{
            color:#ff4d4d;
        }

        .balance .value{
            color:#8b5cf6;
        }

        .value{
            font-size:clamp(18px,2.5vw,48px);
            font-weight:700;

            display:block;
            word-break:break-word;
            overflow-wrap:break-word;

            line-height:1.3;
        }

        .form-box{
            background: rgba(255,255,255,.03);
            border:1px solid rgba(255,255,255,.05);
            backdrop-filter: blur(18px);
            border-radius: 30px;
            padding:28px;
            margin-bottom:35px;
        }

        .form-grid{
            display:grid;
            grid-template-columns:1.2fr 1.4fr 1fr 1fr 1fr;
            gap:18px;
        }

        input{
            width:100%;
            height:70px;
            border:none;
            outline:none;
            border-radius:22px;
            padding:0 22px;
            font-size:20px;
            color:white;

            background:rgba(255,255,255,.04);
            border:1px solid rgba(255,255,255,.06);
        }

        input:focus{
            border:1px solid #8b5cf6;
            box-shadow:0 0 25px rgba(139,92,246,.25);
        }

        button{
            border:none;
            border-radius:22px;
            cursor:pointer;
            font-weight:600;
            transition:.3s;
        }

        .save-btn{
            background:linear-gradient(135deg,#6366f1,#a855f7);
            color:white;
            font-size:22px;
        }

        .save-btn:hover{
            transform:translateY(-2px);
            box-shadow:0 0 30px rgba(139,92,246,.4);
        }

        .export-btn{
            padding:18px 28px;
            background:rgba(255,255,255,.04);
            color:white;
            border:1px solid rgba(255,255,255,.08);
            margin-bottom:35px;
            font-size:18px;
        }

        .export-btn:hover{
            background:#8b5cf6;
        }

        .export-link{
             text-decoration:none;
        }

        .delete-btn{
            background:rgba(255,70,70,.12);
            border:1px solid rgba(255,70,70,.25);
            color:#ff5c5c;
            padding:16px 26px;
            border-radius:22px;
            font-size:16px;
            font-weight:600;
            cursor:pointer;
            transition:.2s ease;
        }

        .delete-btn:hover{
            background:rgba(255,70,70,.18);
            transform:translateY(-2px);
        }

        .logout-btn{
            background:rgba(255,255,255,.04);
            border:1px solid rgba(255,255,255,.10);
            color:#9ca3af;
            padding:16px 26px;
            border-radius:22px;
            font-size:16px;
            font-weight:600;
            cursor:pointer;
            transition:.2s ease;
        }

        .logout-btn:hover{
            background:rgba(255,255,255,.08);
            color:white;
            transform:translateY(-2px);
        }

        .table-box{
            background:rgba(255,255,255,.03);
            border:1px solid rgba(255,255,255,.08);
            border-radius:32px;
            overflow:hidden;
            padding:30px;
        }

        table{
            width:100%;
            border-collapse:separate;
            border-spacing:0 16px;
        }

        th{
            color:#9ca3af;
            font-weight:500;
            text-align:left;
            padding:0 22px;
        }

        td{
            padding:24px 22px;
            background:rgba(255,255,255,.04);
        }

        tr td:first-child{
            border-radius:18px 0 0 18px;
        }

        tr td:last-child{
            border-radius:0 18px 18px 0;
        }

        .income-text{
            color:#22c55e;
            font-weight:600;
        }

        .expense-text{
            color:#ff4d4d;
            font-weight:600;
        }

        .balance-text{
            color:#8b5cf6;
            font-weight:600;
        }

        footer{
            margin-top:60px;
            text-align:center;
            color:#6b7280;
            font-size:18px;
        }

        /* FLATPICKR ESTETIK */

/* ===== FLATPICKR CLEAN ===== */

/* ===== FLATPICKR PREMIUM ===== */

.flatpickr-calendar{
    background: rgba(15,15,25,.98) !important;

    border:1px solid rgba(139,92,246,.18) !important;

    border-radius:22px !important;

    box-shadow:
        0 20px 60px rgba(0,0,0,.45),
        0 0 25px rgba(139,92,246,.12);

    overflow:hidden;

    animation: fadeIn .18s ease;
}

/* HEADER */

        .flatpickr-months{
            background:rgba(255,255,255,.02) !important;
            padding:10px 8px;
        }

        .flatpickr-current-month{
            color:white !important;
            font-weight:600;
            font-size:16px !important;
        }

        .flatpickr-prev-month svg,
        .flatpickr-next-month svg{
            fill:#c4b5fd !important;
        }

        /* WEEK */

        .flatpickr-weekdays{
            background:transparent !important;

            border:none !important;

            box-shadow:none !important;
        }

        .flatpickr-weekday{
            color:#8b5cf6 !important;

            font-size:11px !important;

            font-weight:600 !important;

            background:transparent !important;

            border:none !important;

            box-shadow:none !important;
        }

        /* TAMBAH INI */

        .flatpickr-rContainer{
            border:none !important;
        }

        .flatpickr-days{
            border:none !important;
        }

        .dayContainer{
            border:none !important;
        }

        /* DAYS */

        .flatpickr-day{
            color:#e5e7eb !important;

            border:none !important;

            border-radius:12px !important;

            transition:.18s ease;
        }

        .flatpickr-day:hover{
            background:rgba(139,92,246,.16) !important;
        }

        .flatpickr-day.selected{
            background:linear-gradient(135deg,#7c3aed,#a855f7) !important;

            color:white !important;

            box-shadow:
                0 0 15px rgba(168,85,247,.35);
        }

        .flatpickr-day.today{
            border:1px solid rgba(168,85,247,.45) !important;
        }

        /* BULAN LAIN */

        .flatpickr-day.prevMonthDay,
        .flatpickr-day.nextMonthDay{
            color:rgba(255,255,255,.20) !important;
        }

        /* REMOVE ARROW */

        .flatpickr-calendar:before,
        .flatpickr-calendar:after{
            display:none !important;
        }

/* ANIMATION */

        @keyframes fadeIn{
            from{
                opacity:0;
                transform:translateY(-8px);
            }

            to{
                opacity:1;
                transform:translateY(0);
            }
        }
   
/* =========================
   RESPONSIVE MOBILE
========================= */

@media(max-width:900px){

    .container{
        width:92%;
        padding:40px 0;
    }

    .title{
        font-size:52px;
        line-height:1.1;
    }

    .subtitle{
        font-size:17px;
        margin-top:10px;
    }

    /* CARD */

    .cards{
        display:flex;
        flex-direction:column;
        gap:18px;
    }

    .card{
        width:100%;
        padding:28px;
    }

    .value{
        font-size:clamp(16px,5vw,36px);
    }

    /* FORM */

    .form-box{
        padding:20px;
        border-radius:24px;
    }

    .form-grid{
        grid-template-columns:1fr;
        gap:16px;
    }

    input{
        height:65px;
        font-size:18px;
    }

    .save-btn{
        width:100%;
        height:65px;
        font-size:18px;
    }

    /* TABLE */

    .table-box::-webkit-scrollbar{
        height:6px;
    }

    .table-box::-webkit-scrollbar-thumb{
        background:rgba(139,92,246,.4);
        border-radius:20px;
    }
    table{
        min-width:650px;
    }

    th,
    td{
        font-size:15px;
        padding:18px 14px;
    }

    /* FOOTER */

footer{
    font-size:15px;
    line-height:1.7;
    padding:40px 0 20px;
}
}

/* HP KECIL */

@media(max-width:500px){

    .title{
        font-size:42px;
    }

    .value{
        font-size:clamp(16px,5vw,32px);
    }

    .card{
        padding:24px;
    }

    .table-box{
        border-radius:22px;
    }
}

    </style>
</head>
<body>

<div class="container">

    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px; margin-bottom:12px;">
        <h1 class="title" style="margin-bottom:0;">
            Catatan <span>Keuangan</span>
        </h1>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn">
                Logout
            </button>
        </form>
    </div>

    <p class="subtitle">
        Kelola pemasukan dan pengeluaran duet mu pakcik 
    </p>

    <!-- CARD -->

    <div class="cards">

        <div class="card income">
            <h3>Total Pemasukan</h3>
        <div class="value">
                 Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
        </div>
        </div>

        <div class="card expense">
            <h3>Total Pengeluaran</h3>
            <div class="value">
                Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
            </div>
        </div>

        <div class="card balance">
            <h3>Saldo Akhir</h3>
            <div class="value">
                Rp {{ number_format($saldoAkhir, 0, ',', '.') }}
            </div>
        </div>

    </div>

    <!-- FORM -->

    <div class="form-box">

        <form action="/transaksi" method="POST">
            @csrf

            <div class="form-grid">

                <input
                    type="text"
                    id="tanggal"
                    name="tanggal"
                    placeholder="Pilih tanggal"
                >

                <input
                    type="text"
                    name="keterangan"
                    placeholder="Keterangan"
                    min="0"
                >

                <input
                    type="number"
                    name="pemasukan"
                    placeholder="Pemasukan"
                    min="0"
                >

                <input
                    type="number"
                    name="pengeluaran"
                    placeholder="Pengeluaran"
                >

                <button class="save-btn" type="submit">
                    Simpan
                </button>

            </div>

        </form>

    </div>

    <!-- EXPORT -->

<div style="display:flex; gap:14px; flex-wrap:wrap; margin-bottom:35px;">

    <form action="/hapus-semua" method="POST"
          onsubmit="return confirm('Hapus semua riwayat?')">

        @csrf
        @method('DELETE')

        <button type="submit" class="delete-btn">
            Hapus Riwayat
        </button>

    </form>

    <a href="/export" class="export-link">
        <button type="button" class="export-btn">
            Export Excel
        </button>
    </a>

</div>

    <!-- TABLE -->

    <div class="table-box">

        <table>

            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th>Pemasukan</th>
                    <th>Pengeluaran</th>
                    <th>Saldo</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($transaksis as $transaksi)

                <tr>

                    <td>
                        {{ $transaksi->tanggal }}
                    </td>

                    <td>
                        {{ $transaksi->keterangan }}
                    </td>

                    <td class="income-text">
                        Rp {{ number_format($transaksi->pemasukan, 0, ',', '.') }}
                    </td>

                    <td class="expense-text">
                        Rp {{ number_format($transaksi->pengeluaran, 0, ',', '.') }}
                    </td>

                    <td class="balance-text">
                        Rp {{ number_format($transaksi->saldo, 0, ',', '.') }}
                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

    <footer>
        © 2026 Catatan Keuangan pakcik
    </footer>

</div>

<!-- FLATPICKR -->

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>

flatpickr("#tanggal", {
    dateFormat: "Y-m-d",
    monthSelectorType: "static"
});

</script>

</body>
</html>