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

        $totalPemasukan   = $transaksis->sum('pemasukan');
        $totalPengeluaran = $transaksis->sum('pengeluaran');
        $saldoAkhir       = $transaksis->last()->saldo ?? 0;

        return view('transaksi.index', [
            'transaksis'       => $transaksis,
            'totalPemasukan'   => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'saldoAkhir'       => $saldoAkhir,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'     => 'required|date',
            'keterangan'  => 'required|string|max:500',
            'pemasukan'   => 'nullable|numeric|min:0|max:999999999999',
            'pengeluaran' => 'nullable|numeric|min:0|max:999999999999',
        ]);

        $transaksiTerakhir = Transaksi::where('user_id', auth()->id())
            ->latest()
            ->first();

        $saldoTerakhir = $transaksiTerakhir ? $transaksiTerakhir->saldo : 0;

        $saldoBaru = $saldoTerakhir
            + ($request->pemasukan   ?? 0)
            - ($request->pengeluaran ?? 0);

        Transaksi::create([
            'user_id'     => auth()->id(),
            'tanggal'     => $request->tanggal,
            'keterangan'  => $request->keterangan,
            'pemasukan'   => $request->pemasukan   ?? 0,
            'pengeluaran' => $request->pengeluaran ?? 0,
            'saldo'       => $saldoBaru,
        ]);

        return back()->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function destroy(Transaksi $transaksi)
    {
        // Pastikan hanya pemilik yang bisa hapus (authorization)
        if ($transaksi->user_id !== auth()->id()) {
            abort(403);
        }

        $transaksi->delete();

        return back()->with('success', 'Transaksi berhasil dihapus.');
    }

    /**
     * Hapus semua transaksi milik user yang sedang login.
     * Nama method harus sama dengan yang dipanggil di routes/web.php: destroyAll
     */
    public function destroyAll()
    {
        Transaksi::where('user_id', auth()->id())->delete();

        return redirect()->back()->with('success', 'Semua transaksi berhasil dihapus.');
    }

    public function export()
    {
        $transaksis       = Transaksi::where('user_id', auth()->id())->get();
        $totalPemasukan   = $transaksis->sum('pemasukan');
        $totalPengeluaran = $transaksis->sum('pengeluaran');
        $saldoAkhir       = $transaksis->last()->saldo ?? 0;

        $filename = 'catatan_keuangan.xls';

        $headers = [
            'Content-Type'        => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        // ⚠ PENTING: semua data di-escape dengan htmlspecialchars() untuk cegah XSS di file export
        $html = '
        <html>
        <head>
        <meta charset="UTF-8">
        <style>
        table { border-collapse: collapse; width: 70%; margin: auto; font-family: Arial, sans-serif; }
        th    { background-color: #6C63FF; color: white; padding: 10px; border: 1px solid black; text-align: center; font-weight: bold; }
        td    { border: 1px solid black; padding: 8px; text-align: center; }
        .money { text-align: center; }
        .total { font-weight: bold; background-color: #f2f2f2; text-align: center; }
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
        </tr>';

        foreach ($transaksis as $t) {
            // htmlspecialchars() mencegah XSS injection dari data keterangan/tanggal
            $html .= '<tr>
                <td>' . htmlspecialchars($t->tanggal,    ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($t->keterangan, ENT_QUOTES, 'UTF-8') . '</td>
                <td class="money">Rp ' . number_format($t->pemasukan,   0, ',', '.') . '</td>
                <td class="money">Rp ' . number_format($t->pengeluaran, 0, ',', '.') . '</td>
                <td class="money">Rp ' . number_format($t->saldo,       0, ',', '.') . '</td>
            </tr>';
        }

        $html .= '
        <tr class="total"><td colspan="2">Total Pemasukan</td>
            <td class="money">Rp ' . number_format($totalPemasukan,   0, ',', '.') . '</td><td></td><td></td></tr>
        <tr class="total"><td colspan="2">Total Pengeluaran</td>
            <td></td><td class="money">Rp ' . number_format($totalPengeluaran, 0, ',', '.') . '</td><td></td></tr>
        <tr class="total"><td colspan="2">Saldo Akhir</td>
            <td></td><td></td><td class="money">Rp ' . number_format($saldoAkhir, 0, ',', '.') . '</td></tr>
        </table>
        </body>
        </html>';

        return response($html, 200, $headers);
    }
}