<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class TransaksiController extends Controller
{
    private function kategoriOptions(): array
    {
        return [
            'Makanan',
            'Minuman',
            'Transportasi',
            'Bensin',
            'Tagihan',
            'Paket Data',
            'Hutang',
            'Gaji',
            'Belanja',
            'Sparepart',
            'Service Kereta',
            'Pendidikan',
            'Kesehatan',
            'Hiburan',
            'Tabungan',
            'Lainnya',
        ];
    }

    private function kategoriColumnExists(): bool
    {
        try {
            return Schema::hasColumn('transaksis', 'kategori');
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function inferKategori(?string $keterangan, float|int|string|null $pemasukan = 0, float|int|string|null $pengeluaran = 0): string
    {
        $text = strtolower((string) $keterangan);
        $income = (float) ($pemasukan ?? 0);
        $expense = (float) ($pengeluaran ?? 0);

        if ($income > 0 && $expense <= 0) {
            if (str_contains($text, 'gaji') || str_contains($text, 'upah') || str_contains($text, 'salary')) {
                return 'Gaji';
            }
            if (str_contains($text, 'tabung') || str_contains($text, 'saving')) {
                return 'Tabungan';
            }
        }

        $map = [
            'Bensin'         => ['bensin', 'pertalite', 'pertamax', 'solar', 'premium bbl'],
            'Paket Data'     => ['paket data', 'kuota internet'],
            'Sparepart'      => ['sparepart', 'spare part', 'suku cadang', 'onderdil', 'kampas', 'busi'],
            'Service Kereta' => ['service motor', 'servis motor', 'ganti oli', 'bengkel motor', 'tune up'],
            'Makanan'        => ['kopi', 'nasi', 'makan', 'goreng', 'bakso', 'mie', 'ayam', 'latte', 'kede', 'kafe', 'cafe'],
            'Minuman'        => ['minuman', 'minum', 'juice', 'jus', 'susu', 'es teh'],
            'Transportasi'   => ['parkir', 'grab', 'gojek', 'angkot', 'transport', 'ojek'],
            'Tagihan'        => ['listrik', 'air', 'wifi', 'pulsa', 'kuota', 'kos', 'kost', 'kontrakan', 'tagihan'],
            'Hutang'         => ['hutang', 'utang', 'lunasin', 'lunas', 'bayar pinjam', 'pinjaman'],
            'Belanja'        => ['beli', 'indomaret', 'alfamart', 'paket', 'shopee', 'tokopedia', 'bima'],
            'Pendidikan'     => ['kampus', 'kuliah', 'buku', 'print', 'fotocopy', 'kelas', 'tugas'],
            'Kesehatan'      => ['obat', 'dokter', 'klinik', 'rumah sakit', 'vitamin'],
            'Hiburan'        => ['game', 'nonton', 'bioskop', 'spotify', 'netflix', 'hiburan'],
            'Tabungan'       => ['tabung', 'saving', 'deposit'],
        ];

        foreach ($map as $kategori => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($text, $keyword)) {
                    return $kategori;
                }
            }
        }

        return $income > 0 && $expense <= 0 ? 'Gaji' : 'Lainnya';
    }

    private function attachKategoriFallback($transaksis)
    {
        foreach ($transaksis as $transaksi) {
            $kategori = $transaksi->getAttribute('kategori');

            if (!$kategori) {
                $kategori = $this->inferKategori(
                    $transaksi->keterangan,
                    $transaksi->pemasukan,
                    $transaksi->pengeluaran
                );
            }

            $transaksi->setAttribute('kategori', $kategori);
        }

        return $transaksis;
    }

    private function recalculateSaldo(): void
    {
        $semuaTransaksi = Transaksi::where('user_id', auth()->id())
            ->orderBy('tanggal', 'asc')
            ->orderBy('created_at', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $saldo = 0;

        foreach ($semuaTransaksi as $transaksi) {
            $saldo = $saldo + (float) $transaksi->pemasukan - (float) $transaksi->pengeluaran;
            $transaksi->saldo = $saldo;
            $transaksi->save();
        }
    }

    private function baseDashboardData(): array
    {
        $transaksis = Transaksi::where('user_id', auth()->id())
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        $transaksis = $this->attachKategoriFallback($transaksis);

        $totalPemasukan   = $transaksis->sum('pemasukan');
        $totalPengeluaran = $transaksis->sum('pengeluaran');
        $saldoAkhir       = $totalPemasukan - $totalPengeluaran;

        return [
            'transaksis'       => $transaksis,
            'totalPemasukan'   => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'saldoAkhir'       => $saldoAkhir,
            'kategoriOptions'  => $this->kategoriOptions(),
        ];
    }

    /**
     * Halaman utama dashboard — menampilkan ringkasan & daftar transaksi.
     */
    public function dashboard()
    {
        return view('dashboard', $this->baseDashboardData());
    }

    /**
     * Halaman /transaksi — dipakai kalau masih butuh route terpisah.
     */
    public function index()
    {
        return view('transaksi.index', $this->baseDashboardData());
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'     => 'required|date',
            'kategori'    => 'nullable|string|max:50',
            'keterangan'  => 'required|string|max:500',
            'pemasukan'   => 'nullable|numeric|min:0|max:999999999999',
            'pengeluaran' => 'nullable|numeric|min:0|max:999999999999',
        ]);

        $pemasukan = (float) ($request->pemasukan ?? 0);
        $pengeluaran = (float) ($request->pengeluaran ?? 0);

        if ($pemasukan <= 0 && $pengeluaran <= 0) {
            return back()
                ->withErrors(['nominal' => 'Isi minimal pemasukan atau pengeluaran.'])
                ->withInput();
        }

        $data = [
            'user_id'     => auth()->id(),
            'tanggal'     => $request->tanggal,
            'keterangan'  => $request->keterangan,
            'pemasukan'   => $pemasukan,
            'pengeluaran' => $pengeluaran,
            'saldo'       => 0,
        ];

        if ($this->kategoriColumnExists()) {
            $data['kategori'] = $request->kategori ?: $this->inferKategori($request->keterangan, $pemasukan, $pengeluaran);
        }

        Transaksi::create($data);
        $this->recalculateSaldo();

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
            'kategori'    => 'nullable|string|max:50',
            'keterangan'  => 'required|string|max:500',
            'pemasukan'   => 'nullable|numeric|min:0|max:999999999999',
            'pengeluaran' => 'nullable|numeric|min:0|max:999999999999',
        ]);

        $pemasukan = (float) ($request->pemasukan ?? 0);
        $pengeluaran = (float) ($request->pengeluaran ?? 0);

        if ($pemasukan <= 0 && $pengeluaran <= 0) {
            return back()
                ->withErrors(['nominal' => 'Isi minimal pemasukan atau pengeluaran.'])
                ->withInput();
        }

        $data = [
            'tanggal'     => $request->tanggal,
            'keterangan'  => $request->keterangan,
            'pemasukan'   => $pemasukan,
            'pengeluaran' => $pengeluaran,
        ];

        if ($this->kategoriColumnExists()) {
            $data['kategori'] = $request->kategori ?: $this->inferKategori($request->keterangan, $pemasukan, $pengeluaran);
        }

        $transaksi->update($data);
        $this->recalculateSaldo();

        return back()->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Transaksi $transaksi)
    {
        if ($transaksi->user_id !== auth()->id()) {
            abort(403);
        }

        $transaksi->delete();
        $this->recalculateSaldo();

        return back()->with('success', 'Transaksi berhasil dihapus.');
    }

    public function destroyAll()
    {
        Transaksi::where('user_id', auth()->id())->delete();

        return redirect()->back()->with('success', 'Semua transaksi berhasil dihapus.');
    }

    public function export()
    {
        $transaksis = Transaksi::where('user_id', auth()->id())
            ->orderBy('tanggal', 'asc')
            ->orderBy('created_at', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $transaksis = $this->attachKategoriFallback($transaksis);

        $totalPemasukan   = $transaksis->sum('pemasukan');
        $totalPengeluaran = $transaksis->sum('pengeluaran');
        $saldoAkhir       = $totalPemasukan - $totalPengeluaran;

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
        table { border-collapse: collapse; width: 80%; margin: auto; font-family: Arial, sans-serif; }
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
            <th>Kategori</th>
            <th>Keterangan</th>
            <th>Pemasukan</th>
            <th>Pengeluaran</th>
            <th>Saldo</th>
        </tr>';

        foreach ($transaksis as $t) {
            $html .= '<tr>
                <td>' . htmlspecialchars($t->tanggal,    ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($t->kategori ?? 'Lainnya', ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($t->keterangan, ENT_QUOTES, 'UTF-8') . '</td>
                <td class="money">Rp ' . number_format($t->pemasukan,   0, ',', '.') . '</td>
                <td class="money">Rp ' . number_format($t->pengeluaran, 0, ',', '.') . '</td>
                <td class="money">Rp ' . number_format($t->saldo,       0, ',', '.') . '</td>
            </tr>';
        }

        $html .= '
        <tr class="total"><td colspan="3">Total Pemasukan</td>
            <td class="money">Rp ' . number_format($totalPemasukan,   0, ',', '.') . '</td><td></td><td></td></tr>
        <tr class="total"><td colspan="3">Total Pengeluaran</td>
            <td></td><td class="money">Rp ' . number_format($totalPengeluaran, 0, ',', '.') . '</td><td></td></tr>
        <tr class="total"><td colspan="3">Saldo Akhir</td>
            <td></td><td></td><td class="money">Rp ' . number_format($saldoAkhir, 0, ',', '.') . '</td></tr>
        </table>
        </body>
        </html>';

        return response($html, 200, $headers);
    }
}