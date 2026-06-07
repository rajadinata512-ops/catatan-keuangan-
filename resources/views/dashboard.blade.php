<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Catatan Keuangan</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">

    <style>
        *{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}
        body{
            min-height:100vh;
            background:
                radial-gradient(circle at top left,rgba(124,58,237,.22),transparent 30%),
                radial-gradient(circle at bottom right,rgba(168,85,247,.22),transparent 30%),
                #020617;
            color:white;
            overflow-x:hidden;
        }
        .container{width:90%;max-width:1200px;margin:auto;padding:70px 0;}
        .top-bar{display:flex;justify-content:space-between;align-items:flex-start;gap:20px;margin-bottom:30px;}
        .title{font-size:clamp(36px,5vw,68px);font-weight:700;line-height:1;margin-bottom:12px;}
        .title span{background:linear-gradient(135deg,#8b5cf6,#6366f1);-webkit-background-clip:text;-webkit-text-fill-color:transparent;}
        .subtitle{color:#9ca3af;font-size:20px;margin-bottom:30px;}
        .logout-btn,.delete-btn{
            background:rgba(255,70,70,.12);border:1px solid rgba(255,70,70,.25);color:#ff5c5c;
            padding:16px 26px;border-radius:22px;font-size:16px;font-weight:600;cursor:pointer;transition:.2s ease;white-space:nowrap;
        }
        .logout-btn:hover,.delete-btn:hover{background:rgba(255,70,70,.18);transform:translateY(-2px);}

        .active-filter-badge{display:inline-flex;align-items:center;gap:8px;font-size:13px;color:#a78bfa;background:rgba(139,92,246,.12);border:1px solid rgba(139,92,246,.2);border-radius:50px;padding:6px 14px;margin-bottom:18px;opacity:0;transform:translateY(-6px);transition:.3s;}
        .active-filter-badge.visible{opacity:1;transform:translateY(0);}

        .cards{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;margin-bottom:34px;}
        .card,.panel,.form-box,.filter-section,.table-box{
            background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.08);backdrop-filter:blur(18px);border-radius:28px;
            box-shadow:0 18px 70px rgba(0,0,0,.12);
        }
        .card{padding:28px;transition:.3s;overflow:hidden;min-width:0;}
        .card:hover,.panel:hover{transform:translateY(-4px);box-shadow:0 0 40px rgba(139,92,246,.16);}
        .card h3{color:#9ca3af;margin-bottom:14px;font-weight:500;font-size:18px;}
        .value{font-size:clamp(24px,3vw,46px);font-weight:700;display:block;word-break:break-word;overflow-wrap:break-word;line-height:1.25;transition:.25s;}
        .income{border-color:rgba(34,197,94,.4)}.expense{border-color:rgba(239,68,68,.4)}.balance{border-color:rgba(139,92,246,.4)}
        .income .value,.income-text{color:#22c55e}.expense .value,.expense-text{color:#ff4d4d}.balance .value,.balance-text{color:#8b5cf6}
        .card.empty-state{opacity:.56}.card-empty-label{font-size:11px;color:#6b7280;margin-top:8px;display:none;text-transform:uppercase;letter-spacing:.4px}.card.empty-state .card-empty-label{display:block;}
        @keyframes valuePop{0%{transform:scale(1);opacity:1}35%{transform:scale(1.045);opacity:.74}100%{transform:scale(1);opacity:1}}
        .value.pop{animation:valuePop .28s ease;}

        .analytics-grid{display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:35px;}
        .panel{padding:26px;transition:.3s;min-width:0;}
        .panel-head{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:20px;}
        .panel-title{font-size:17px;font-weight:700;color:#e9d5ff;text-transform:uppercase;letter-spacing:.06em;}
        .panel-subtitle{font-size:12px;color:#6b7280;margin-top:4px;}
        .report-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:14px;}
        .report-item{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:20px;padding:18px;min-height:112px;}
        .report-label{font-size:12px;color:#9ca3af;margin-bottom:10px;text-transform:uppercase;letter-spacing:.04em;}
        .report-value{font-size:clamp(18px,2vw,28px);font-weight:700;color:white;line-height:1.25;word-break:break-word;}
        .report-note{font-size:12px;color:#6b7280;margin-top:8px;}
        .bar-list{display:flex;flex-direction:column;gap:14px;}
        .bar-row{display:grid;grid-template-columns:130px 1fr 120px;align-items:center;gap:12px;}
        .bar-label{font-size:13px;color:#c4b5fd;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
        .bar-track{height:14px;border-radius:999px;background:rgba(255,255,255,.06);overflow:hidden;border:1px solid rgba(255,255,255,.06);}
        .bar-fill{height:100%;border-radius:999px;background:linear-gradient(90deg,#6366f1,#a855f7);min-width:4px;transition:width .35s ease;}
        .bar-fill.income-bar{background:linear-gradient(90deg,#16a34a,#22c55e)}
        .bar-fill.expense-bar{background:linear-gradient(90deg,#ef4444,#fb7185)}
        .bar-value{font-size:12px;color:#9ca3af;text-align:right;}
        .empty-chart{color:#6b7280;text-align:center;padding:34px 10px;font-size:14px;}

        .form-box{padding:28px;margin-bottom:35px;}
        .form-grid{display:grid;grid-template-columns:1.1fr 1fr 1.35fr .95fr .95fr .8fr;gap:16px;}
        input,select{width:100%;height:66px;border:none;outline:none;border-radius:22px;padding:0 20px;font-size:17px;color:white;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.06);transition:.2s;}
        select{cursor:pointer;appearance:none;-webkit-appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%239ca3af' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 18px center;padding-right:48px;}
        select option{background:#0f0a1e;color:white;}
        input:focus,select:focus{border:1px solid #8b5cf6;box-shadow:0 0 25px rgba(139,92,246,.25);}
        input[readonly]{cursor:pointer;}input[type=number]::-webkit-inner-spin-button,input[type=number]::-webkit-outer-spin-button{-webkit-appearance:none;margin:0}input[type=number]{-moz-appearance:textfield;}
        button{border:none;border-radius:22px;cursor:pointer;font-weight:600;transition:.25s;}
        .save-btn{background:linear-gradient(135deg,#6366f1,#a855f7);color:white;font-size:19px;}
        .save-btn:hover{transform:translateY(-2px);box-shadow:0 0 30px rgba(139,92,246,.4)}.save-btn:disabled{opacity:.6;cursor:not-allowed;transform:none;}
        .date-wrapper,.modal-date-wrapper{position:relative;display:flex;align-items:center;}
        .date-icon,.modal-date-icon{position:absolute;left:18px;top:50%;transform:translateY(-50%);pointer-events:auto;cursor:pointer;z-index:3;color:rgba(196,181,253,.68);display:flex;align-items:center;}
        .date-wrapper.active .date-icon,.modal-date-wrapper.active .modal-date-icon{color:#a78bfa;filter:drop-shadow(0 0 7px rgba(167,139,250,.6));}
        .date-wrapper input{padding-left:52px!important}.modal-date-wrapper input{padding-left:46px!important;}
        .alert-error,.alert-success{border-radius:16px;padding:14px 20px;margin-bottom:18px;font-size:15px}.alert-error{background:rgba(255,70,70,.12);border:1px solid rgba(255,70,70,.3);color:#ff5c5c}.alert-error ul{padding-left:18px}.alert-success{background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.3);color:#22c55e}
        .export-btn{padding:18px 28px;background:rgba(255,255,255,.04);color:white;border:1px solid rgba(255,255,255,.08);font-size:16px}.export-btn:hover{background:#8b5cf6}.export-link{text-decoration:none;}

        .filter-section{padding:26px;margin-bottom:28px;}
        .filter-label{display:flex;align-items:center;gap:8px;color:#8b5cf6;text-transform:uppercase;font-size:12px;font-weight:700;letter-spacing:.05em;margin-bottom:18px;}
        .filter-grid{display:grid;grid-template-columns:1.4fr .85fr .85fr .95fr auto;gap:14px;align-items:center;}
        .search-wrapper{position:relative}.search-icon{position:absolute;left:20px;top:50%;transform:translateY(-50%);color:#8b5cf6;opacity:.8}.search-wrapper input{padding-left:54px;height:58px;font-size:15px}.filter-grid select{height:58px;font-size:15px;border-radius:18px;}
        .reset-filter-btn{height:58px;padding:0 22px;background:rgba(139,92,246,.12);border:1px solid rgba(139,92,246,.3);color:#c4b5fd;font-size:14px;white-space:nowrap}.reset-filter-btn:hover{background:rgba(139,92,246,.22);transform:translateY(-1px);}
        .month-tabs-label{color:#6b7280;font-size:12px;margin:14px 0 8px}.month-tabs,.year-tabs{display:flex;gap:8px;flex-wrap:wrap;}
        .month-tab,.year-tab{padding:8px 16px;border-radius:50px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);color:#9ca3af;font-size:13px;font-weight:500;cursor:pointer;user-select:none;transition:.2s;}
        .month-tab:hover,.year-tab:hover{background:rgba(139,92,246,.12);border-color:rgba(139,92,246,.3);color:#c4b5fd}.month-tab.active,.year-tab.active{background:linear-gradient(135deg,rgba(99,102,241,.3),rgba(168,85,247,.3));border-color:rgba(139,92,246,.5);color:#e9d5ff;font-weight:700;}
        .result-count{font-size:13px;color:#6b7280;margin-top:18px;padding-top:14px;border-top:1px solid rgba(255,255,255,.05);display:flex;align-items:center;gap:6px}.result-count span{background:rgba(139,92,246,.15);border:1px solid rgba(139,92,246,.2);color:#a78bfa;font-weight:600;font-size:12px;padding:3px 10px;border-radius:50px;}

        .table-box{overflow-x:auto;padding:30px;}table{width:100%;border-collapse:separate;border-spacing:0 16px;min-width:940px;}th{color:#9ca3af;font-weight:500;text-align:left;padding:0 18px;font-size:14px;}td{padding:22px 18px;background:rgba(255,255,255,.04);font-size:14px;}tr td:first-child{border-radius:18px 0 0 18px}tr td:last-child{border-radius:0 18px 18px 0}.empty-state{text-align:center;padding:40px 20px;color:#6b7280;font-size:16px;}
        .category-badge{display:inline-flex;align-items:center;gap:6px;padding:8px 12px;border-radius:999px;background:rgba(139,92,246,.12);border:1px solid rgba(139,92,246,.25);color:#d8b4fe;font-size:12px;font-weight:700;white-space:nowrap;}
        .highlight{background:rgba(168,85,247,.25);border-radius:4px;padding:0 2px;color:#e9d5ff;}
        .month-group-row td{background:transparent!important;padding:22px 0 4px!important;border-radius:0!important}.month-group-title{display:flex;align-items:center;gap:12px;color:#c4b5fd;font-size:15px;font-weight:700;letter-spacing:.03em;text-transform:uppercase}.month-group-title:after{content:"";height:1px;flex:1;background:linear-gradient(90deg,rgba(139,92,246,.45),transparent)}.month-group-pill{display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:999px;background:rgba(139,92,246,.13);border:1px solid rgba(139,92,246,.28);box-shadow:0 0 18px rgba(139,92,246,.12);}
        .row-actions{display:flex;gap:8px;align-items:center}.btn-edit-row{background:rgba(99,102,241,.15);border:1px solid rgba(99,102,241,.35);color:#a5b4fc;padding:8px 16px;border-radius:12px;font-size:13px;font-weight:600;white-space:nowrap}.btn-edit-row:hover{background:rgba(99,102,241,.28);transform:translateY(-1px);box-shadow:0 0 12px rgba(99,102,241,.3)}.btn-del-row{background:rgba(255,70,70,.1);border:1px solid rgba(255,70,70,.25);color:#f87171;padding:8px 16px;border-radius:12px;font-size:13px;font-weight:600;white-space:nowrap}.btn-del-row:hover{background:rgba(255,70,70,.2);transform:translateY(-1px)}

        .modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.65);backdrop-filter:blur(6px);z-index:1000;align-items:center;justify-content:center;padding:20px}.modal-overlay.open{display:flex}.modal-box{background:rgba(8,8,20,.97);border:1px solid rgba(139,92,246,.25);border-radius:30px;padding:36px;width:100%;max-width:620px;box-shadow:0 30px 80px rgba(0,0,0,.6),0 0 40px rgba(139,92,246,.12);position:relative;animation:modalIn .22s ease;overflow:visible;}@keyframes modalIn{from{opacity:0;transform:scale(.93) translateY(10px)}to{opacity:1;transform:scale(1) translateY(0)}}.modal-title{font-size:22px;font-weight:700;margin-bottom:24px;background:linear-gradient(135deg,#8b5cf6,#6366f1);-webkit-background-clip:text;-webkit-text-fill-color:transparent}.modal-close{position:absolute;top:20px;right:24px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);color:#9ca3af;width:36px;height:36px;border-radius:50%;font-size:18px;display:flex;align-items:center;justify-content:center}.modal-close:hover{background:rgba(255,70,70,.15);color:#f87171;border-color:rgba(255,70,70,.3)}.modal-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;overflow:visible;}.modal-field{display:flex;flex-direction:column;gap:8px;overflow:visible;position:relative;}.modal-field.full{grid-column:1/-1}.modal-label{font-size:13px;color:#9ca3af;font-weight:500;padding-left:4px}.modal-input{height:54px;border:none;outline:none;border-radius:16px;padding:0 18px;font-size:16px;color:white;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.08);transition:.2s}.modal-input:focus{border:1px solid #8b5cf6;box-shadow:0 0 20px rgba(139,92,246,.2)}.modal-footer{display:flex;gap:12px;margin-top:24px}.btn-update{flex:1;height:54px;background:linear-gradient(135deg,#6366f1,#a855f7);color:white;font-size:17px;border-radius:16px}.btn-cancel{height:54px;padding:0 28px;background:rgba(255,255,255,.04);color:#9ca3af;font-size:17px;border-radius:16px;border:1px solid rgba(255,255,255,.08)}.btn-update:hover,.btn-cancel:hover{transform:translateY(-1px)}

       /* ===== CUSTOM DATEPICKER DARK PURPLE THEME ===== */
.flatpickr-calendar {
    background: #111427 !important;
    border: 1px solid rgba(139, 92, 246, 0.45) !important;
    border-radius: 18px !important;
    box-shadow: 0 18px 45px rgba(0, 0, 0, 0.55),
                0 0 30px rgba(139, 92, 246, 0.18) !important;
    color: #ffffff !important;
    overflow: hidden !important;
}

.flatpickr-calendar.arrowTop::before,
.flatpickr-calendar.arrowTop::after,
.flatpickr-calendar.arrowBottom::before,
.flatpickr-calendar.arrowBottom::after {
    border-bottom-color: #111427 !important;
    border-top-color: #111427 !important;
}

.flatpickr-months {
    background: linear-gradient(135deg, rgba(124, 58, 237, 0.45), rgba(17, 20, 39, 0.95)) !important;
    border-bottom: 1px solid rgba(139, 92, 246, 0.35) !important;
}

.flatpickr-month {
    color: #ffffff !important;
    height: 46px !important;
}

.flatpickr-current-month {
    color: #ffffff !important;
    font-weight: 700 !important;
    padding-top: 12px !important;
}

.flatpickr-current-month .flatpickr-monthDropdown-months {
    background: transparent !important;
    color: #ffffff !important;
    font-weight: 700 !important;
}

.flatpickr-current-month input.cur-year {
    color: #d8ccff !important;
    font-weight: 700 !important;
}

.flatpickr-prev-month,
.flatpickr-next-month {
    color: #ffffff !important;
    fill: #ffffff !important;
    top: 8px !important;
}

.flatpickr-prev-month:hover,
.flatpickr-next-month:hover {
    color: #a78bfa !important;
    fill: #a78bfa !important;
}

.flatpickr-weekdays {
    background: #151932 !important;
    border-bottom: 1px solid rgba(139, 92, 246, 0.22) !important;
}

.flatpickr-weekday {
    color: #d8ccff !important;
    font-weight: 700 !important;
    font-size: 12px !important;
}

.flatpickr-days {
    background: #111427 !important;
}

.dayContainer {
    background: #111427 !important;
    padding: 8px !important;
}

.flatpickr-day {
    color: #d7ddff !important;
    border-radius: 12px !important;
    border: 1px solid transparent !important;
    font-weight: 600 !important;
}

.flatpickr-day:hover {
    background: rgba(139, 92, 246, 0.22) !important;
    border-color: rgba(139, 92, 246, 0.55) !important;
    color: #ffffff !important;
}

.flatpickr-day.today {
    border-color: #8b5cf6 !important;
    color: #ffffff !important;
    background: rgba(139, 92, 246, 0.16) !important;
}

.flatpickr-day.selected,
.flatpickr-day.startRange,
.flatpickr-day.endRange {
    background: linear-gradient(135deg, #7c3aed, #a855f7) !important;
    border-color: #a855f7 !important;
    color: #ffffff !important;
    box-shadow: 0 0 18px rgba(168, 85, 247, 0.45) !important;
}

.flatpickr-day.prevMonthDay,
.flatpickr-day.nextMonthDay {
    color: rgba(215, 221, 255, 0.22) !important;
}

.flatpickr-day.flatpickr-disabled,
.flatpickr-day.flatpickr-disabled:hover {
    color: rgba(215, 221, 255, 0.18) !important;
    background: transparent !important;
    border-color: transparent !important;
    cursor: not-allowed !important;
}

.numInputWrapper span {
    border-color: rgba(139, 92, 246, 0.25) !important;
}

.numInputWrapper span:hover {
    background: rgba(139, 92, 246, 0.18) !important;
}

.numInputWrapper span.arrowUp::after {
    border-bottom-color: #ffffff !important;
}

.numInputWrapper span.arrowDown::after {
    border-top-color: #ffffff !important;
}
        footer{margin-top:60px;text-align:center;color:#6b7280;font-size:18px;}

        /* ===== CUSTOM KATEGORI DROPDOWN ===== */
        .kat-wrap{position:relative;width:100%;}
        .kat-trigger{
            width:100%;height:66px;background:rgba(255,255,255,.04);
            border:1px solid rgba(255,255,255,.06);border-radius:22px;
            padding:0 48px 0 20px;font-size:17px;color:white;
            display:flex;align-items:center;cursor:pointer;transition:.2s;user-select:none;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%239ca3af' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat:no-repeat;background-position:right 18px center;
        }
        .kat-trigger:hover,.kat-trigger.open{border-color:#8b5cf6;box-shadow:0 0 25px rgba(139,92,246,.25);}
        .kat-trigger.plch{color:#9ca3af;}
        .kat-wrap.size-sm .kat-trigger{height:58px;font-size:15px;border-radius:18px;}
        .kat-wrap.size-md .kat-trigger{height:54px;font-size:16px;border-radius:16px;}
        .kat-menu{
            position:fixed;top:0;left:0;width:200px;
            background:#111427;border:1px solid rgba(139,92,246,.4);border-radius:18px;
            z-index:10100;box-shadow:0 22px 55px rgba(0,0,0,.65),0 0 28px rgba(139,92,246,.15);
            display:none;overflow:visible;min-width:180px;
        }
        .kat-menu.show{display:block;animation:katSlide .18s ease;}
        @keyframes katSlide{from{opacity:0;transform:translateY(-8px)}to{opacity:1;transform:translateY(0)}}
        .kat-opts{max-height:220px;overflow-y:auto;padding:8px;border-radius:18px 18px 0 0;}
        .kat-opts::-webkit-scrollbar{width:4px;}.kat-opts::-webkit-scrollbar-thumb{background:rgba(139,92,246,.4);border-radius:4px;}
        .kat-opt{padding:11px 16px;border-radius:12px;color:white;cursor:pointer;font-size:15px;transition:.12s;user-select:none;}
        .kat-opt:hover{background:rgba(139,92,246,.18);color:#e9d5ff;}
        .kat-opt.sel{background:rgba(139,92,246,.26);color:#e9d5ff;font-weight:600;position:relative;padding-left:36px;}
        .kat-opt.sel::before{content:"✓";position:absolute;left:14px;color:#a78bfa;font-weight:700;}
        .kat-opt.plch-opt{color:#9ca3af;}
        .kat-line{height:1px;background:rgba(255,255,255,.08);margin:4px 8px;}
        .kat-footer{margin:4px 8px 8px;}
        .kat-add-row{
            display:flex;align-items:center;gap:8px;padding:10px 14px;
            border-radius:12px;color:#a78bfa;cursor:pointer;font-size:14px;
            transition:.12s;border:1px dashed rgba(139,92,246,.28);
        }
        .kat-add-row:hover{background:rgba(139,92,246,.1);border-color:rgba(139,92,246,.5);}
        .kat-add-area{padding:10px 0 4px;display:none;}
        .kat-add-area.show{display:block;}
        .kat-new-row{display:flex;gap:8px;}
        .kat-new-inp{
            flex:1;height:40px;background:rgba(255,255,255,.06);
            border:1px solid rgba(139,92,246,.35);border-radius:12px;
            padding:0 14px;font-size:14px;color:white;outline:none;
        }
        .kat-new-inp:focus{border-color:#8b5cf6;box-shadow:0 0 15px rgba(139,92,246,.2);}
        .kat-new-inp::placeholder{color:#6b7280;}
        .kat-new-btn{
            height:40px;padding:0 16px;background:linear-gradient(135deg,#6366f1,#a855f7);
            border:none;border-radius:12px;color:white;font-size:13px;font-weight:600;cursor:pointer;white-space:nowrap;
        }
        .kat-new-btn:hover{transform:translateY(-1px);box-shadow:0 4px 14px rgba(139,92,246,.35);}
        @media(max-width:1080px){.cards,.analytics-grid{grid-template-columns:1fr}.form-grid{grid-template-columns:1fr 1fr}.filter-grid{grid-template-columns:1fr 1fr}.filter-grid .search-wrapper{grid-column:1/-1}.filter-grid .reset-filter-btn{grid-column:1/-1;width:100%}.bar-row{grid-template-columns:100px 1fr 105px}.container{width:92%;padding:44px 0}.title{font-size:52px}.subtitle{font-size:17px}}
        @media(max-width:600px){.form-grid,.filter-grid,.report-grid,.modal-grid{grid-template-columns:1fr}.title{font-size:42px}.card,.panel,.form-box,.filter-section,.table-box{border-radius:24px;padding:22px}.top-bar{flex-direction:column}.bar-row{grid-template-columns:1fr}.bar-value{text-align:left}.modal-box{padding:24px}.modal-footer{flex-direction:column}.btn-cancel{width:100%;}.btn-update{width:100%;flex:auto}.value{font-size:32px}}
    </style>
</head>
<body>
@php
    $kategoriOptions = $kategoriOptions ?? ['Makanan','Minuman','Transportasi','Bensin','Tagihan','Paket Data','Hutang','Gaji','Belanja','Sparepart','Service Kereta','Pendidikan','Kesehatan','Hiburan','Tabungan','Lainnya'];
    $years = $transaksis->map(fn($t) => \Carbon\Carbon::parse($t->tanggal)->year)->unique()->sortDesc()->values();
    $transaksiDataRealtime = $transaksis->map(function($t) {
        $tanggal = \Carbon\Carbon::parse($t->tanggal);
        return [
            'id'          => $t->id,
            'tanggal'     => $tanggal->format('Y-m-d'),
            'bulan'       => $tanggal->format('m'),
            'tahun'       => (string) $tanggal->year,
            'keterangan'  => strtolower((string) $t->keterangan),
            'kategori'    => (string) ($t->kategori ?? 'Lainnya'),
            'pemasukan'   => (float) ($t->pemasukan ?? 0),
            'pengeluaran' => (float) ($t->pengeluaran ?? 0),
            'saldo'       => (float) ($t->saldo ?? 0),
        ];
    })->values();
@endphp
<div class="container">
    <div class="top-bar">
        <div>
            <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap;">
                <h1 class="title">Catatan <span>Keuangan</span></h1>
                <form action="{{ route('logout') }}" method="POST" style="margin:0;">@csrf<button type="submit" class="logout-btn">Logout</button></form>
            </div>
            <p class="subtitle">Kelola pemasukan dan pengeluaran duet mu pakcik</p>
        </div>
    </div>

    <div id="activeFilterBadge" class="active-filter-badge">⚡ <span id="activeBadgeText">Filter aktif</span></div>

    <div class="cards">
        <div class="card income" id="cardIncomeBox">
            <h3>Total Pemasukan</h3>
            <div class="value" id="cardPemasukan">Rp {{ number_format($totalPemasukan,0,',','.') }}</div>
            <div class="card-empty-label">Tidak ada transaksi</div>
        </div>
        <div class="card expense" id="cardExpenseBox">
            <h3>Total Pengeluaran</h3>
            <div class="value" id="cardPengeluaran">Rp {{ number_format($totalPengeluaran,0,',','.') }}</div>
            <div class="card-empty-label">Tidak ada transaksi</div>
        </div>
        <div class="card balance" id="cardBalanceBox">
            <h3>Saldo Akhir</h3>
            <div class="value" id="cardSaldo">{{ ($saldoAkhir ?? 0) < 0 ? '-Rp ' : 'Rp ' }}{{ number_format(abs($saldoAkhir ?? 0),0,',','.') }}</div>
            <div class="card-empty-label">Tidak ada transaksi</div>
        </div>
    </div>

    <div class="analytics-grid">
        <div class="panel">
            <div class="panel-head">
                <div>
                    <div class="panel-title">Laporan Ringkas</div>
                    <div class="panel-subtitle" id="reportScope">Semua transaksi</div>
                </div>
            </div>
            <div class="report-grid">
                <div class="report-item"><div class="report-label">Jumlah Transaksi</div><div class="report-value" id="reportCount">0</div><div class="report-note" id="reportCountNote">Data yang tampil</div></div>
                <div class="report-item"><div class="report-label">Pengeluaran Terbesar</div><div class="report-value expense-text" id="reportBiggestExpense">Rp 0</div><div class="report-note" id="reportBiggestNote">Belum ada pengeluaran</div></div>
                <div class="report-item"><div class="report-label">Kategori Terboros</div><div class="report-value" id="reportTopCategory">-</div><div class="report-note" id="reportTopCategoryNote">Berdasarkan pengeluaran</div></div>
                <div class="report-item"><div class="report-label">Rata-rata Pengeluaran</div><div class="report-value expense-text" id="reportAverageExpense">Rp 0</div><div class="report-note">Per transaksi pengeluaran</div></div>
            </div>
        </div>
        <div class="panel">
            <div class="panel-head">
                <div>
                    <div class="panel-title">Grafik Bulanan</div>
                    <div class="panel-subtitle">Pemasukan vs pengeluaran</div>
                </div>
            </div>
            <div class="bar-list" id="monthlyChart"></div>
        </div>
    </div>

    <div class="analytics-grid">
        <div class="panel">
            <div class="panel-head">
                <div>
                    <div class="panel-title">Pengeluaran per Kategori</div>
                    <div class="panel-subtitle">Supaya kelihatan uang paling banyak habis ke mana</div>
                </div>
            </div>
            <div class="bar-list" id="categoryChart"></div>
        </div>
        <div class="panel">
            <div class="panel-head">
                <div>
                    <div class="panel-title">Insight Otomatis</div>
                    <div class="panel-subtitle">Analisis singkat dari data yang sedang tampil</div>
                </div>
            </div>
            <div id="insightBox" class="report-item" style="min-height:210px;line-height:1.8;color:#d1d5db;"></div>
        </div>
    </div>

    @if(session('success'))<div class="alert-success">{{ session('success') }}</div>@endif
    @if($errors->any())
        <div class="alert-error"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <div class="form-box">
        <form action="{{ route('transaksi.store') }}" method="POST" id="formTransaksi">
            @csrf
            <div class="form-grid">
                <div class="date-wrapper" id="dateWrapper">
                    <span class="date-icon" id="dateIconBtn">📅</span>
                    <input type="text" id="tanggal" name="tanggal" placeholder="Pilih tanggal" required readonly value="{{ old('tanggal') }}">
                </div>
                <div class="kat-wrap" id="kategoriInputWrap">
                    <select name="kategori" id="kategoriInput">
                        <option value="">Kategori</option>
                        @foreach($kategoriOptions as $kategori)
                            <option value="{{ $kategori }}" {{ old('kategori') === $kategori ? 'selected' : '' }}>{{ $kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <input type="text" name="keterangan" placeholder="Keterangan" required maxlength="255" value="{{ old('keterangan') }}">
                <input type="number" name="pemasukan" placeholder="Pemasukan" min="0" step="1" value="{{ old('pemasukan') }}">
                <input type="number" name="pengeluaran" placeholder="Pengeluaran" min="0" step="1" value="{{ old('pengeluaran') }}">
                <button class="save-btn" type="submit" id="btnSimpan">Simpan</button>
            </div>
        </form>
    </div>

    <div style="display:flex;gap:14px;flex-wrap:wrap;margin-bottom:35px;">
        <form action="{{ route('transaksi.destroyAll') }}" method="POST" onsubmit="return confirm('⚠️ Yakin mau hapus SEMUA riwayat?\nData tidak bisa dikembalikan!')">
            @csrf @method('DELETE')
            <button type="submit" class="delete-btn">Hapus Riwayat</button>
        </form>
        <a href="{{ route('transaksi.export') }}" class="export-link"><button type="button" class="export-btn">Export Excel</button></a>
    </div>

    <div class="filter-section">
        <div class="filter-label">⌁ Filter & Cari Transaksi</div>
        <div class="filter-grid">
            <div class="search-wrapper"><span class="search-icon">⌕</span><input type="text" id="searchInput" placeholder="Cari keterangan transaksi..." autocomplete="off"></div>
            <select id="filterBulan">
                <option value="">Semua Bulan</option>
                <option value="01">Januari</option><option value="02">Februari</option><option value="03">Maret</option><option value="04">April</option><option value="05">Mei</option><option value="06">Juni</option><option value="07">Juli</option><option value="08">Agustus</option><option value="09">September</option><option value="10">Oktober</option><option value="11">November</option><option value="12">Desember</option>
            </select>
            <select id="filterTahun">
                <option value="">Semua Tahun</option>
                @foreach($years as $year)<option value="{{ $year }}">{{ $year }}</option>@endforeach
            </select>
            <div class="kat-wrap size-sm" id="filterKategoriWrap">
                <select id="filterKategori">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriOptions as $kategori)<option value="{{ $kategori }}">{{ $kategori }}</option>@endforeach
                </select>
            </div>
            <button class="reset-filter-btn" type="button" onclick="resetFilters()">✕ Reset</button>
        </div>
        <div style="margin-top:18px;">
            <div class="month-tabs-label">Pilih cepat bulan:</div>
            <div class="month-tabs" id="monthTabs">
                <div class="month-tab active" data-bulan="" onclick="setMonthTab(this)">Semua</div>
                <div class="month-tab" data-bulan="01" onclick="setMonthTab(this)">Jan</div><div class="month-tab" data-bulan="02" onclick="setMonthTab(this)">Feb</div><div class="month-tab" data-bulan="03" onclick="setMonthTab(this)">Mar</div><div class="month-tab" data-bulan="04" onclick="setMonthTab(this)">Apr</div><div class="month-tab" data-bulan="05" onclick="setMonthTab(this)">Mei</div><div class="month-tab" data-bulan="06" onclick="setMonthTab(this)">Jun</div><div class="month-tab" data-bulan="07" onclick="setMonthTab(this)">Jul</div><div class="month-tab" data-bulan="08" onclick="setMonthTab(this)">Agu</div><div class="month-tab" data-bulan="09" onclick="setMonthTab(this)">Sep</div><div class="month-tab" data-bulan="10" onclick="setMonthTab(this)">Okt</div><div class="month-tab" data-bulan="11" onclick="setMonthTab(this)">Nov</div><div class="month-tab" data-bulan="12" onclick="setMonthTab(this)">Des</div>
            </div>
        </div>
        <div style="margin-top:14px;">
            <div class="month-tabs-label">Pilih cepat tahun:</div>
            <div class="year-tabs" id="yearTabs">
                <div class="year-tab active" data-tahun="" onclick="setYearTab(this)">Semua</div>
                @foreach($years as $year)<div class="year-tab" data-tahun="{{ $year }}" onclick="setYearTab(this)">{{ $year }}</div>@endforeach
            </div>
        </div>
        <div class="result-count" id="resultCount">Menampilkan <span id="countShown">{{ $transaksis->count() }}</span> dari {{ $transaksis->count() }} transaksi</div>
    </div>

    <div class="table-box">
        <table id="mainTable">
            <thead>
                <tr><th>Tanggal</th><th>Kategori</th><th>Keterangan</th><th>Pemasukan</th><th>Pengeluaran</th><th>Saldo</th><th>Aksi</th></tr>
            </thead>
            <tbody id="tableBody">
            @forelse($transaksis as $transaksi)
                @php
                    $tanggal = \Carbon\Carbon::parse($transaksi->tanggal);
                    $kategoriRow = (string) ($transaksi->kategori ?? 'Lainnya');
                @endphp
                <tr class="transaksi-row"
                    data-id="{{ $transaksi->id }}"
                    data-tanggal="{{ $tanggal->format('Y-m-d') }}"
                    data-bulan="{{ $tanggal->format('m') }}"
                    data-tahun="{{ $tanggal->year }}"
                    data-kategori="{{ e($kategoriRow) }}"
                    data-keterangan="{{ e(strtolower($transaksi->keterangan)) }}"
                    data-pemasukan="{{ (float) $transaksi->pemasukan }}"
                    data-pengeluaran="{{ (float) $transaksi->pengeluaran }}"
                    data-saldo="{{ (float) $transaksi->saldo }}">
                    <td>{{ $tanggal->format('d/m/Y') }}</td>
                    <td><span class="category-badge">{{ $kategoriRow }}</span></td>
                    <td class="keterangan-cell">{{ $transaksi->keterangan }}</td>
                    <td class="income-text">Rp {{ number_format($transaksi->pemasukan,0,',','.') }}</td>
                    <td class="expense-text">Rp {{ number_format($transaksi->pengeluaran,0,',','.') }}</td>
                    <td class="balance-text">{{ ($transaksi->saldo ?? 0) < 0 ? '-Rp ' : 'Rp ' }}{{ number_format(abs($transaksi->saldo ?? 0),0,',','.') }}</td>
                    <td>
                        <div class="row-actions">
                            <button type="button" class="btn-edit-row"
                                data-id="{{ $transaksi->id }}"
                                data-tanggal="{{ $tanggal->format('Y-m-d') }}"
                                data-kategori="{{ e($kategoriRow) }}"
                                data-keterangan="{{ e($transaksi->keterangan) }}"
                                data-pemasukan="{{ (float) $transaksi->pemasukan }}"
                                data-pengeluaran="{{ (float) $transaksi->pengeluaran }}"
                                onclick="openEditModalFromButton(this)">✏️ Edit</button>
                            <form action="{{ route('transaksi.destroy', $transaksi->id) }}" method="POST" onsubmit="return confirm('Hapus transaksi ini?')" style="margin:0;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-del-row">🗑 Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr id="emptyRow"><td colspan="7" class="empty-state">Belum ada transaksi. Yuk catat pengeluaran pertama kamu!</td></tr>
            @endforelse
            </tbody>
        </table>
        <div id="filterEmptyState" style="display:none;text-align:center;padding:40px 20px;color:#6b7280;font-size:16px;">🔍 Tidak ada transaksi yang cocok dengan filter ini.</div>
    </div>

    <footer>© 2026 Catatan Keuangan pakcik</footer>
</div>

<div class="modal-overlay" id="editModal">
    <div class="modal-box">
        <button class="modal-close" onclick="closeEditModal()" title="Tutup">✕</button>
        <div class="modal-title">Edit Transaksi</div>
        <form id="formEdit" method="POST">
            @csrf @method('PUT')
            <div class="modal-grid">
                <div class="modal-field full"><label class="modal-label">Tanggal</label><div class="modal-date-wrapper" id="modalDateWrapper"><span class="modal-date-icon" id="modalDateIconBtn">📅</span><input type="text" id="editTanggal" name="tanggal" class="modal-input" placeholder="Pilih tanggal" required readonly></div></div>
                <div class="modal-field"><label class="modal-label">Kategori</label><div class="kat-wrap size-md" id="editKategoriWrap"><select id="editKategori" name="kategori" class="modal-input">@foreach($kategoriOptions as $kategori)<option value="{{ $kategori }}">{{ $kategori }}</option>@endforeach</select></div></div>
                <div class="modal-field"><label class="modal-label">Keterangan</label><input type="text" id="editKeterangan" name="keterangan" class="modal-input" placeholder="Keterangan" maxlength="255" required></div>
                <div class="modal-field"><label class="modal-label">Pemasukan</label><input type="number" id="editPemasukan" name="pemasukan" class="modal-input" placeholder="0" min="0" step="1"></div>
                <div class="modal-field"><label class="modal-label">Pengeluaran</label><input type="number" id="editPengeluaran" name="pengeluaran" class="modal-input" placeholder="0" min="0" step="1"></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn-cancel" onclick="closeEditModal()">Batal</button><button type="submit" class="btn-update" id="btnUpdate">Simpan Perubahan</button></div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script>
    const transaksiData = {!! \Illuminate\Support\Js::from($transaksiDataRealtime) !!};
    const totalRows = transaksiData.length;
    const FILTER_KEY = 'keuangan_filter_v4_analytics';
    const BULAN_LABEL = {'01':'Januari','02':'Februari','03':'Maret','04':'April','05':'Mei','06':'Juni','07':'Juli','08':'Agustus','09':'September','10':'Oktober','11':'November','12':'Desember'};
    const BULAN_SHORT = {'01':'Jan','02':'Feb','03':'Mar','04':'Apr','05':'Mei','06':'Jun','07':'Jul','08':'Agu','09':'Sep','10':'Okt','11':'Nov','12':'Des'};
    const getEl = (id) => document.getElementById(id);

    function safeFlatpickr(selector, options) {
        if (typeof flatpickr === 'function') return flatpickr(selector, options);
        const el = document.querySelector(selector);
        if (el) { el.removeAttribute('readonly'); el.type = 'date'; }
        return { isOpen:false, open:function(){}, close:function(){}, setDate:function(v){ if(el) el.value = v; } };
    }

    const fp = safeFlatpickr('#tanggal', {
        locale:'id',dateFormat:'Y-m-d',position:'below',monthSelectorType:'static',maxDate:'today',
        onOpen:function(){ getEl('dateWrapper')?.classList.add('active') },
        onClose:function(){ getEl('dateWrapper')?.classList.remove('active') }
    });
    getEl('dateIconBtn')?.addEventListener('click', function(e){e.stopPropagation(); fp.isOpen ? fp.close() : fp.open();});

    const fpEdit = safeFlatpickr('#editTanggal', {
        locale:'id',dateFormat:'Y-m-d',position:'below',monthSelectorType:'static',maxDate:'today',
        onOpen:function(){ getEl('modalDateWrapper')?.classList.add('active') },
        onClose:function(){ getEl('modalDateWrapper')?.classList.remove('active') }
    });
    getEl('modalDateIconBtn')?.addEventListener('click', function(e){e.stopPropagation(); fpEdit.isOpen ? fpEdit.close() : fpEdit.open();});

    function formatRupiah(n) {
        n = Number(n) || 0;
        const sign = n < 0 ? '-Rp ' : 'Rp ';
        return sign + Math.abs(Math.round(n)).toLocaleString('id-ID');
    }
    function escapeHtml(str) {
        return String(str ?? '').replace(/[&<>'"]/g, function(c){return {'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#39;','"':'&quot;'}[c];});
    }
    function escapeRegex(str) { return String(str).replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); }

    function getFilterValue() {
        return {
            search:(getEl('searchInput')?.value || '').toLowerCase().trim(),
            bulan:(getEl('filterBulan')?.value || '').trim(),
            tahun:(getEl('filterTahun')?.value || '').trim(),
            kategori:(getEl('filterKategori')?.value || '').trim()
        };
    }
    function matchItem(item, f) {
        return (!f.bulan || item.bulan === f.bulan) && (!f.tahun || item.tahun === f.tahun) && (!f.kategori || item.kategori === f.kategori) && (!f.search || item.keterangan.includes(f.search));
    }
    function matchedItems(f) { return transaksiData.filter(item => matchItem(item, f)); }

    function saveFilterState() { try { localStorage.setItem(FILTER_KEY, JSON.stringify(getFilterValue())); } catch(e) {} }
    function restoreFilterState() {
        try {
            const saved = localStorage.getItem(FILTER_KEY); if (!saved) return;
            const f = JSON.parse(saved);
            if (getEl('filterBulan')) getEl('filterBulan').value = f.bulan || '';
            if (getEl('filterTahun')) getEl('filterTahun').value = f.tahun || '';
            if (getEl('filterKategori')) getEl('filterKategori').value = f.kategori || '';
            if (getEl('searchInput')) getEl('searchInput').value = f.search || '';
            if (_katFilter) _katFilter.setValue(f.kategori || '');
            syncMonthTab(f.bulan || ''); syncYearTab(f.tahun || '');
        } catch(e) {}
    }

    function updateBadge(f) {
        const parts = [];
        if (f.bulan) parts.push(BULAN_LABEL[f.bulan] || f.bulan);
        if (f.tahun) parts.push(f.tahun);
        if (f.kategori) parts.push(f.kategori);
        if (f.search) parts.push('Cari: ' + f.search);
        const badge = getEl('activeFilterBadge'); const text = getEl('activeBadgeText');
        if (!badge || !text) return;
        if (parts.length) { text.textContent = 'Menampilkan: ' + parts.join(' • '); badge.classList.add('visible'); }
        else { badge.classList.remove('visible'); }
    }

    function updateCards(items) {
        const pemasukan = items.reduce((s,i)=>s + (Number(i.pemasukan)||0),0);
        const pengeluaran = items.reduce((s,i)=>s + (Number(i.pengeluaran)||0),0);
        const saldo = pemasukan - pengeluaran;
        const nodes = [getEl('cardPemasukan'), getEl('cardPengeluaran'), getEl('cardSaldo')];
        if (!nodes[0] || !nodes[1] || !nodes[2]) return;
        nodes[0].textContent = formatRupiah(pemasukan);
        nodes[1].textContent = formatRupiah(pengeluaran);
        nodes[2].textContent = formatRupiah(saldo);
        ['cardIncomeBox','cardExpenseBox','cardBalanceBox'].forEach(id => getEl(id)?.classList.toggle('empty-state', items.length === 0));
        nodes.forEach(el => { el.classList.remove('pop'); void el.offsetWidth; el.classList.add('pop'); });
    }

    function updateReport(items, f) {
        const income = items.reduce((s,i)=>s + (Number(i.pemasukan)||0),0);
        const expenses = items.filter(i => (Number(i.pengeluaran)||0) > 0);
        const totalExpense = expenses.reduce((s,i)=>s + (Number(i.pengeluaran)||0),0);
        const biggest = expenses.slice().sort((a,b)=>Number(b.pengeluaran)-Number(a.pengeluaran))[0];
        const catTotals = {};
        expenses.forEach(i => { catTotals[i.kategori] = (catTotals[i.kategori] || 0) + Number(i.pengeluaran || 0); });
        const topCat = Object.entries(catTotals).sort((a,b)=>b[1]-a[1])[0];
        const avgExpense = expenses.length ? totalExpense / expenses.length : 0;
        const scope = [];
        if (f.bulan) scope.push(BULAN_LABEL[f.bulan]);
        if (f.tahun) scope.push(f.tahun);
        if (f.kategori) scope.push(f.kategori);
        getEl('reportScope').textContent = scope.length ? scope.join(' • ') : 'Semua transaksi';
        getEl('reportCount').textContent = items.length;
        getEl('reportCountNote').textContent = items.length ? 'Data yang sedang tampil' : 'Belum ada data';
        getEl('reportBiggestExpense').textContent = biggest ? formatRupiah(biggest.pengeluaran) : 'Rp 0';
        getEl('reportBiggestNote').textContent = biggest ? biggest.tanggal.split('-').reverse().join('/') + ' • ' + biggest.kategori : 'Belum ada pengeluaran';
        getEl('reportTopCategory').textContent = topCat ? topCat[0] : '-';
        getEl('reportTopCategoryNote').textContent = topCat ? formatRupiah(topCat[1]) + ' total pengeluaran' : 'Belum ada pengeluaran';
        getEl('reportAverageExpense').textContent = formatRupiah(avgExpense);

        const insight = getEl('insightBox');
        if (!insight) return;
        if (!items.length) {
            insight.innerHTML = 'Belum ada transaksi pada filter ini. Kalau pilih bulan kosong seperti April, ringkasan dan grafik akan otomatis nol tanpa perlu refresh.';
            return;
        }
        const saldo = income - totalExpense;
        const ratio = income > 0 ? Math.round((totalExpense / income) * 100) : null;
        let html = '<b style="color:#e9d5ff">Kesimpulan:</b><br>';
        html += 'Saldo periode ini <b style="color:' + (saldo >= 0 ? '#22c55e' : '#ff4d4d') + '">' + formatRupiah(saldo) + '</b>.<br>';
        if (ratio !== null) html += 'Pengeluaran memakai sekitar <b>' + ratio + '%</b> dari pemasukan.<br>';
        if (topCat) html += 'Kategori paling besar adalah <b>' + escapeHtml(topCat[0]) + '</b> sebesar <b>' + formatRupiah(topCat[1]) + '</b>.<br>';
        if (biggest) html += 'Transaksi pengeluaran terbesar: <b>' + formatRupiah(biggest.pengeluaran) + '</b> pada <b>' + biggest.tanggal.split('-').reverse().join('/') + '</b>.';
        insight.innerHTML = html;
    }

    function renderBar(containerId, rows, type) {
        const el = getEl(containerId); if (!el) return;
        if (!rows.length) { el.innerHTML = '<div class="empty-chart">Belum ada data untuk grafik ini.</div>'; return; }
        const maxValue = Math.max(...rows.map(r => Math.abs(Number(r.value)||0)), 1);
        el.innerHTML = rows.map(r => {
            const pct = Math.max(4, Math.round((Math.abs(Number(r.value)||0) / maxValue) * 100));
            const cls = type === 'income' ? 'income-bar' : (type === 'expense' ? 'expense-bar' : '');
            return '<div class="bar-row"><div class="bar-label" title="' + escapeHtml(r.label) + '">' + escapeHtml(r.label) + '</div><div class="bar-track"><div class="bar-fill ' + cls + '" style="width:' + pct + '%"></div></div><div class="bar-value">' + formatRupiah(r.value) + '</div></div>';
        }).join('');
    }

    function updateCharts(items, f) {
        const monthly = {};
        items.forEach(i => {
            const key = i.tahun + '-' + i.bulan;
            if (!monthly[key]) monthly[key] = {label:(BULAN_SHORT[i.bulan] || i.bulan) + ' ' + i.tahun, income:0, expense:0, sort:key};
            monthly[key].income += Number(i.pemasukan)||0;
            monthly[key].expense += Number(i.pengeluaran)||0;
        });
        const monthlyRows = Object.values(monthly).sort((a,b)=>b.sort.localeCompare(a.sort)).slice(0, 8);
        const comboRows = [];
        monthlyRows.forEach(m => {
            comboRows.push({label:m.label + ' Masuk', value:m.income, kind:'income'});
            comboRows.push({label:m.label + ' Keluar', value:m.expense, kind:'expense'});
        });
        const monthlyEl = getEl('monthlyChart');
        if (!comboRows.length) monthlyEl.innerHTML = '<div class="empty-chart">Belum ada data bulanan.</div>';
        else {
            const maxValue = Math.max(...comboRows.map(r=>Math.abs(r.value)),1);
            monthlyEl.innerHTML = comboRows.map(r => {
                const pct = Math.max(4, Math.round((Math.abs(r.value) / maxValue) * 100));
                const cls = r.kind === 'income' ? 'income-bar' : 'expense-bar';
                return '<div class="bar-row"><div class="bar-label" title="' + escapeHtml(r.label) + '">' + escapeHtml(r.label) + '</div><div class="bar-track"><div class="bar-fill ' + cls + '" style="width:' + pct + '%"></div></div><div class="bar-value">' + formatRupiah(r.value) + '</div></div>';
            }).join('');
        }

        const category = {};
        items.forEach(i => { if (Number(i.pengeluaran) > 0) category[i.kategori] = (category[i.kategori] || 0) + Number(i.pengeluaran || 0); });
        const catRows = Object.entries(category).sort((a,b)=>b[1]-a[1]).slice(0, 8).map(([label,value]) => ({label,value}));
        renderBar('categoryChart', catRows, 'expense');
    }

    function getMonthGroupLabel(row) {
        const bulan = row.getAttribute('data-bulan') || '';
        const tahun = row.getAttribute('data-tahun') || '';
        return (BULAN_LABEL[bulan] || bulan) + (tahun ? ' ' + tahun : '');
    }
    function createMonthGroupRow(label) {
        const row = document.createElement('tr'); row.className = 'month-group-row';
        row.innerHTML = '<td colspan="7"><div class="month-group-title"><span class="month-group-pill">📅 ' + escapeHtml(label) + '</span></div></td>';
        return row;
    }
    function updateKeteranganHighlight(row, search) {
        const cell = row.querySelector('.keterangan-cell'); if (!cell) return;
        const original = cell.textContent;
        if (!search) { cell.textContent = original; return; }
        const regex = new RegExp('(' + escapeRegex(search) + ')', 'gi');
        cell.innerHTML = original.replace(regex, '<span class="highlight">$1</span>');
    }
    function updateTableRows(f) {
        const body = getEl('tableBody');
        const rows = Array.from(document.querySelectorAll('.transaksi-row'));
        document.querySelectorAll('.month-group-row').forEach(r => r.remove());
        let visible = [];
        rows.forEach((row,index) => {
            row.dataset.originalIndex = row.dataset.originalIndex || String(index);
            const show = (!f.bulan || row.dataset.bulan === f.bulan) && (!f.tahun || row.dataset.tahun === f.tahun) && (!f.kategori || row.dataset.kategori === f.kategori) && (!f.search || (row.dataset.keterangan || '').includes(f.search));
            row.style.display = show ? '' : 'none';
            if (show) visible.push(row);
        });
        visible.sort((a,b) => {
            const dateCompare = (b.dataset.tanggal || '').localeCompare(a.dataset.tanggal || '');
            if (dateCompare !== 0) return dateCompare;
            return Number(b.dataset.id || 0) - Number(a.dataset.id || 0);
        });
        let activeGroup = '';
        visible.forEach(row => {
            updateKeteranganHighlight(row, f.search);
            if (body && !f.bulan) {
                const group = (row.dataset.tahun || '') + '-' + (row.dataset.bulan || '');
                if (group !== activeGroup) { activeGroup = group; body.appendChild(createMonthGroupRow(getMonthGroupLabel(row))); }
            }
            body?.appendChild(row);
        });
        rows.forEach(row => { if (row.style.display === 'none') body?.appendChild(row); });
        return visible.length;
    }

    function applyFilters() {
        const f = getFilterValue(); saveFilterState(); updateBadge(f);
        const items = matchedItems(f);
        const shown = updateTableRows(f);
        const resultCount = getEl('resultCount');
        if (resultCount) resultCount.innerHTML = 'Menampilkan <span>' + shown + '</span> dari ' + totalRows + ' transaksi';
        const empty = getEl('filterEmptyState'); const body = getEl('tableBody');
        if (empty && body) { empty.style.display = (shown === 0 && totalRows > 0) ? 'block' : 'none'; body.style.display = (shown === 0 && totalRows > 0) ? 'none' : ''; }
        updateCards(items); updateReport(items, f); updateCharts(items, f);
    }

    function syncMonthTab(value) { document.querySelectorAll('.month-tab').forEach(t => t.classList.toggle('active', t.dataset.bulan === value)); }
    function syncYearTab(value) { document.querySelectorAll('.year-tab').forEach(t => t.classList.toggle('active', t.dataset.tahun === value)); }
    function resetFilters() {
        if (getEl('searchInput')) getEl('searchInput').value = '';
        if (getEl('filterBulan')) getEl('filterBulan').value = '';
        if (getEl('filterTahun')) getEl('filterTahun').value = '';
        if (getEl('filterKategori')) getEl('filterKategori').value = '';
        if (_katFilter) _katFilter.setValue('');
        syncMonthTab(''); syncYearTab(''); try { localStorage.removeItem(FILTER_KEY); } catch(e) {} applyFilters();
    }
    function setMonthTab(el) { const value = el.dataset.bulan || ''; if (getEl('filterBulan')) getEl('filterBulan').value = value; syncMonthTab(value); applyFilters(); }
    function setYearTab(el) { const value = el.dataset.tahun || ''; if (getEl('filterTahun')) getEl('filterTahun').value = value; syncYearTab(value); applyFilters(); }

    ['searchInput','filterBulan','filterTahun','filterKategori'].forEach(id => getEl(id)?.addEventListener(id === 'searchInput' ? 'input' : 'change', function(){ if(id==='filterBulan') syncMonthTab(this.value || ''); if(id==='filterTahun') syncYearTab(this.value || ''); applyFilters(); }));

    function validateNominal(tanggal, keterangan, pemasukan, pengeluaran) {
        if (!tanggal) { alert('Pilih tanggal dulu ya!'); return false; }
        if (!keterangan.trim()) { alert('Keterangan tidak boleh kosong!'); return false; }
        if (pemasukan === 0 && pengeluaran === 0) { alert('Isi minimal pemasukan atau pengeluaran!'); return false; }
        if (pemasukan < 0 || pengeluaran < 0) { alert('Nominal tidak boleh minus!'); return false; }
        return true;
    }
    getEl('formTransaksi')?.addEventListener('submit', function(e){
        const pemasukan = parseFloat(document.querySelector('[name=pemasukan]').value) || 0;
        const pengeluaran = parseFloat(document.querySelector('[name=pengeluaran]').value) || 0;
        const tanggal = document.querySelector('[name=tanggal]').value;
        const keterangan = document.querySelector('[name=keterangan]').value;
        if (!validateNominal(tanggal,keterangan,pemasukan,pengeluaran)) { e.preventDefault(); return; }
        const btn = getEl('btnSimpan'); if (btn) { btn.disabled = true; btn.textContent = 'Menyimpan...'; }
    });

    function openEditModalFromButton(btn) {
        const form = getEl('formEdit'); if (!form) return;
        form.action = '/transaksi/' + btn.dataset.id;
        fpEdit.setDate(btn.dataset.tanggal || '', true);
        const kv = btn.dataset.kategori || 'Lainnya';
        getEl('editKategori').value = kv;
        if (_katEdit) _katEdit.setValue(kv);
        getEl('editKeterangan').value = btn.dataset.keterangan || '';
        getEl('editPemasukan').value = Number(btn.dataset.pemasukan || 0) > 0 ? btn.dataset.pemasukan : '';
        getEl('editPengeluaran').value = Number(btn.dataset.pengeluaran || 0) > 0 ? btn.dataset.pengeluaran : '';
        const updateBtn = getEl('btnUpdate'); if (updateBtn) { updateBtn.disabled = false; updateBtn.textContent = 'Simpan Perubahan'; }
        getEl('editModal')?.classList.add('open'); document.body.style.overflow = 'hidden';
    }
    function closeEditModal() { getEl('editModal')?.classList.remove('open'); document.body.style.overflow = ''; }
    getEl('editModal')?.addEventListener('click', function(e){ if (e.target === this) closeEditModal(); });
    document.addEventListener('keydown', function(e){ if (e.key === 'Escape') closeEditModal(); });
    getEl('formEdit')?.addEventListener('submit', function(e){
        const pemasukan = parseFloat(getEl('editPemasukan').value) || 0;
        const pengeluaran = parseFloat(getEl('editPengeluaran').value) || 0;
        if (!validateNominal(getEl('editTanggal').value, getEl('editKeterangan').value, pemasukan, pengeluaran)) { e.preventDefault(); return; }
        const btn = getEl('btnUpdate'); if (btn) { btn.disabled = true; btn.textContent = 'Menyimpan...'; }
    });

    // ===== CUSTOM KATEGORI DROPDOWN ENGINE =====
    var _katForm = null, _katFilter = null, _katEdit = null;
    const _KAT_LS = 'keuangan_custom_kategori_v2';
    const _katReg = [];

    function _loadCustKat() { try { return JSON.parse(localStorage.getItem(_KAT_LS) || '[]'); } catch(e) { return []; } }
    function _saveCustKat(arr) { try { localStorage.setItem(_KAT_LS, JSON.stringify(arr)); } catch(e) {} }

    function buildKatSelect(cfg) {
        var wrap = getEl(cfg.wrapId), sel = getEl(cfg.selId);
        if (!wrap || !sel) return null;

        // Read default options from native select
        var defOpts = Array.from(sel.options).map(function(o){ return {v:o.value,l:o.text}; });
        sel.style.display = 'none';

        // Build DOM
        var trigger = document.createElement('div');
        trigger.className = 'kat-trigger' + (defOpts[0] && !defOpts[0].v ? ' plch' : '');

        var menu = document.createElement('div');
        menu.className = 'kat-menu';
        menu.innerHTML =
            '<div class="kat-opts"></div>' +
            (cfg.noAdd ? '' :
            '<div class="kat-line"></div>' +
            '<div class="kat-footer">' +
              '<div class="kat-add-row">＋&nbsp; Tambah Kategori</div>' +
              '<div class="kat-add-area">' +
                '<div class="kat-new-row">' +
                  '<input class="kat-new-inp" type="text" placeholder="Nama kategori baru..." maxlength="30">' +
                  '<button class="kat-new-btn" type="button">Tambah</button>' +
                '</div>' +
              '</div>' +
            '</div>');

        wrap.appendChild(trigger);
        wrap.appendChild(menu);

        var optsEl = menu.querySelector('.kat-opts');
        var addRow  = cfg.noAdd ? null : menu.querySelector('.kat-add-row');
        var addArea = cfg.noAdd ? null : menu.querySelector('.kat-add-area');
        var newInp  = cfg.noAdd ? null : menu.querySelector('.kat-new-inp');
        var newBtn  = cfg.noAdd ? null : menu.querySelector('.kat-new-btn');

        var curVal = sel.value || '';
        var isOpen = false;

        function allOpts() {
            var custom = _loadCustKat();
            var defVals = defOpts.filter(function(o){ return o.v; }).map(function(o){ return o.v; });
            var extra = custom.filter(function(c){ return defVals.indexOf(c) === -1; });
            return defOpts.concat(extra.map(function(c){ return {v:c,l:c}; }));
        }

        function renderOpts() {
            optsEl.innerHTML = '';
            allOpts().forEach(function(opt) {
                var d = document.createElement('div');
                d.className = 'kat-opt' + (!opt.v ? ' plch-opt' : '') + (opt.v === curVal ? ' sel' : '');
                d.textContent = opt.l;
                (function(v){ d.addEventListener('click', function(){ pick(v); }); })(opt.v);
                optsEl.appendChild(d);
            });
            // Scroll selected item into view
            var sel = optsEl.querySelector('.kat-opt.sel');
            if (sel) sel.scrollIntoView({block:'nearest'});
        }

        function setTrigger() {
            if (!curVal) {
                trigger.textContent = cfg.placeholder;
                trigger.className = 'kat-trigger plch';
            } else {
                var found = allOpts().find(function(o){ return o.v === curVal; });
                trigger.textContent = found ? found.l : curVal;
                trigger.className = 'kat-trigger';
            }
        }

        function pick(val) {
            curVal = val;
            // Ensure native select has this option (for custom categories)
            if (val && !sel.querySelector('option[value="' + val.replace(/"/g, '&quot;') + '"]')) {
                var opt = document.createElement('option');
                opt.value = val;
                opt.textContent = val;
                sel.appendChild(opt);
            }
            sel.value = val;
            sel.dispatchEvent(new Event('change', {bubbles:true}));
            setTrigger();
            renderOpts();
            closeMenu();
        }

        function openMenu() {
            isOpen = true;
            // Position menu using fixed coords (escapes backdrop-filter stacking context)
            var rect = trigger.getBoundingClientRect();
            menu.style.top   = (rect.bottom + 6) + 'px';
            menu.style.left  = rect.left + 'px';
            menu.style.width = rect.width + 'px';
            menu.classList.add('show');
            trigger.classList.add('open');
            if (addArea) addArea.classList.remove('show');
            if (newInp) newInp.value = '';
        }

        function closeMenu() {
            isOpen = false;
            menu.classList.remove('show');
            trigger.classList.remove('open');
        }

        function reposition() {
            if (!isOpen) return;
            var rect = trigger.getBoundingClientRect();
            menu.style.top   = (rect.bottom + 6) + 'px';
            menu.style.left  = rect.left + 'px';
            menu.style.width = rect.width + 'px';
        }

        trigger.addEventListener('click', function(e) {
            e.stopPropagation();
            if (isOpen) closeMenu();
            else { _katReg.forEach(function(i){ if(i!==inst) i.close(); }); openMenu(); }
        });

        document.addEventListener('click', function(e) {
            if (!wrap.contains(e.target) && !menu.contains(e.target)) closeMenu();
        });

        window.addEventListener('scroll', reposition, true);
        window.addEventListener('resize', reposition);

        if (addRow) addRow.addEventListener('click', function(e) {
            e.stopPropagation();
            if (addArea) addArea.classList.toggle('show');
            if (addArea && addArea.classList.contains('show') && newInp) { newInp.value = ''; newInp.focus(); }
        });

        function doAdd() {
            var name = (newInp.value || '').trim();
            if (!name) { newInp.focus(); return; }
            var custom = _loadCustKat();
            var allv = allOpts().map(function(o){ return (o.v||'').toLowerCase(); });
            if (allv.indexOf(name.toLowerCase()) === -1) {
                custom.push(name);
                _saveCustKat(custom);
                _katReg.forEach(function(i){ i.refresh(); });
            }
            pick(name);
            if (newInp) newInp.value = '';
            if (addArea) addArea.classList.remove('show');
        }

        if (newBtn) newBtn.addEventListener('click', function(e){ e.stopPropagation(); doAdd(); });
        if (newInp) newInp.addEventListener('keydown', function(e){ if(e.key==='Enter'){e.preventDefault();doAdd();} e.stopPropagation(); });
        if (newInp) newInp.addEventListener('click', function(e){ e.stopPropagation(); });

        var inst = {
            getValue: function(){ return curVal; },
            setValue: function(v){
                curVal = v;
                // Ensure native select has this option (for custom categories from DB)
                if (v && !sel.querySelector('option[value="' + v.replace(/"/g,'&quot;') + '"]')) {
                    var opt = document.createElement('option');
                    opt.value = v; opt.textContent = v;
                    sel.appendChild(opt);
                    // Also save to custom kat if not already there
                    var custom = _loadCustKat();
                    var defVals = defOpts.filter(function(o){return o.v;}).map(function(o){return o.v.toLowerCase();});
                    if (defVals.indexOf(v.toLowerCase()) === -1 && custom.map(function(c){return c.toLowerCase();}).indexOf(v.toLowerCase()) === -1) {
                        custom.push(v); _saveCustKat(custom);
                    }
                }
                sel.value = v; setTrigger(); renderOpts();
            },
            refresh: function(){ renderOpts(); },
            close: closeMenu,
        };

        _katReg.push(inst);
        curVal = sel.value || '';
        setTrigger();
        renderOpts();
        return inst;
    }

    // Init all 3 dropdown instances
    _katForm   = buildKatSelect({wrapId:'kategoriInputWrap', selId:'kategoriInput',   placeholder:'Kategori'});
    _katFilter = buildKatSelect({wrapId:'filterKategoriWrap',selId:'filterKategori',  placeholder:'Semua Kategori', noAdd:true});
    _katEdit   = buildKatSelect({wrapId:'editKategoriWrap',  selId:'editKategori',    placeholder:'Pilih Kategori'});
    // ===== END CUSTOM KATEGORI DROPDOWN =====

    function populateFilterKategori() {
        // Build unique categories from actual transaction data
        var seen = {};
        var cats = [];
        transaksiData.forEach(function(t) {
            var k = t.kategori || 'Lainnya';
            if (!seen[k]) { seen[k] = true; cats.push(k); }
        });
        cats.sort();
        var sel = getEl('filterKategori');
        if (!sel) return;
        // Remove all existing options except the first (Semua Kategori)
        while (sel.options.length > 1) sel.remove(1);
        cats.forEach(function(k) {
            var opt = document.createElement('option');
            opt.value = k; opt.textContent = k;
            sel.appendChild(opt);
        });
        // Sync the custom dropdown display
        if (_katFilter) _katFilter.refresh();
    }

    function initApp() { populateFilterKategori(); restoreFilterState(); applyFilters(); }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initApp); else initApp();
</script>
</body>
</html>