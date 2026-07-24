<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use Carbon\Carbon;
use App\Models\Kategori;
use App\Models\MetodePembayaran;
use App\Models\User;
use App\Models\Area;
use App\Models\FirewallIp;
use App\Exports\KeuanganExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * 1. Laporan Keuangan Retail (Keseluruhan & Dikelompokkan)
     */
    public function laporanKeuangan(Request $request)
    {
        /// Ambil filter tanggal
        $mulai = $request->input('tanggal_mulai', \Carbon\Carbon::now()->startOfMonth()->toDateString());
        $sampai = $request->input('tanggal_selesai', \Carbon\Carbon::now()->endOfMonth()->toDateString());

        // 1. Cek apakah ada input transaksi dengan kategori "Saldo Awal" pada bulan/periode tersebut
        $transaksiSaldoAwal = \App\Models\Transaksi::whereBetween('tanggal', [$mulai, $sampai])
            ->whereHas('kategori', function ($q) {
                $q->where('nama_kategori', 'like', '%Saldo Awal%');
            })->sum('debet');

        // 2. Akumulasi saldo dari bulan-bulan sebelumnya (diluar periode filter) + inputan Saldo Awal periode ini
        $transaksiSebelumnya = \App\Models\Transaksi::where('tanggal', '<', $mulai)->get();
        $saldoAwalAkumulasi = $transaksiSebelumnya->sum('debet') - $transaksiSebelumnya->sum('kredit');

        // Total Saldo Awal = Akumulasi lalu + Input Saldo Awal bulan ini
        $saldoAwal = $saldoAwalAkumulasi + $transaksiSaldoAwal;

        // 3. Transaksi Berjalan (Kecuali kategori Saldo Awal agar tidak double hitung di debet)
        $transaksi = \App\Models\Transaksi::whereBetween('tanggal', [$mulai, $sampai])
            ->whereDoesntHave('kategori', function ($q) {
                $q->where('nama_kategori', 'like', '%Saldo Awal%');
            })
            ->with(['kategori', 'metodePembayaran', 'area', 'user'])
            ->orderBy('tanggal', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $totalDebet = $transaksi->sum('debet');
        $totalKredit = $transaksi->sum('kredit');

        // 4. Saldo Akhir
        $mutasiBerjalan = $totalDebet - $totalKredit;
        $saldoAkhir = $saldoAwal + $mutasiBerjalan;

        $areas = \App\Models\Area::all();

        // Pemasukan per Area
        $pemasukanPerArea = [];
        foreach ($areas as $area) {
            $nominal = $transaksi->where('area_id', $area->id)->sum('debet');
            $pemasukanPerArea[$area->nama_area] = $nominal;
        }

        // Pemasukan lainnya di luar area (seperti Tukar Cash, dll)
        $pemasukanLainnya = $transaksi->filter(function ($r) use ($areas) {
            return $r->debet > 0 &&
                is_null($r->area_id) &&
                $r->kategori &&
                stripos($r->kategori->nama_kategori, 'Kasbon') === false &&
                $r->kategori->nama_kategori !== 'Pemasangan Baru';
        });

        foreach ($pemasukanLainnya as $trx) {
            $namaKat = $trx->kategori->nama_kategori;
            $pemasukanPerArea[$namaKat] = ($pemasukanPerArea[$namaKat] ?? 0) + $trx->debet;
        }

        $kasbonMasuk = $transaksi->filter(function ($r) {
            return $r->debet > 0 && $r->kategori && stripos($r->kategori->nama_kategori, 'Kasbon') !== false;
        })->sum('debet');

        // Pengeluaran per Kategori
        $kategoris = \App\Models\Kategori::all();
        $pengeluaranPerKategori = [];
        foreach ($kategoris as $kat) {
            $nominalKredit = $transaksi->where('kategori_id', $kat->id)->sum('kredit');
            if ($nominalKredit > 0 && stripos($kat->nama_kategori, 'Kasbon') === false) {
                $pengeluaranPerKategori[$kat->nama_kategori] = $nominalKredit;
            }
        }

        $kasbonKeluar = $transaksi->filter(function ($r) {
            return $r->kredit > 0 && $r->kategori && stripos($r->kategori->nama_kategori, 'Kasbon') !== false;
        })->sum('kredit');

        return view('laporan.keuangan', compact(
            'saldoAwal',
            'pemasukanPerArea',
            'kasbonMasuk',
            'pengeluaranPerKategori',
            'kasbonKeluar',
            'totalDebet',
            'totalKredit',
            'saldoAkhir',
            'mulai',
            'sampai',
            'areas'
        ));
    }

    /**
     * 2. Laporan Pemasangan Baru
     */
    public function pemasanganBaru(Request $request)
    {
        $mulai = $request->input('tanggal_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->input('tanggal_selesai', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $transaksi = Transaksi::with(['kategori', 'metodePembayaran', 'area', 'user'])
            ->whereBetween('tanggal', [$mulai, $sampai])
            ->whereHas('kategori', function ($q) {
                $q->where('nama_kategori', 'Pemasangan Baru');
            })
            ->orderBy('tanggal', 'asc')
            ->get();

        $totalPemasangan = $transaksi->sum(function ($row) {
            return $row->debet > 0 ? $row->debet : $row->kredit;
        });

        return view('laporan.pemasangan_baru', compact('transaksi', 'totalPemasangan', 'mulai', 'sampai'));
    }

    /**
     * 3. Laporan Pemasukan (Cash & Transfer) + Filter Tanggal
     */
    public function pemasukan(Request $request)
    {
        $mulai = $request->input('tanggal_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->input('tanggal_selesai', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $pemasukanCash = Transaksi::with(['kategori', 'metodePembayaran', 'area', 'user'])
            ->where('debet', '>', 0)
            ->whereBetween('tanggal', [$mulai, $sampai])
            ->whereHas('metodePembayaran', fn($q) => $q->where('nama_metode', 'Cash'))
            ->orderBy('tanggal', 'asc')
            ->get();

        $pemasukanTransfer = Transaksi::with(['kategori', 'metodePembayaran', 'area', 'user'])
            ->where('debet', '>', 0)
            ->whereBetween('tanggal', [$mulai, $sampai])
            ->whereHas('metodePembayaran', fn($q) => $q->where('nama_metode', 'Transfer Bank'))
            ->orderBy('tanggal', 'asc')
            ->get();

        $totalCash = $pemasukanCash->sum('debet');
        $totalTransfer = $pemasukanTransfer->sum('debet');

        return view('laporan.pemasukan', compact('pemasukanCash', 'pemasukanTransfer', 'totalCash', 'totalTransfer', 'mulai', 'sampai'));
    }

    /**
     * 4. Laporan Pengeluaran + Filter Tanggal
     */
    public function pengeluaran(Request $request)
    {
        $mulai = $request->input('tanggal_mulai', \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->input('tanggal_selesai', \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d'));

        $pengeluaranCash = Transaksi::with(['kategori', 'metodePembayaran', 'area', 'user'])
            ->where('kredit', '>', 0)
            ->whereBetween('tanggal', [$mulai, $sampai])
            ->whereHas('kategori', fn($q) => $q->where('nama_kategori', '!=', 'Kasbon'))
            ->whereHas('metodePembayaran', fn($q) => $q->where('nama_metode', 'Cash'))
            ->orderBy('tanggal', 'asc')
            ->get();

        $pengeluaranTransfer = Transaksi::with(['kategori', 'metodePembayaran', 'area', 'user'])
            ->where('kredit', '>', 0)
            ->whereBetween('tanggal', [$mulai, $sampai])
            ->whereHas('kategori', fn($q) => $q->where('nama_kategori', '!=', 'Kasbon'))
            ->whereHas('metodePembayaran', fn($q) => $q->where('nama_metode', 'Transfer Bank'))
            ->orderBy('tanggal', 'asc')
            ->get();

        $totalCash = $pengeluaranCash->sum('kredit');
        $totalTransfer = $pengeluaranTransfer->sum('kredit');

        return view('laporan.pengeluaran', compact('pengeluaranCash', 'pengeluaranTransfer', 'totalCash', 'totalTransfer', 'mulai', 'sampai'));
    }

    /**
     * 5. Laporan Kasbon Teknisi + Filter Tanggal
     */
    public function kasbon(Request $request)
    {
        $mulai = $request->input('tanggal_mulai', \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->input('tanggal_selesai', \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d'));
        $teknisiId = $request->input('teknisi_id');

        $userIdsKasbon = \App\Models\Transaksi::whereBetween('tanggal', [$mulai, $sampai])
            ->whereHas('kategori', fn($q) => $q->where('nama_kategori', 'like', '%Kasbon%'))
            ->whereNotNull('user_id')
            ->pluck('user_id')
            ->unique();

        $teknisis = \App\Models\User::whereIn('id', $userIdsKasbon)->get();

        $kasbon = \App\Models\Transaksi::with(['user', 'area', 'metodePembayaran'])
            ->whereBetween('tanggal', [$mulai, $sampai])
            ->whereHas('kategori', fn($q) => $q->where('nama_kategori', 'like', '%Kasbon%'))
            ->when($teknisiId, function ($query) use ($teknisiId) {
                return $query->where('user_id', $teknisiId);
            })
            ->orderBy('tanggal', 'asc')
            ->get();

        $totalKasbon = $kasbon->sum(function ($row) {
            return $row->kredit > 0 ? $row->kredit : $row->debet;
        });

        return view('laporan.kasbon', compact('kasbon', 'totalKasbon', 'mulai', 'sampai', 'teknisis', 'teknisiId'));
    }

    public function create(Request $request)
    {
        $kategoris = Kategori::all();
        $metodes = MetodePembayaran::all();
        $users = User::all();
        $areas = Area::all();

        $defaultJenis = $request->input('jenis', 'debet');

        return view('laporan.create', compact('kategoris', 'metodes', 'users', 'areas', 'defaultJenis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jenis_transaksi' => 'required|in:debet,kredit',
            'kategori_id' => 'required',
            'metode_pembayaran_id' => 'required',
            'keterangan' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
        ]);

        $debet = $request->jenis_transaksi === 'debet' ? $request->nominal : 0;
        $kredit = $request->jenis_transaksi === 'kredit' ? $request->nominal : 0;

        $tglFormat = \Carbon\Carbon::parse($request->tanggal)->format('d/m/Y');
        $this->recordLog('Tambah Transaksi', 'Menambahkan transaksi tgl ' . $tglFormat . ' senilai Rp ' . number_format($request->nominal, 0, ',', '.') . ' (' . $request->keterangan . ')');

        Transaksi::create([
            'tanggal' => $request->tanggal,
            'kategori_id' => $request->kategori_id,
            'metode_pembayaran_id' => $request->metode_pembayaran_id,
            'area_id' => $request->area_id ?: null,
            'user_id' => $request->user_id ?: null,
            'keterangan' => $request->keterangan,
            'debet' => $debet,
            'kredit' => $kredit,
        ]);

        return redirect('/laporan/keuangan')->with('success', 'Data transaksi berhasil ditambahkan!');
    }

    public function menuInput()
    {
        return view('laporan.menu_input');
    }

    public function indexTeknisi()
    {
        $users = User::all();
        return view('laporan.teknisi', compact('users'));
    }

    public function storeTeknisi(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        User::create([
            'name' => $request->name,
            'email' => strtolower(str_replace(' ', '', $request->name)) . '@teknisi.local',
            'password' => bcrypt('password123'),
            'jabatan' => 'Teknisi'
        ]);

        return redirect('/laporan/teknisi')->with('success', 'Nama teknisi berhasil ditambahkan!');
    }

    public function laporanArea(Request $request)
    {
        $mulai = $request->input('tanggal_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->input('tanggal_selesai', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $areaId = $request->input('area_id');

        $areas = Area::all();

        $transaksi = Transaksi::with(['kategori', 'metodePembayaran', 'area', 'user'])
            ->whereBetween('tanggal', [$mulai, $sampai])
            ->when($areaId, function ($query) use ($areaId) {
                return $query->where('area_id', $areaId);
            })
            ->orderBy('tanggal', 'asc')
            ->get();

        $totalNominal = $transaksi->sum('debet');

        return view('laporan.area', compact('transaksi', 'totalNominal', 'areas', 'areaId', 'mulai', 'sampai'));
    }

    public function indexArea()
    {
        $areas = Area::all();
        return view('laporan.master_area', compact('areas'));
    }

    public function storeArea(Request $request)
    {
        $request->validate([
            'nama_area' => 'required|string|max:255|unique:areas,nama_area',
        ]);

        Area::create([
            'nama_area' => $request->nama_area
        ]);

        return redirect('/laporan/master-area')->with('success', 'Area baru berhasil ditambahkan!');
    }

    public function indexKategori()
    {
        $kategoris = Kategori::all();
        return view('laporan.master_kategori', compact('kategoris'));
    }

    public function storeKategori(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategoris,nama_kategori',
        ]);

        Kategori::create([
            'nama_kategori' => $request->nama_kategori
        ]);

        return redirect('/laporan/master-kategori')->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    public function editArea($id)
    {
        $area = Area::findOrFail($id);
        return view('laporan.edit_area', compact('area'));
    }
    public function updateArea(Request $request, $id)
    {
        $request->validate(['nama_area' => 'required|string|max:255']);
        Area::where('id', $id)->update(['nama_area' => $request->nama_area]);
        return redirect('/laporan/master-area')->with('success', 'Area berhasil diperbarui!');
    }
    public function destroyArea($id)
    {
        Area::destroy($id);
        return redirect('/laporan/master-area')->with('success', 'Area berhasil dihapus!');
    }

    public function editTeknisi($id)
    {
        $user = User::findOrFail($id);
        return view('laporan.edit_teknisi', compact('user'));
    }
    public function updateTeknisi(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        User::where('id', $id)->update(['name' => $request->name]);
        return redirect('/laporan/teknisi')->with('success', 'Data teknisi berhasil diperbarui!');
    }
    public function destroyTeknisi($id)
    {
        User::destroy($id);
        return redirect('/laporan/teknisi')->with('success', 'Data teknisi berhasil dihapus!');
    }

    public function editKategori($id)
    {
        $kategori = Kategori::findOrFail($id);
        return view('laporan.edit_kategori', compact('kategori'));
    }
    public function updateKategori(Request $request, $id)
    {
        $request->validate(['nama_kategori' => 'required|string|max:255']);
        Kategori::where('id', $id)->update(['nama_kategori' => $request->nama_kategori]);
        return redirect('/laporan/master-kategori')->with('success', 'Kategori berhasil diperbarui!');
    }
    public function destroyKategori($id)
    {
        Kategori::destroy($id);
        return redirect('/laporan/master-kategori')->with('success', 'Kategori berhasil dihapus!');
    }

    public function editTransaksi($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $kategoris = Kategori::all();
        $metodes = MetodePembayaran::all();
        $users = User::all();
        $areas = Area::all();

        return view('laporan.edit_transaksi', compact('transaksi', 'kategoris', 'metodes', 'users', 'areas'));
    }

    public function updateTransaksi(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jenis_transaksi' => 'required|in:debet,kredit',
            'kategori_id' => 'required',
            'metode_pembayaran_id' => 'required',
            'keterangan' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
        ]);

        $debet = $request->jenis_transaksi === 'debet' ? $request->nominal : 0;
        $kredit = $request->jenis_transaksi === 'kredit' ? $request->nominal : 0;
        $this->recordLog('Edit Transaksi', 'Memperbarui data transaksi ID: ' . $id . ' (' . $request->keterangan . ')');

        Transaksi::where('id', $id)->update([
            'tanggal' => $request->tanggal,
            'kategori_id' => $request->kategori_id,
            'metode_pembayaran_id' => $request->metode_pembayaran_id,
            'area_id' => $request->area_id ?: null,
            'user_id' => $request->user_id ?: null,
            'keterangan' => $request->keterangan,
            'debet' => $debet,
            'kredit' => $kredit,
        ]);

        return back()->with('success', 'Data transaksi berhasil diperbarui!');
    }

    public function destroyTransaksi($id)
    {
        $trx = Transaksi::find($id);
        $deskripsi = $trx ? 'Menghapus transaksi: ' . $trx->keterangan . ' (Rp ' . number_format($trx->debet > 0 ? $trx->debet : $trx->kredit, 0, ',', '.') . ')' : 'Menghapus transaksi ID: ' . $id;

        $this->recordLog('Hapus Transaksi', $deskripsi);

        Transaksi::destroy($id);

        return back()->with('success', 'Data transaksi berhasil dihapus!');
    }

    public function exportExcel(Request $request)
    {
        $mulai = $request->input('tanggal_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->input('tanggal_selesai', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $transaksiSaldoAwal = \App\Models\Transaksi::whereBetween('tanggal', [$mulai, $sampai])
            ->whereHas('kategori', function ($q) {
                $q->where('nama_kategori', 'like', '%Saldo Awal%');
            })->sum('debet');

        $transaksiSebelumnya = \App\Models\Transaksi::where('tanggal', '<', $mulai)->get();
        $saldoAwalAkumulasi = $transaksiSebelumnya->sum('debet') - $transaksiSebelumnya->sum('kredit');
        $saldoAwal = $saldoAwalAkumulasi + $transaksiSaldoAwal;

        $transaksi = \App\Models\Transaksi::whereBetween('tanggal', [$mulai, $sampai])
            ->whereDoesntHave('kategori', function ($q) {
                $q->where('nama_kategori', 'like', '%Saldo Awal%');
            })
            ->with(['kategori', 'metodePembayaran', 'area', 'user'])
            ->get();

        $totalDebet = $transaksi->sum('debet');
        $totalKredit = $transaksi->sum('kredit');
        $mutasiBerjalan = $totalDebet - $totalKredit;
        $saldoAkhir = $saldoAwal + $mutasiBerjalan;

        $areas = \App\Models\Area::all();

        return Excel::download(
            new KeuanganExport($transaksi, $mulai, $sampai, $saldoAwal, $totalDebet, $totalKredit, $saldoAkhir, $areas),
            'Laporan_Keuangan_' . $mulai . '_sd_' . $sampai . '.xlsx'
        );
    }

    public function exportPdf(Request $request)
    {
        $mulai = $request->input('tanggal_mulai', \Carbon\Carbon::now()->startOfMonth()->toDateString());
        $sampai = $request->input('tanggal_selesai', \Carbon\Carbon::now()->endOfMonth()->toDateString());

        $transaksiSaldoAwal = \App\Models\Transaksi::whereBetween('tanggal', [$mulai, $sampai])
            ->whereHas('kategori', function ($q) {
                $q->where('nama_kategori', 'like', '%Saldo Awal%');
            })->sum('debet');

        $transaksiSebelumnya = \App\Models\Transaksi::where('tanggal', '<', $mulai)->get();
        $saldoAwalAkumulasi = $transaksiSebelumnya->sum('debet') - $transaksiSebelumnya->sum('kredit');
        $saldoAwal = $saldoAwalAkumulasi + $transaksiSaldoAwal;

        $transaksi = \App\Models\Transaksi::whereBetween('tanggal', [$mulai, $sampai])
            ->whereDoesntHave('kategori', function ($q) {
                $q->where('nama_kategori', 'like', '%Saldo Awal%');
            })
            ->with(['kategori', 'metodePembayaran', 'area', 'user'])
            ->get();

        $totalDebet = $transaksi->sum('debet');
        $totalKredit = $transaksi->sum('kredit');
        $mutasiBerjalan = $totalDebet - $totalKredit;
        $saldoAkhir = $saldoAwal + $mutasiBerjalan;

        $areas = \App\Models\Area::all();

        $pemasukanPerArea = [];
        foreach ($areas as $area) {
            $nominal = $transaksi->where('area_id', $area->id)->sum('debet');
            $pemasukanPerArea[$area->nama_area] = $nominal;
        }

        $pemasukanLainnya = $transaksi->filter(function ($r) {
            return $r->debet > 0 &&
                is_null($r->area_id) &&
                $r->kategori &&
                stripos($r->kategori->nama_kategori, 'Kasbon') === false &&
                $r->kategori->nama_kategori !== 'Pemasangan Baru';
        });

        foreach ($pemasukanLainnya as $trx) {
            $namaKat = $trx->kategori->nama_kategori;
            $pemasukanPerArea[$namaKat] = ($pemasukanPerArea[$namaKat] ?? 0) + $trx->debet;
        }

        $kasbonMasuk = $transaksi->filter(function ($r) {
            return $r->debet > 0 && $r->kategori && stripos($r->kategori->nama_kategori, 'Kasbon') !== false;
        })->sum('debet');

        $kategoris = \App\Models\Kategori::all();
        $pengeluaranPerKategori = [];
        foreach ($kategoris as $kat) {
            $nominalKredit = $transaksi->where('kategori_id', $kat->id)->sum('kredit');
            if (
                $nominalKredit > 0 &&
                stripos($kat->nama_kategori, 'Kasbon') === false &&
                stripos($kat->nama_kategori, 'Saldo Awal') === false &&
                stripos($kat->nama_kategori, 'Tukar Cash') === false
            ) {
                $pengeluaranPerKategori[$kat->nama_kategori] = $nominalKredit;
            }
        }

        $kasbonKeluar = $transaksi->filter(function ($r) {
            return $r->kredit > 0 && $r->kategori && stripos($r->kategori->nama_kategori, 'Kasbon') !== false;
        })->sum('kredit');

        return view('laporan.export_pdf', compact(
            'saldoAwal',
            'pemasukanPerArea',
            'kasbonMasuk',
            'pengeluaranPerKategori',
            'kasbonKeluar',
            'totalDebet',
            'totalKredit',
            'saldoAkhir',
            'mulai',
            'sampai',
            'areas'
        ));
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function processLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/laporan/keuangan');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    private function recordLog($aktivitas, $deskripsi)
    {
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'aktivitas' => $aktivitas,
            'deskripsi' => $deskripsi,
        ]);
    }

    public function indexLog(Request $request)
    {
        $mulai = $request->input('tanggal_mulai', \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->input('tanggal_selesai', \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d'));

        $query = \App\Models\ActivityLog::with('user')
            ->whereBetween('created_at', [$mulai . ' 00:00:00', $sampai . ' 23:59:59'])
            ->orderBy('created_at', 'asc')
            ->orderBy('id', 'asc');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('aktivitas', 'like', '%' . $search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($qUser) use ($search) {
                        $qUser->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        $logs = $query->paginate(20);

        return view('laporan.activity_log', compact('logs', 'mulai', 'sampai'));
    }

    public function firewallManagement()
    {
        $activeSessions = DB::table('sessions')
            ->leftJoin('users', 'sessions.user_id', '=', 'users.id')
            ->select('sessions.id as session_id', 'sessions.ip_address', 'sessions.last_activity', 'users.name')
            ->orderBy('sessions.last_activity', 'desc')
            ->get();

        $allowedIps = FirewallIp::all();

        return view('laporan.firewall', compact('activeSessions', 'allowedIps'));
    }

    public function killSession($sessionId)
    {
        DB::table('sessions')->where('id', $sessionId)->delete();
        return back()->with('success', 'Sesi / user berhasil ditendang dari sistem!');
    }

    public function storeIp(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|ip',
            'keterangan' => 'required|string|max:255'
        ]);

        // Menggunakan updateOrCreate: Jika IP sudah ada, update keterangannya. Jika belum, buat baru.
        FirewallIp::updateOrCreate(
            ['ip_address' => $request->ip_address],
            ['keterangan' => $request->keterangan]
        );

        return back()->with('success', 'IP Address berhasil disimpan ke dalam whitelist!');
    }
    public function destroyIp($id)
    {
        FirewallIp::destroy($id);
        return back()->with('success', 'IP Address berhasil dicabut dari whitelist!');
    }
}