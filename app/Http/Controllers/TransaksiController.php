<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksis = Transaksi::where('user_id', auth()->id())
            ->latest()
            ->get();

        $totalPemasukan = Transaksi::where('user_id', auth()->id())
            ->sum('pemasukan');

        $totalPengeluaran = Transaksi::where('user_id', auth()->id())
            ->sum('pengeluaran');

        $saldoAkhir = Transaksi::where('user_id', auth()->id())
            ->latest()
            ->first()->saldo ?? 0;

        return view('transaksi.index', compact(
            'transaksis',
            'totalPemasukan',
            'totalPengeluaran',
            'saldoAkhir'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pemasukan' => 'nullable|numeric|min:0',
            'pengeluaran' => 'nullable|numeric|min:0',
        ]);

        $saldoTerakhir = Transaksi::where('user_id', auth()->id())
            ->latest()
            ->first()->saldo ?? 0;

        $saldoBaru =
            $saldoTerakhir
            + ($request->pemasukan ?? 0)
            - ($request->pengeluaran ?? 0);

        Transaksi::create([
            'user_id' => auth()->id(),
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'pemasukan' => $request->pemasukan ?? 0,
            'pengeluaran' => $request->pengeluaran ?? 0,
            'saldo' => $saldoBaru
        ]);

        return back();
    }

    public function export()
    {
        $transaksis = Transaksi::where('user_id', auth()->id())->get();

        $totalPemasukan = $transaksis->sum('pemasukan');
        $totalPengeluaran = $transaksis->sum('pengeluaran');
        $saldoAkhir = $transaksis->last()->saldo ?? 0;

        $filename = "catatan_keuangan.xls";

        $headers = [
            "Content-Type" => "application/vnd.ms-excel",
            "Content-Disposition" => "attachment; filename=$filename"
        ];

        $html = '
        <html>
        <head>
        <meta charset="UTF-8">

        <style>
        table {
            border-collapse: collapse;
            width: 70%;
            margin: auto;
            font-family: Arial, sans-serif;
        }

        th {
            background-color: #6C63FF;
            color: white;
            padding: 10px;
            border: 1px solid black;
            text-align: center;
            font-weight: bold;
        }

        td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        .money {
            text-align: center;
        }

        .total {
            font-weight: bold;
            background-color: #f2f2f2;
            text-align: center;
        }
        </style>
        </head>

        <body>

        <h2 style="text-align:center;">Catatan Keuangan</h2>

        <table>
        <tr>
            <th>Tanggal</th>
            <th>Keterangan</th>
            <th>Pemasukan</th>
            <th>Pengeluaran</th>
            <th>Saldo</th>
        </tr>
        ';

        foreach ($transaksis as $t) {

            $html .= '
            <tr>
                <td>' . $t->tanggal . '</td>
                <td>' . $t->keterangan . '</td>
                <td class="money">Rp ' . number_format($t->pemasukan, 0, ',', '.') . '</td>
                <td class="money">Rp ' . number_format($t->pengeluaran, 0, ',', '.') . '</td>
                <td class="money">Rp ' . number_format($t->saldo, 0, ',', '.') . '</td>
            </tr>
            ';
        }

        $html .= '
        
        <tr class="total">
            <td colspan="2">Total Pemasukan</td>
            <td class="money">Rp ' . number_format($totalPemasukan, 0, ',', '.') . '</td>
            <td></td>
            <td></td>
        </tr>

        <tr class="total">
            <td colspan="2">Total Pengeluaran</td>
            <td></td>
            <td class="money">Rp ' . number_format($totalPengeluaran, 0, ',', '.') . '</td>
            <td></td>
        </tr>

        <tr class="total">
            <td colspan="2">Saldo Akhir</td>
            <td></td>
            <td></td>
            <td class="money">Rp ' . number_format($saldoAkhir, 0, ',', '.') . '</td>
        </tr>
        </table>
        </body>
        </html>
        ';

        return response($html, 200, $headers);
    }

    public function hapusSemua()
    {
        Transaksi::where('user_id', auth()->id())->delete();

        return redirect()->back();
    }
}