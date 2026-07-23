<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class KeuanganExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $transaksi;
    protected $mulai;
    protected $sampai;
    protected $saldoAwal;
    protected $totalDebet;
    protected $totalKredit;
    protected $saldoAkhir;
    protected $areas;

    public function __construct($transaksi, $mulai, $sampai, $saldoAwal, $totalDebet, $totalKredit, $saldoAkhir, $areas)
    {
        $this->transaksi = $transaksi;
        $this->mulai = $mulai;
        $this->sampai = $sampai;
        $this->saldoAwal = $saldoAwal;
        $this->totalDebet = $totalDebet;
        $this->totalKredit = $totalKredit;
        $this->saldoAkhir = $saldoAkhir;
        $this->areas = $areas;
    }

    public function collection()
    {
        $formatterMulai = \Carbon\Carbon::parse($this->mulai)->translatedFormat('d F Y');
        $formatterSampai = \Carbon\Carbon::parse($this->sampai)->translatedFormat('d F Y');
        $periodeStr = 'Periode ' . $formatterMulai . ' - ' . $formatterSampai;

        $data = [];

        // 0. Judul Periode
        $data[] = ['title', $periodeStr];
        $data[] = ['header', 'No', 'Keterangan', 'Debet', 'Kredit', 'Saldo'];

        // I. SALDO
        $data[] = ['section', 'I', 'Saldo', '', '', ''];
        $data[] = ['row', '1', 'Saldo Bulan Sebelumnya (Saldo Awal)', '', '', $this->saldoAwal];
        $data[] = ['row', '2', 'Pemasukan Berjalan (Bulan Ini)', $this->totalDebet, '', $this->saldoAwal + $this->totalDebet];

        // II. PEMASUKAN RINCI
        $data[] = ['section', 'II', 'Pemasukan Rinci', '', '', ''];
        $noPemasukan = 1;

        foreach ($this->areas as $area) {
            $nominal = $this->transaksi->where('area_id', $area->id)->sum('debet');
            $data[] = ['row', $noPemasukan++, 'Pembayaran Retail ' . $area->nama_area, $nominal, '', '-'];
        }

        $daftarAreaNames = $this->areas->pluck('nama_area')->toArray();
        $pemasukanLainnya = $this->transaksi->filter(function ($r) use ($daftarAreaNames) {
            return $r->debet > 0 &&
                is_null($r->area_id) &&
                $r->kategori &&
                stripos($r->kategori->nama_kategori, 'Kasbon') === false &&
                $r->kategori->nama_kategori !== 'Pemasangan Baru';
        });

        foreach ($pemasukanLainnya as $trx) {
            $namaKat = $trx->kategori->nama_kategori;
            $data[] = ['row', $noPemasukan++, $namaKat, $trx->debet, '', '-'];
        }

        $kasbonMasuk = $this->transaksi->filter(function ($r) {
            return $r->debet > 0 && $r->kategori && stripos($r->kategori->nama_kategori, 'Kasbon') !== false;
        })->sum('debet');

        $data[] = ['row', $noPemasukan++, 'Pembayaran Kasbon Teknisi', $kasbonMasuk, '', '-'];

        // III. PENGELUARAN RINCI
        $data[] = ['section', 'III', 'Pengeluaran Rinci', '', '', ''];
        $noPengeluaran = 1;

        $kategoris = \App\Models\Kategori::all();
        foreach ($kategoris as $kat) {
            $nominalKredit = $this->transaksi->where('kategori_id', $kat->id)->sum('kredit');
            if (
                $nominalKredit > 0 &&
                stripos($kat->nama_kategori, 'Kasbon') === false &&
                stripos($kat->nama_kategori, 'Saldo Awal') === false &&
                stripos($kat->nama_kategori, 'Tukar Cash') === false
            ) {
                $data[] = ['row', $noPengeluaran++, $kat->nama_kategori, '', $nominalKredit, -$nominalKredit];
            }
        }

        $kasbonKeluar = $this->transaksi->filter(function ($r) {
            return $r->kredit > 0 && $r->kategori && stripos($r->kategori->nama_kategori, 'Kasbon') !== false;
        })->sum('kredit');

        if ($kasbonKeluar > 0) {
            $data[] = ['row', $noPengeluaran++, 'Kasbon Teknisi', '', $kasbonKeluar, -$kasbonKeluar];
        }

        // TOTAL KEUANGAN RETAIL
        $data[] = ['total', 'TOTAL KEUANGAN RETAIL', $this->totalDebet, $this->totalKredit, $this->saldoAkhir];

        // SPACING
        $data[] = ['empty'];

        // TABEL POSISI KAS AKHIR
        $data[] = ['sub_title', 'Laporan Keuangan Retail'];
        $data[] = ['sub_periode', $periodeStr];
        $data[] = ['header_kas', 'No', 'Keterangan', 'Saldo (Rp)'];
        $data[] = ['row_kas', '1', 'Uang Cash di Operasional', '-'];
        $data[] = ['row_kas', '2', 'Uang Cash dari Retail yang belum disetor ke Bank', '-'];
        $data[] = ['row_kas', '3', 'Uang Retail di Rekening', $this->saldoAkhir];
        $data[] = ['total_kas', 'TOTAL KEUANGAN RETAIL', $this->saldoAkhir];

        // TANDA TANGAN
        $data[] = ['empty'];
        $data[] = ['note', 'Catatan : Bukti Terlampir'];
        $data[] = ['empty'];
        $data[] = ['ttd_date', 'Jember, ' . $formatterSampai];
        $data[] = ['ttd_label', 'Direktur', 'Admin Retail'];
        $data[] = ['empty'];
        $data[] = ['empty'];
        $data[] = ['empty'];
        $data[] = ['ttd_name', 'Fans Ach Farrosil Miqdad', 'Hertina Rahmaningtyas'];
        $data[] = ['empty'];
        $data[] = ['ttd_mengetahui', 'Mengetahui,'];
        $data[] = ['ttd_komisaris', 'Komisaris'];
        $data[] = ['empty'];
        $data[] = ['empty'];
        $data[] = ['empty'];
        $data[] = ['ttd_komisaris_name', 'Erfan Effendi S.Pd., M.Pd'];

        return collect($data);
    }

    public function headings(): array
    {
        return [];
    }

    public function map($row): array
    {
        $type = $row[0] ?? '';
        switch ($type) {
            case 'title':
            case 'sub_title':
            case 'sub_periode':
            case 'note':
            case 'ttd_date':
                return [$row[1]];
            case 'header':
                return [$row[1], $row[2], $row[3], $row[4], $row[5]];
            case 'section':
                return [$row[1], $row[2], '', '', ''];
            case 'row':
                $debetFmt = is_numeric($row[3]) ? 'Rp ' . number_format($row[3], 0, ',', '.') : ($row[3] !== '' ? $row[3] : '');
                $kreditFmt = is_numeric($row[4]) ? 'Rp ' . number_format($row[4], 0, ',', '.') : ($row[4] !== '' ? $row[4] : '');
                $saldoFmt = is_numeric($row[5]) ? 'Rp ' . number_format($row[5], 0, ',', '.') : ($row[5] !== '' ? $row[5] : '');
                return [$row[1], $row[2], $debetFmt, $kreditFmt, $saldoFmt];
            case 'total':
                return [$row[1], '', 'Rp ' . number_format($row[2], 0, ',', '.'), 'Rp ' . number_format($row[3], 0, ',', '.'), 'Rp ' . number_format($row[4], 0, ',', '.')];
            case 'header_kas':
                return [$row[1], $row[2], $row[3]];
            case 'row_kas':
                $saldoKasFmt = is_numeric($row[3]) ? 'Rp ' . number_format($row[3], 0, ',', '.') : $row[3];
                return [$row[1], $row[2], $saldoKasFmt];
            case 'total_kas':
                return [$row[1], '', 'Rp ' . number_format($row[2], 0, ',', '.')];
            case 'ttd_label':
            case 'ttd_name':
                return ['', $row[1], '', $row[2]];
            case 'ttd_mengetahui':
            case 'ttd_komisaris':
            case 'ttd_komisaris_name':
                return ['', '', $row[1]];
            default:
                return [];
        }
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:E1');
        $sheet->mergeCells('A16:C16');
        $sheet->mergeCells('A17:C17');

        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];

        return [
            1 => [
                'font' => ['bold' => true, 'size' => 11],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F1F2F6']]
            ],
            2 => [
                'font' => ['bold' => true, 'size' => 10],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F1F2F6']]
            ],
        ];
    }
}