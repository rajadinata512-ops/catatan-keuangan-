<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;

class TransaksiController extends Controller
{
    /**
     * Halaman utama dashboard — menampilkan ringkasan & daftar transaksi.
     */
    public function dashboard()
    {
        $transaksis = Transaksi::where('user_id', auth()->id())
            ->latest()
            ->get();

        $totalPemasukan   = $transaksis->sum('pemasukan');
        $totalPengeluaran = $transaksis->sum('pengeluaran');
        $saldoAkhir       = $transaksis->first()->saldo ?? 0; // latest() = DESC, jadi first() = terbaru

        return view('dashboard', [
            'transaksis'       => $transaksis,
            'totalPemasukan'   => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'saldoAkhir'       => $saldoAkhir,
        ]);
    }

    /**
     * Halaman /transaksi — dipakai kalau masih butuh route terpisah.
     */
    public function index()
    {
        $transaksis = Transaksi::where('user_id', auth()->id())
            ->latest()
            ->get();

        $totalPemasukan   = $transaksis->sum('pemasukan');
        $totalPengeluaran = $transaksis->sum('pengeluaran');
        $saldoAkhir       = $transaksis->first()->saldo ?? 0; // latest() = DESC, jadi first() = terbaru

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

    /**
     * Update transaksi + recalculate semua saldo setelahnya.
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        if ($transaksi->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'tanggal'     => 'required|date',
            'keterangan'  => 'required|string|max:500',
            'pemasukan'   => 'nullable|numeric|min:0|max:999999999999',
            'pengeluaran' => 'nullable|numeric|min:0|max:999999999999',
        ]);

        $transaksi->update([
            'tanggal'     => $request->tanggal,
            'keterangan'  => $request->keterangan,
            'pemasukan'   => $request->pemasukan   ?? 0,
            'pengeluaran' => $request->pengeluaran ?? 0,
        ]);

        // Recalculate saldo semua transaksi user ini secara berurutan
        $semuaTransaksi = Transaksi::where('user_id', auth()->id())
            ->orderBy('created_at', 'asc')
            ->get();

        $saldo = 0;
        foreach ($semuaTransaksi as $t) {
            $saldo = $saldo + $t->pemasukan - $t->pengeluaran;
            $t->saldo = $saldo;
            $t->save();
        }

        return back()->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Transaksi $transaksi)
    {
        if ($transaksi->user_id !== auth()->id()) {
            abort(403);
        }

        $transaksi->delete();

        // Recalculate saldo setelah hapus
        $semuaTransaksi = Transaksi::where('user_id', auth()->id())
            ->orderBy('created_at', 'asc')
            ->get();

        $saldo = 0;
        foreach ($semuaTransaksi as $t) {
            $saldo = $saldo + $t->pemasukan - $t->pengeluaran;
            $t->saldo = $saldo;
            $t->save();
        }

        return back()->with('success', 'Transaksi berhasil dihapus.');
    }

    public function destroyAll()
    {
        Transaksi::where('user_id', auth()->id())->delete();

        return redirect()->back()->with('success', 'Semua transaksi berhasil dihapus.');
    }

    public function export()
    {
        $transaksis       = Transaksi::where('user_id', auth()->id())->orderBy('created_at', 'asc')->get();
        $totalPemasukan   = $transaksis->sum('pemasukan');
        $totalPengeluaran = $transaksis->sum('pengeluaran');
        $saldoAkhir       = $transaksis->last()->saldo ?? 0; // ASC order, jadi last() = terbaru

        $filename = 'catatan_keuangan.xls';

        $headers = [
            'Content-Type'        => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

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