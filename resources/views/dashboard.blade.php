<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
            font-size:clamp(36px,5vw,68px);
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
            overflow:hidden;
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

        .income{ border-color:rgba(34,197,94,.4); }
        .expense{ border-color:rgba(239,68,68,.4); }
        .balance{ border-color:rgba(139,92,246,.4); }
        .income .value{ color:#22c55e; }
        .expense .value{ color:#ff4d4d; }
        .balance .value{ color:#8b5cf6; }

        .value{
            font-size:clamp(24px,3vw,48px);
            font-weight:700;
            display:block;
            word-break:break-word;
            overflow-wrap:break-word;
            line-height:1.3;
        }

        .form-box{
            background:rgba(255,255,255,.03);
            border:1px solid rgba(255,255,255,.05);
            backdrop-filter:blur(18px);
            border-radius:30px;
            padding:28px;
            margin-bottom:35px;
        }

        .form-grid{
            display:grid;
            grid-template-columns:1.2fr 1.4fr 1fr 1fr 1fr;
            gap:18px;
        }

        input, select{
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

        select{
            cursor:pointer;
            appearance:none;
            -webkit-appearance:none;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%239ca3af' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat:no-repeat;
            background-position:right 18px center;
            padding-right:48px;
        }

        select option{
            background:#0f0a1e;
            color:white;
        }

        input:focus, select:focus{
            border:1px solid #8b5cf6;
            box-shadow:0 0 25px rgba(139,92,246,.25);
        }

        input[readonly]{ cursor:pointer; }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button{
            -webkit-appearance:none;
            margin:0;
        }
        input[type=number]{ -moz-appearance:textfield; }

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

        .save-btn:disabled{
            opacity:0.6;
            cursor:not-allowed;
            transform:none;
        }

        .export-btn{
            padding:18px 28px;
            background:rgba(255,255,255,.04);
            color:white;
            border:1px solid rgba(255,255,255,.08);
            font-size:18px;
        }

        .export-btn:hover{ background:#8b5cf6; }

        .export-link{ text-decoration:none; }

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

        .top-bar{
            width:100%;
            display:flex;
            justify-content:flex-start;
            align-items:flex-start;
            margin-bottom:30px;
            gap:20px;
        }

        .title-area{ display:flex; flex-direction:column; }

        .logout-btn{
            background:rgba(255,70,70,.12);
            border:1px solid rgba(255,70,70,.25);
            color:#ff5c5c;
            padding:16px 26px;
            border-radius:22px;
            font-size:16px;
            font-weight:600;
            cursor:pointer;
            transition:.2s ease;
            white-space:nowrap;
        }

        .logout-btn:hover{
            background:rgba(255,70,70,.18);
            transform:translateY(-2px);
        }

        .alert-error{
            background:rgba(255,70,70,.12);
            border:1px solid rgba(255,70,70,.3);
            border-radius:16px;
            padding:14px 20px;
            margin-bottom:18px;
            color:#ff5c5c;
            font-size:15px;
        }

        .alert-error ul{
            margin:0;
            padding-left:18px;
        }

        .alert-success{
            background:rgba(34,197,94,.10);
            border:1px solid rgba(34,197,94,.3);
            border-radius:16px;
            padding:14px 20px;
            margin-bottom:18px;
            color:#22c55e;
            font-size:15px;
        }

        .table-box{
            background:rgba(255,255,255,.03);
            border:1px solid rgba(255,255,255,.08);
            border-radius:32px;
            overflow-x:auto;
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

        tr td:first-child{ border-radius:18px 0 0 18px; }
        tr td:last-child{ border-radius:0 18px 18px 0; }

        .income-text{ color:#22c55e; font-weight:600; }
        .expense-text{ color:#ff4d4d; font-weight:600; }
        .balance-text{ color:#8b5cf6; font-weight:600; }

        .empty-state{
            text-align:center;
            padding:40px 20px;
            color:#6b7280;
            font-size:16px;
        }

        footer{
            margin-top:60px;
            text-align:center;
            color:#6b7280;
            font-size:18px;
        }

        /* DATE WRAPPER */
        .date-wrapper{
            position:relative;
            display:flex;
            align-items:center;
        }

        .date-icon{
            position:absolute;
            left:20px;
            top:50%;
            transform:translateY(-50%);
            pointer-events:auto;
            cursor:pointer;
            z-index:3;
            color:rgba(196,181,253,.65);
            display:flex;
            align-items:center;
            transition:color .25s, filter .25s;
        }

        .date-wrapper.active .date-icon{
            color:#a78bfa;
            filter:drop-shadow(0 0 7px rgba(167,139,250,.6));
        }

        .date-wrapper input,
        .date-wrapper .flatpickr-input{
            padding-left:54px !important;
            width:100% !important;
        }

        /* FLATPICKR */
        .flatpickr-calendar{
            background:rgba(15,15,25,.98) !important;
            border:1px solid rgba(139,92,246,.18) !important;
            border-radius:22px !important;
            box-shadow:0 20px 60px rgba(0,0,0,.45),0 0 25px rgba(139,92,246,.12);
            overflow:hidden;
            animation:fadeIn .18s ease;
        }

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
        .flatpickr-next-month svg{ fill:#c4b5fd !important; }

        .flatpickr-weekdays,
        .flatpickr-rContainer,
        .flatpickr-days,
        .dayContainer{ background:transparent !important; border:none !important; box-shadow:none !important; }

        .flatpickr-weekday{
            color:#8b5cf6 !important;
            font-size:11px !important;
            font-weight:600 !important;
            background:transparent !important;
        }

        .flatpickr-day{
            color:#e5e7eb !important;
            border:none !important;
            border-radius:12px !important;
            transition:.18s ease;
        }

        .flatpickr-day:hover{ background:rgba(139,92,246,.16) !important; }

        .flatpickr-day.selected{
            background:linear-gradient(135deg,#7c3aed,#a855f7) !important;
            color:white !important;
            box-shadow:0 0 15px rgba(168,85,247,.35);
        }

        .flatpickr-day.today{ border:1px solid rgba(168,85,247,.45) !important; }

        .flatpickr-day.prevMonthDay,
        .flatpickr-day.nextMonthDay{ color:rgba(255,255,255,.20) !important; }

        .flatpickr-calendar:before,
        .flatpickr-calendar:after{ display:none !important; }

        @keyframes fadeIn{
            from{ opacity:0; transform:translateY(-8px); }
            to{ opacity:1; transform:translateY(0); }
        }

        /* ========================
           FILTER & SEARCH BAR
        ======================== */
        .filter-section{
            background:rgba(255,255,255,.025);
            border:1px solid rgba(255,255,255,.07);
            backdrop-filter:blur(18px);
            border-radius:28px;
            padding:22px 28px;
            margin-bottom:28px;
        }

        .filter-label{
            font-size:13px;
            color:#7c3aed;
            font-weight:600;
            letter-spacing:.06em;
            text-transform:uppercase;
            margin-bottom:14px;
            display:flex;
            align-items:center;
            gap:8px;
        }

        .filter-label svg{
            color:#a78bfa;
        }

        .filter-grid{
            display:grid;
            grid-template-columns:2fr 1fr 1fr auto;
            gap:14px;
            align-items:center;
        }

        .search-wrapper{
            position:relative;
            display:flex;
            align-items:center;
        }

        .search-icon{
            position:absolute;
            left:20px;
            top:50%;
            transform:translateY(-50%);
            pointer-events:none;
            z-index:3;
            color:rgba(196,181,253,.6);
            display:flex;
            align-items:center;
        }

        .search-wrapper input{
            padding-left:52px !important;
            height:58px;
            font-size:17px;
        }

        .filter-grid select{
            height:58px;
            font-size:17px;
        }

        .reset-filter-btn{
            height:58px;
            padding:0 22px;
            background:rgba(139,92,246,.1);
            border:1px solid rgba(139,92,246,.25);
            color:#a78bfa;
            border-radius:18px;
            font-size:15px;
            font-weight:600;
            cursor:pointer;
            transition:.2s ease;
            white-space:nowrap;
        }

        .reset-filter-btn:hover{
            background:rgba(139,92,246,.2);
            transform:translateY(-1px);
        }

        /* Tab bulan */
        .month-tabs-wrapper{
            margin-bottom:22px;
        }

        .month-tabs-label{
            font-size:13px;
            color:#6b7280;
            font-weight:500;
            margin-bottom:10px;
        }

        .month-tabs{
            display:flex;
            gap:8px;
            flex-wrap:wrap;
        }

        .month-tab{
            padding:8px 18px;
            border-radius:50px;
            background:rgba(255,255,255,.04);
            border:1px solid rgba(255,255,255,.08);
            color:#9ca3af;
            font-size:14px;
            font-weight:500;
            cursor:pointer;
            transition:.2s ease;
            user-select:none;
        }

        .month-tab:hover{
            background:rgba(139,92,246,.12);
            border-color:rgba(139,92,246,.3);
            color:#c4b5fd;
        }

        .month-tab.active{
            background:linear-gradient(135deg,rgba(99,102,241,.3),rgba(168,85,247,.3));
            border-color:rgba(139,92,246,.5);
            color:#e9d5ff;
            font-weight:600;
        }

        /* Tahun selector */
        .year-tabs{
            display:flex;
            gap:8px;
            flex-wrap:wrap;
        }

        .year-tab{
            padding:8px 18px;
            border-radius:50px;
            background:rgba(255,255,255,.04);
            border:1px solid rgba(255,255,255,.08);
            color:#9ca3af;
            font-size:14px;
            font-weight:500;
            cursor:pointer;
            transition:.2s ease;
            user-select:none;
        }

        .year-tab:hover{
            background:rgba(99,102,241,.12);
            border-color:rgba(99,102,241,.3);
            color:#a5b4fc;
        }

        .year-tab.active{
            background:linear-gradient(135deg,rgba(99,102,241,.3),rgba(79,70,229,.3));
            border-color:rgba(99,102,241,.5);
            color:#c7d2fe;
            font-weight:600;
        }

        /* Count badge */
        .result-count{
            font-size:13px;
            color:#6b7280;
            margin-top:18px;
            padding-top:14px;
            border-top:1px solid rgba(255,255,255,.05);
            display:flex;
            align-items:center;
            gap:6px;
        }

        .result-count span{
            background:rgba(139,92,246,.15);
            border:1px solid rgba(139,92,246,.2);
            color:#a78bfa;
            font-weight:600;
            font-size:12px;
            padding:3px 10px;
            border-radius:50px;
        }

        /* Highlight search match */
        .highlight{
            background:rgba(168,85,247,.25);
            border-radius:4px;
            padding:0 2px;
            color:#e9d5ff;
        }

        /* ========================
           ACTION BUTTONS PER ROW
        ======================== */
        .row-actions{
            display:flex;
            gap:8px;
            align-items:center;
        }

        .btn-edit-row{
            background:rgba(99,102,241,.15);
            border:1px solid rgba(99,102,241,.35);
            color:#a5b4fc;
            padding:8px 16px;
            border-radius:12px;
            font-size:13px;
            font-weight:600;
            cursor:pointer;
            transition:.2s ease;
            white-space:nowrap;
        }

        .btn-edit-row:hover{
            background:rgba(99,102,241,.28);
            transform:translateY(-1px);
            box-shadow:0 0 12px rgba(99,102,241,.3);
        }

        .btn-del-row{
            background:rgba(255,70,70,.10);
            border:1px solid rgba(255,70,70,.25);
            color:#f87171;
            padding:8px 16px;
            border-radius:12px;
            font-size:13px;
            font-weight:600;
            cursor:pointer;
            transition:.2s ease;
            white-space:nowrap;
        }

        .btn-del-row:hover{
            background:rgba(255,70,70,.20);
            transform:translateY(-1px);
        }

        /* ========================
           MODAL EDIT
        ======================== */
        .modal-overlay{
            display:none;
            position:fixed;
            inset:0;
            background:rgba(0,0,0,.65);
            backdrop-filter:blur(6px);
            z-index:1000;
            align-items:center;
            justify-content:center;
            padding:20px;
        }

        .modal-overlay.open{
            display:flex;
        }

        .modal-box{
            background:rgba(8,8,20,.97);
            border:1px solid rgba(139,92,246,.25);
            border-radius:30px;
            padding:36px;
            width:100%;
            max-width:560px;
            box-shadow:0 30px 80px rgba(0,0,0,.6), 0 0 40px rgba(139,92,246,.12);
            animation:modalIn .22s ease;
            position:relative;
        }

        @keyframes modalIn{
            from{ opacity:0; transform:scale(.93) translateY(10px); }
            to{ opacity:1; transform:scale(1) translateY(0); }
        }

        .modal-title{
            font-size:22px;
            font-weight:700;
            margin-bottom:24px;
            background:linear-gradient(135deg,#8b5cf6,#6366f1);
            -webkit-background-clip:text;
            -webkit-text-fill-color:transparent;
        }

        .modal-close{
            position:absolute;
            top:20px;
            right:24px;
            background:rgba(255,255,255,.06);
            border:1px solid rgba(255,255,255,.1);
            color:#9ca3af;
            width:36px;
            height:36px;
            border-radius:50%;
            font-size:18px;
            display:flex;
            align-items:center;
            justify-content:center;
            cursor:pointer;
            transition:.2s;
        }

        .modal-close:hover{
            background:rgba(255,70,70,.15);
            color:#f87171;
            border-color:rgba(255,70,70,.3);
        }

        .modal-grid{
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:16px;
            margin-bottom:16px;
        }

        .modal-field{
            display:flex;
            flex-direction:column;
            gap:8px;
        }

        .modal-field.full{
            grid-column:1/-1;
        }

        .modal-label{
            font-size:13px;
            color:#9ca3af;
            font-weight:500;
            padding-left:4px;
        }

        .modal-input{
            height:54px;
            border:none;
            outline:none;
            border-radius:16px;
            padding:0 18px;
            font-size:17px;
            color:white;
            background:rgba(255,255,255,.05);
            border:1px solid rgba(255,255,255,.08);
            transition:.2s;
        }

        .modal-input:focus{
            border:1px solid #8b5cf6;
            box-shadow:0 0 20px rgba(139,92,246,.2);
        }

        .modal-input[readonly]{ cursor:pointer; }

        .modal-footer{
            display:flex;
            gap:12px;
            margin-top:24px;
        }

        .btn-update{
            flex:1;
            height:54px;
            background:linear-gradient(135deg,#6366f1,#a855f7);
            color:white;
            font-size:17px;
            font-weight:600;
            border-radius:16px;
            border:none;
            cursor:pointer;
            transition:.3s;
        }

        .btn-update:hover{
            transform:translateY(-2px);
            box-shadow:0 0 25px rgba(139,92,246,.4);
        }

        .btn-update:disabled{
            opacity:0.6;
            cursor:not-allowed;
            transform:none;
        }

        .btn-cancel{
            height:54px;
            padding:0 28px;
            background:rgba(255,255,255,.04);
            color:#9ca3af;
            font-size:17px;
            font-weight:600;
            border-radius:16px;
            border:1px solid rgba(255,255,255,.08);
            cursor:pointer;
            transition:.2s;
        }

        .btn-cancel:hover{
            background:rgba(255,255,255,.08);
            color:white;
        }

        /* Date icon inside modal */
        .modal-date-wrapper{
            position:relative;
            display:flex;
            align-items:center;
        }

        .modal-date-icon{
            position:absolute;
            left:16px;
            top:50%;
            transform:translateY(-50%);
            pointer-events:auto;
            cursor:pointer;
            z-index:3;
            color:rgba(196,181,253,.65);
            display:flex;
            align-items:center;
            transition:color .25s;
        }

        .modal-date-wrapper.active .modal-date-icon{
            color:#a78bfa;
        }

        .modal-date-wrapper .modal-input{
            padding-left:46px !important;
            width:100%;
        }

        /* RESPONSIVE */
        @media(max-width:900px){
            .container{ width:92%; padding:40px 0; }
            .title{ font-size:52px; line-height:1.1; }
            .subtitle{ font-size:17px; margin-top:10px; }
            .cards{ display:flex; flex-direction:column; gap:18px; }
            .card{ width:100%; padding:28px; }
            .value{ font-size:clamp(16px,5vw,36px); }
            .form-box{ padding:20px; border-radius:24px; }
            .form-grid{ grid-template-columns:1fr; gap:16px; }
            input, select{ height:65px; font-size:18px; }
            .save-btn{ width:100%; height:65px; font-size:18px; }
            .table-box::-webkit-scrollbar{ height:6px; }
            .table-box::-webkit-scrollbar-thumb{ background:rgba(139,92,246,.4); border-radius:20px; }
            table{ min-width:700px; }
            th, td{ font-size:15px; padding:18px 14px; }
            footer{ font-size:15px; line-height:1.7; padding:40px 0 20px; }
            .modal-grid{ grid-template-columns:1fr; }
            .filter-grid{ grid-template-columns:1fr 1fr; gap:10px; }
            .filter-grid .search-wrapper{ grid-column:1/-1; }
            .filter-grid .reset-filter-btn{ grid-column:1/-1; width:100%; }
            .search-wrapper input{ height:58px; }
            .filter-grid select{ height:58px; }
        }

        @media(max-width:500px){
            .title{ font-size:42px; }
            .value{ font-size:clamp(16px,5vw,32px); }
            .card{ padding:24px; }
            .table-box{ border-radius:22px; }
            .modal-box{ padding:24px; }
            .filter-grid{ grid-template-columns:1fr; }
        }

        /* ========================
           ANIMASI & RESPONSIVE CARDS
        ======================== */
        .value{
            transition:color .3s ease;
        }

        /* Flash animation saat nilai berubah */
        @keyframes valuePop{
            0%  { transform:scale(1);    opacity:1; }
            30% { transform:scale(1.05); opacity:.7; }
            100%{ transform:scale(1);    opacity:1; }
        }
        .value.pop{
            animation:valuePop .28s ease;
        }

        /* State kosong: bulan tidak ada transaksi */
        .card.empty-state{
            opacity:.55;
            transition:opacity .35s ease;
        }
        .card.empty-state .value{
            font-size:clamp(20px,2.5vw,38px) !important;
        }
        .card-empty-label{
            font-size:11px;
            color:#6b7280;
            margin-top:8px;
            display:none;
            letter-spacing:.4px;
            text-transform:uppercase;
        }
        .card.empty-state .card-empty-label{
            display:block;
        }

        /* Pill label bulan aktif di atas kartu */
        .active-filter-badge{
            display:inline-flex;
            align-items:center;
            gap:6px;
            font-size:13px;
            color:#a78bfa;
            background:rgba(139,92,246,.12);
            border:1px solid rgba(139,92,246,.2);
            border-radius:50px;
            padding:5px 14px;
            margin-bottom:18px;
            opacity:0;
            transform:translateY(-6px);
            transition:opacity .3s, transform .3s;
            pointer-events:none;
            user-select:none;
        }
        .active-filter-badge.visible{
            opacity:1;
            transform:translateY(0);
        }
        .active-filter-badge svg{
            flex-shrink:0;
        }

        /* Smooth card grid pada ukuran medium */
        @media(min-width:501px) and (max-width:900px){
            .cards{ grid-template-columns:1fr !important; }
        }
        @media(min-width:901px){
            .cards{ display:grid; grid-template-columns:repeat(3,1fr); gap:24px; }
        }

    </style>
</head>
<body>

<div class="container">

    <!-- TOP BAR -->
    <div class="top-bar">
        <div class="title-area">
            <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap;">
                <h1 class="title">
                    Catatan <span>Keuangan</span>
                </h1>

                <!-- LOGOUT -->
                <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="logout-btn">
                        Logout
                    </button>
                </form>
            </div>

            <p class="subtitle">
                Kelola pemasukan dan pengeluaran duet mu pakcik
            </p>
        </div>
    </div>

    <!-- CARDS -->
    <div id="activeFilterBadge" class="active-filter-badge">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
        <span id="activeBadgeText">Filter aktif</span>
    </div>
    <div class="cards">

        <div class="card income" id="cardIncomeBox">
            <h3>Total Pemasukan</h3>
            <div class="value" id="cardPemasukan">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
            <div class="card-empty-label">Tidak ada transaksi</div>
        </div>

        <div class="card expense" id="cardExpenseBox">
            <h3>Total Pengeluaran</h3>
            <div class="value" id="cardPengeluaran">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
            <div class="card-empty-label">Tidak ada transaksi</div>
        </div>

        <div class="card balance" id="cardBalanceBox">
            <h3>Saldo Akhir</h3>
            <div class="value" id="cardSaldo">Rp {{ number_format($saldoAkhir, 0, ',', '.') }}</div>
            <div class="card-empty-label">Tidak ada transaksi</div>
        </div>

    </div>

    <!-- FLASH SUCCESS -->
    @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <!-- FORM TAMBAH TRANSAKSI -->
    <div class="form-box">

        @if ($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('transaksi.store') }}" method="POST" id="formTransaksi">
            @csrf

            <div class="form-grid">

                <div class="date-wrapper" id="dateWrapper">
                    <span class="date-icon">
                        <svg width="21" height="21" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.85"
                             stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="4"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8"  y1="2" x2="8"  y2="6"/>
                            <line x1="3"  y1="10" x2="21" y2="10"/>
                            <circle cx="8"  cy="15.5" r="1.1" fill="currentColor" stroke="none"/>
                            <circle cx="12" cy="15.5" r="1.1" fill="currentColor" stroke="none"/>
                            <circle cx="16" cy="15.5" r="1.1" fill="currentColor" stroke="none"/>
                        </svg>
                    </span>
                    <input
                        type="text"
                        id="tanggal"
                        name="tanggal"
                        placeholder="Pilih tanggal"
                        required
                        readonly
                        value="{{ old('tanggal') }}"
                    >
                </div>

                <input
                    type="text"
                    name="keterangan"
                    placeholder="Keterangan"
                    required
                    maxlength="255"
                    value="{{ old('keterangan') }}"
                >

                <input
                    type="number"
                    name="pemasukan"
                    placeholder="Pemasukan"
                    min="0"
                    step="1"
                    value="{{ old('pemasukan') }}"
                >

                <input
                    type="number"
                    name="pengeluaran"
                    placeholder="Pengeluaran"
                    min="0"
                    step="1"
                    value="{{ old('pengeluaran') }}"
                >

                <button class="save-btn" type="submit" id="btnSimpan">
                    Simpan
                </button>

            </div>

        </form>

    </div>

    <!-- HAPUS & EXPORT -->
    <div style="display:flex;gap:14px;flex-wrap:wrap;margin-bottom:35px;">

        <form action="{{ route('transaksi.destroyAll') }}" method="POST"
              onsubmit="return confirm('⚠️ Yakin mau hapus SEMUA riwayat?\nData tidak bisa dikembalikan!')">
            @csrf
            @method('DELETE')
            <button type="submit" class="delete-btn">
                Hapus Riwayat
            </button>
        </form>

        <a href="{{ route('transaksi.export') }}" class="export-link">
            <button type="button" class="export-btn">
                Export Excel
            </button>
        </a>

    </div>

    <!-- ========================
         FILTER & SEARCH SECTION
    ======================== -->
    <div class="filter-section">

        <div class="filter-label">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
            </svg>
            Filter & Cari Transaksi
        </div>

        <!-- Search + Bulan + Tahun -->
        <div class="filter-grid">

            <div class="search-wrapper">
                <span class="search-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                </span>
                <input
                    type="text"
                    id="searchInput"
                    placeholder="Cari keterangan transaksi..."
                    oninput="applyFilters()"
                    autocomplete="off"
                >
            </div>

            <select id="filterBulan" onchange="applyFilters()">
                <option value="">Semua Bulan</option>
                <option value="01">Januari</option>
                <option value="02">Februari</option>
                <option value="03">Maret</option>
                <option value="04">April</option>
                <option value="05">Mei</option>
                <option value="06">Juni</option>
                <option value="07">Juli</option>
                <option value="08">Agustus</option>
                <option value="09">September</option>
                <option value="10">Oktober</option>
                <option value="11">November</option>
                <option value="12">Desember</option>
            </select>

            <select id="filterTahun" onchange="applyFilters()">
                <option value="">Semua Tahun</option>
                {{-- Tahun dinamis dari data transaksi --}}
                @php
                    $years = $transaksis->map(fn($t) => \Carbon\Carbon::parse($t->tanggal)->year)->unique()->sortDesc();
                @endphp
                @foreach($years as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>

            <button class="reset-filter-btn" onclick="resetFilters()">
                ✕ Reset
            </button>

        </div>

        <!-- Tab Bulan Cepat -->
        <div style="margin-top:18px;">
            <div class="month-tabs-label">Pilih cepat bulan:</div>
            <div class="month-tabs" id="monthTabs">
                <div class="month-tab active" data-bulan="" onclick="setMonthTab(this)">Semua</div>
                <div class="month-tab" data-bulan="01" onclick="setMonthTab(this)">Jan</div>
                <div class="month-tab" data-bulan="02" onclick="setMonthTab(this)">Feb</div>
                <div class="month-tab" data-bulan="03" onclick="setMonthTab(this)">Mar</div>
                <div class="month-tab" data-bulan="04" onclick="setMonthTab(this)">Apr</div>
                <div class="month-tab" data-bulan="05" onclick="setMonthTab(this)">Mei</div>
                <div class="month-tab" data-bulan="06" onclick="setMonthTab(this)">Jun</div>
                <div class="month-tab" data-bulan="07" onclick="setMonthTab(this)">Jul</div>
                <div class="month-tab" data-bulan="08" onclick="setMonthTab(this)">Agu</div>
                <div class="month-tab" data-bulan="09" onclick="setMonthTab(this)">Sep</div>
                <div class="month-tab" data-bulan="10" onclick="setMonthTab(this)">Okt</div>
                <div class="month-tab" data-bulan="11" onclick="setMonthTab(this)">Nov</div>
                <div class="month-tab" data-bulan="12" onclick="setMonthTab(this)">Des</div>
            </div>
        </div>

        <!-- Tab Tahun Cepat -->
        <div style="margin-top:14px;">
            <div class="month-tabs-label">Pilih cepat tahun:</div>
            <div class="year-tabs" id="yearTabs">
                <div class="year-tab active" data-tahun="" onclick="setYearTab(this)">Semua</div>
                @foreach($years as $year)
                    <div class="year-tab" data-tahun="{{ $year }}" onclick="setYearTab(this)">{{ $year }}</div>
                @endforeach
            </div>
        </div>

        <!-- Hasil -->
        <div class="result-count" id="resultCount">
            Menampilkan <span id="countShown">{{ $transaksis->count() }}</span> dari {{ $transaksis->count() }} transaksi
        </div>

    </div>

    <!-- TABLE -->
    <div class="table-box">

        <table id="mainTable">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th>Pemasukan</th>
                    <th>Pengeluaran</th>
                    <th>Saldo</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">

                @forelse ($transaksis as $transaksi)
                <tr
                    class="transaksi-row"
                    data-tanggal="{{ $transaksi->tanggal }}"
                    data-bulan="{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('m') }}"
                    data-tahun="{{ \Carbon\Carbon::parse($transaksi->tanggal)->year }}"
                    data-keterangan="{{ strtolower($transaksi->keterangan) }}"
                    data-pemasukan="{{ $transaksi->pemasukan }}"
                    data-pengeluaran="{{ $transaksi->pengeluaran }}"
                    data-saldo="{{ $transaksi->saldo }}"
                >
                    <td>{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y') }}</td>
                    <td class="keterangan-cell">{{ $transaksi->keterangan }}</td>
                    <td class="income-text">
                        Rp {{ number_format($transaksi->pemasukan, 0, ',', '.') }}
                    </td>
                    <td class="expense-text">
                        Rp {{ number_format($transaksi->pengeluaran, 0, ',', '.') }}
                    </td>
                    <td class="balance-text">
                        Rp {{ number_format($transaksi->saldo, 0, ',', '.') }}
                    </td>
                    <td>
                        <div class="row-actions">
                            <!-- Tombol Edit -->
                            <button
                                type="button"
                                class="btn-edit-row"
                                onclick="openEditModal(
                                    {{ $transaksi->id }},
                                    '{{ $transaksi->tanggal }}',
                                    '{{ addslashes($transaksi->keterangan) }}',
                                    {{ $transaksi->pemasukan }},
                                    {{ $transaksi->pengeluaran }}
                                )"
                            >
                                ✏️ Edit
                            </button>

                            <!-- Tombol Hapus per baris -->
                            <form
                                action="{{ route('transaksi.destroy', $transaksi->id) }}"
                                method="POST"
                                onsubmit="return confirm('Hapus transaksi ini?')"
                                style="margin:0;"
                            >
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-del-row">🗑 Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr id="emptyRow">
                    <td colspan="6" class="empty-state">
                        Belum ada transaksi. Yuk catat pengeluaran pertama kamu!
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>

        <!-- Empty state saat filter kosong -->
        <div id="filterEmptyState" style="display:none; text-align:center; padding:40px 20px; color:#6b7280; font-size:16px;">
            🔍 Tidak ada transaksi yang cocok dengan filter ini.
        </div>

    </div>

    <footer>
        © 2026 Catatan Keuangan pakcik
    </footer>

</div>

<!-- ========================
     MODAL EDIT TRANSAKSI
======================== -->
<div class="modal-overlay" id="editModal">
    <div class="modal-box">

        <button class="modal-close" onclick="closeEditModal()" title="Tutup">✕</button>

        <div class="modal-title">Edit Transaksi</div>

        <form id="formEdit" method="POST">
            @csrf
            @method('PUT')

            <div class="modal-grid">

                <!-- Tanggal -->
                <div class="modal-field full">
                    <label class="modal-label">Tanggal</label>
                    <div class="modal-date-wrapper" id="modalDateWrapper">
                        <span class="modal-date-icon" id="modalDateIconBtn">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.85"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="4"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8"  y1="2" x2="8"  y2="6"/>
                                <line x1="3"  y1="10" x2="21" y2="10"/>
                                <circle cx="8"  cy="15.5" r="1.1" fill="currentColor" stroke="none"/>
                                <circle cx="12" cy="15.5" r="1.1" fill="currentColor" stroke="none"/>
                                <circle cx="16" cy="15.5" r="1.1" fill="currentColor" stroke="none"/>
                            </svg>
                        </span>
                        <input
                            type="text"
                            id="editTanggal"
                            name="tanggal"
                            class="modal-input"
                            placeholder="Pilih tanggal"
                            required
                            readonly
                        >
                    </div>
                </div>

                <!-- Keterangan -->
                <div class="modal-field full">
                    <label class="modal-label">Keterangan</label>
                    <input
                        type="text"
                        id="editKeterangan"
                        name="keterangan"
                        class="modal-input"
                        placeholder="Keterangan"
                        maxlength="255"
                        required
                    >
                </div>

                <!-- Pemasukan -->
                <div class="modal-field">
                    <label class="modal-label">Pemasukan</label>
                    <input
                        type="number"
                        id="editPemasukan"
                        name="pemasukan"
                        class="modal-input"
                        placeholder="0"
                        min="0"
                        step="1"
                    >
                </div>

                <!-- Pengeluaran -->
                <div class="modal-field">
                    <label class="modal-label">Pengeluaran</label>
                    <input
                        type="number"
                        id="editPengeluaran"
                        name="pengeluaran"
                        class="modal-input"
                        placeholder="0"
                        min="0"
                        step="1"
                    >
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeEditModal()">Batal</button>
                <button type="submit" class="btn-update" id="btnUpdate">Simpan Perubahan</button>
            </div>

        </form>

    </div>
</div>

<!-- FLATPICKR -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

<script>

    // ========================
    // Flatpickr — form tambah
    // ========================
    const wrapper = document.getElementById('dateWrapper');

    const fp = flatpickr("#tanggal", {
        locale: "id",
        dateFormat: "Y-m-d",
        position: "below",
        monthSelectorType: "static",
        maxDate: "today",
        onOpen:  function(){ wrapper.classList.add('active'); },
        onClose: function(){ wrapper.classList.remove('active'); }
    });

    document.querySelector('.date-icon').addEventListener('click', function(e){
        e.stopPropagation();
        fp.isOpen ? fp.close() : fp.open();
    });

    // Cegah double submit
    document.getElementById('formTransaksi').addEventListener('submit', function() {
        const btn = document.getElementById('btnSimpan');
        btn.disabled = true;
        btn.textContent = 'Menyimpan...';
    });

    // Validasi client-side form tambah
    document.getElementById('formTransaksi').addEventListener('submit', function(e) {
        const pemasukan   = parseFloat(document.querySelector('[name=pemasukan]').value)   || 0;
        const pengeluaran = parseFloat(document.querySelector('[name=pengeluaran]').value) || 0;
        const tanggal     = document.querySelector('[name=tanggal]').value;
        const keterangan  = document.querySelector('[name=keterangan]').value.trim();

        if (!tanggal) {
            e.preventDefault();
            alert('Pilih tanggal dulu ya!');
            document.getElementById('btnSimpan').disabled = false;
            document.getElementById('btnSimpan').textContent = 'Simpan';
            return;
        }

        if (!keterangan) {
            e.preventDefault();
            alert('Keterangan tidak boleh kosong!');
            document.getElementById('btnSimpan').disabled = false;
            document.getElementById('btnSimpan').textContent = 'Simpan';
            return;
        }

        if (pemasukan === 0 && pengeluaran === 0) {
            e.preventDefault();
            alert('Isi minimal pemasukan atau pengeluaran!');
            document.getElementById('btnSimpan').disabled = false;
            document.getElementById('btnSimpan').textContent = 'Simpan';
            return;
        }

        if (pemasukan < 0 || pengeluaran < 0) {
            e.preventDefault();
            alert('Nominal tidak boleh minus!');
            document.getElementById('btnSimpan').disabled = false;
            document.getElementById('btnSimpan').textContent = 'Simpan';
            return;
        }
    });

    // ========================
    // Flatpickr — modal edit
    // ========================
    const modalWrapper = document.getElementById('modalDateWrapper');

    const fpEdit = flatpickr("#editTanggal", {
        locale: "id",
        dateFormat: "Y-m-d",
        position: "below",
        monthSelectorType: "static",
        maxDate: "today",
        onOpen:  function(){ modalWrapper.classList.add('active'); },
        onClose: function(){ modalWrapper.classList.remove('active'); }
    });

    document.getElementById('modalDateIconBtn').addEventListener('click', function(e){
        e.stopPropagation();
        fpEdit.isOpen ? fpEdit.close() : fpEdit.open();
    });

    // ========================
    // Buka / tutup modal edit
    // ========================
    function openEditModal(id, tanggal, keterangan, pemasukan, pengeluaran) {
        const form = document.getElementById('formEdit');
        form.action = '/transaksi/' + id;

        fpEdit.setDate(tanggal, true);
        document.getElementById('editKeterangan').value  = keterangan;
        document.getElementById('editPemasukan').value   = pemasukan  > 0 ? pemasukan  : '';
        document.getElementById('editPengeluaran').value = pengeluaran > 0 ? pengeluaran : '';

        const btn = document.getElementById('btnUpdate');
        btn.disabled = false;
        btn.textContent = 'Simpan Perubahan';

        document.getElementById('editModal').classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.remove('open');
        document.body.style.overflow = '';
    }

    document.getElementById('editModal').addEventListener('click', function(e){
        if (e.target === this) closeEditModal();
    });

    document.addEventListener('keydown', function(e){
        if (e.key === 'Escape') closeEditModal();
    });

    document.getElementById('formEdit').addEventListener('submit', function(e) {
        const pemasukan   = parseFloat(document.getElementById('editPemasukan').value)   || 0;
        const pengeluaran = parseFloat(document.getElementById('editPengeluaran').value) || 0;
        const tanggal     = document.getElementById('editTanggal').value;
        const keterangan  = document.getElementById('editKeterangan').value.trim();

        if (!tanggal) {
            e.preventDefault();
            alert('Pilih tanggal dulu ya!');
            return;
        }

        if (!keterangan) {
            e.preventDefault();
            alert('Keterangan tidak boleh kosong!');
            return;
        }

        if (pemasukan === 0 && pengeluaran === 0) {
            e.preventDefault();
            alert('Isi minimal pemasukan atau pengeluaran!');
            return;
        }

        if (pemasukan < 0 || pengeluaran < 0) {
            e.preventDefault();
            alert('Nominal tidak boleh minus!');
            return;
        }

        const btn = document.getElementById('btnUpdate');
        btn.disabled = true;
        btn.textContent = 'Menyimpan...';
    });

    // ========================
    // FILTER REALTIME UNTUK CARD + RIWAYAT
    // Sumber angka dibuat dari data transaksi, bukan dari tampilan row.
    // Jadi setelah klik April/Mei/Juni, card langsung berubah tanpa refresh.
    // ========================
    const FILTER_KEY = 'keuangan_filter_v3_realtime';

    @php
        $transaksiDataRealtime = $transaksis->map(function($t) {
            return [
                'id'          => $t->id,
                'tanggal'     => \Carbon\Carbon::parse($t->tanggal)->format('Y-m-d'),
                'bulan'       => \Carbon\Carbon::parse($t->tanggal)->format('m'),
                'tahun'       => (string) \Carbon\Carbon::parse($t->tanggal)->year,
                'keterangan'  => strtolower((string) $t->keterangan),
                'pemasukan'   => (float) ($t->pemasukan ?? 0),
                'pengeluaran' => (float) ($t->pengeluaran ?? 0),
            ];
        })->values();
    @endphp

    const transaksiData = {!! \Illuminate\Support\Js::from($transaksiDataRealtime) !!};

    const totalRows = transaksiData.length;

    var BULAN_LABEL = {
        '01':'Januari','02':'Februari','03':'Maret','04':'April',
        '05':'Mei','06':'Juni','07':'Juli','08':'Agustus',
        '09':'September','10':'Oktober','11':'November','12':'Desember'
    };

    function getEl(id) {
        return document.getElementById(id);
    }

    function getFilterValue() {
        return {
            search: (getEl('searchInput')?.value || '').toLowerCase().trim(),
            bulan:  (getEl('filterBulan')?.value || '').trim(),
            tahun:  (getEl('filterTahun')?.value || '').trim()
        };
    }

    function saveFilterState() {
        const f = getFilterValue();
        try {
            localStorage.setItem(FILTER_KEY, JSON.stringify(f));
        } catch(e) {}
    }

    function restoreFilterState() {
        try {
            const saved = localStorage.getItem(FILTER_KEY);
            if (!saved) return;

            const state = JSON.parse(saved);

            if (state.bulan !== undefined) getEl('filterBulan').value = state.bulan;
            if (state.tahun !== undefined) getEl('filterTahun').value = state.tahun;
            if (state.search !== undefined) getEl('searchInput').value = state.search;

            syncMonthTab(state.bulan || '');
            syncYearTab(state.tahun || '');
        } catch(e) {}
    }

    function formatRupiah(n) {
        n = Number(n) || 0;
        return 'Rp ' + Math.abs(Math.round(n)).toLocaleString('id-ID');
    }

    function updateCards(sumPemasukan, sumPengeluaran, visibleCount) {
        const saldo = sumPemasukan - sumPengeluaran;

        const elPemasukan   = getEl('cardPemasukan');
        const elPengeluaran = getEl('cardPengeluaran');
        const elSaldo       = getEl('cardSaldo');

        if (!elPemasukan || !elPengeluaran || !elSaldo) return;

        elPemasukan.textContent   = formatRupiah(sumPemasukan);
        elPengeluaran.textContent = formatRupiah(sumPengeluaran);
        elSaldo.textContent       = (saldo < 0 ? '-' : '') + formatRupiah(saldo);
        elSaldo.style.color       = saldo < 0 ? '#ff4d4d' : '#8b5cf6';

        const isEmpty = visibleCount === 0;
        ['cardIncomeBox','cardExpenseBox','cardBalanceBox'].forEach(function(boxId) {
            const box = getEl(boxId);
            if (!box) return;
            box.classList.toggle('empty-state', isEmpty);
        });

        [elPemasukan, elPengeluaran, elSaldo].forEach(function(el) {
            el.classList.remove('pop');
            void el.offsetWidth;
            el.classList.add('pop');
        });
    }

    function updateBadge(bulan, tahun) {
        const badge = getEl('activeFilterBadge');
        const text  = getEl('activeBadgeText');
        if (!badge || !text) return;

        const parts = [];
        if (bulan) parts.push(BULAN_LABEL[bulan] || bulan);
        if (tahun) parts.push(tahun);

        if (parts.length) {
            text.textContent = 'Menampilkan: ' + parts.join(' ');
            badge.classList.add('visible');
        } else {
            badge.classList.remove('visible');
        }
    }

    function escapeRegex(str) {
        return str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    function matchTransaksi(item, filter) {
        const matchBulan  = !filter.bulan  || item.bulan === filter.bulan;
        const matchTahun  = !filter.tahun  || item.tahun === filter.tahun;
        const matchSearch = !filter.search || item.keterangan.includes(filter.search);
        return matchBulan && matchTahun && matchSearch;
    }

    function updateTableRows(filter) {
        const rows = document.querySelectorAll('.transaksi-row');
        let visibleCount = 0;

        rows.forEach(function(row) {
            const rowBulan = (row.getAttribute('data-bulan') || '').trim();
            const rowTahun = String(row.getAttribute('data-tahun') || '').trim();
            const rowKet   = (row.getAttribute('data-keterangan') || '').toLowerCase();

            const show = (!filter.bulan  || rowBulan === filter.bulan) &&
                         (!filter.tahun  || rowTahun === filter.tahun) &&
                         (!filter.search || rowKet.includes(filter.search));

            row.style.display = show ? '' : 'none';

            if (show) {
                visibleCount++;

                const ketCell = row.querySelector('.keterangan-cell');
                if (ketCell) {
                    const originalText = ketCell.textContent;
                    if (filter.search) {
                        const regex = new RegExp('(' + escapeRegex(filter.search) + ')', 'gi');
                        ketCell.innerHTML = originalText.replace(regex, '<span class="highlight">$1</span>');
                    } else {
                        ketCell.textContent = originalText;
                    }
                }
            }
        });

        return visibleCount;
    }

    function calculateSummary(filter) {
        let visibleCount = 0;
        let sumPemasukan = 0;
        let sumPengeluaran = 0;

        transaksiData.forEach(function(item) {
            if (!matchTransaksi(item, filter)) return;
            visibleCount++;
            sumPemasukan += Number(item.pemasukan) || 0;
            sumPengeluaran += Number(item.pengeluaran) || 0;
        });

        return { visibleCount, sumPemasukan, sumPengeluaran };
    }

    function applyFilters() {
        const filter = getFilterValue();

        saveFilterState();
        updateBadge(filter.bulan, filter.tahun);

        const shownFromRows = updateTableRows(filter);
        const summary = calculateSummary(filter);

        const visibleCount = summary.visibleCount;

        const countShown = getEl('countShown');
        if (countShown) countShown.textContent = visibleCount;

        const resultCount = getEl('resultCount');
        if (resultCount) {
            resultCount.innerHTML = 'Menampilkan <span>' + visibleCount + '</span> dari ' + totalRows + ' transaksi';
        }

        const filterEmpty = getEl('filterEmptyState');
        const tableBody   = getEl('tableBody');

        if (filterEmpty && tableBody) {
            if (visibleCount === 0 && totalRows > 0) {
                filterEmpty.style.display = 'block';
                tableBody.style.display   = 'none';
            } else {
                filterEmpty.style.display = 'none';
                tableBody.style.display   = '';
            }
        }

        updateCards(summary.sumPemasukan, summary.sumPengeluaran, visibleCount);
    }

    function syncMonthTab(value) {
        document.querySelectorAll('.month-tab').forEach(function(t){
            t.classList.toggle('active', t.getAttribute('data-bulan') === value);
        });
    }

    function syncYearTab(value) {
        document.querySelectorAll('.year-tab').forEach(function(t){
            t.classList.toggle('active', t.getAttribute('data-tahun') === value);
        });
    }

    function resetFilters() {
        getEl('searchInput').value = '';
        getEl('filterBulan').value = '';
        getEl('filterTahun').value = '';

        syncMonthTab('');
        syncYearTab('');

        try { localStorage.removeItem(FILTER_KEY); } catch(e) {}
        applyFilters();
    }

    function setMonthTab(el) {
        const value = el.getAttribute('data-bulan') || '';
        getEl('filterBulan').value = value;
        syncMonthTab(value);
        applyFilters();
    }

    function setYearTab(el) {
        const value = el.getAttribute('data-tahun') || '';
        getEl('filterTahun').value = value;
        syncYearTab(value);
        applyFilters();
    }

    getEl('filterBulan').addEventListener('change', function() {
        syncMonthTab(this.value || '');
        applyFilters();
    });

    getEl('filterTahun').addEventListener('change', function() {
        syncYearTab(this.value || '');
        applyFilters();
    });

    getEl('searchInput').addEventListener('input', applyFilters);

    function initApp() {
        restoreFilterState();
        applyFilters();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initApp);
    } else {
        initApp();
    }

</script>

</body>
</html>