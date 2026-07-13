<?php

namespace App\Exports\Keuangan;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Http\Request;

class TransaksiExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    protected $request;

    // Kita tangkap data filter dari controller
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = Transaksi::with(['kategori', 'siswa.kelas']);

        // Samakan persis dengan filter di Index Controller
        if ($this->request->filled('start_date') && $this->request->filled('end_date')) {
            $query->whereBetween('tanggal', [$this->request->start_date, $this->request->end_date]);
        }
        if ($this->request->filled('jenis')) {
            $query->where('jenis', $this->request->jenis);
        }
        if ($this->request->filled('kategori_transaksi_id')) {
            $query->where('kategori_transaksi_id', $this->request->kategori_transaksi_id);
        }
        if ($this->request->filled('keyword')) {
            $keyword = $this->request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('no_transaksi', 'like', "%{$keyword}%")
                  ->orWhere('keterangan', 'like', "%{$keyword}%")
                  ->orWhereHas('siswa', function($sq) use ($keyword) {
                      $sq->where('nama_siswa', 'like', "%{$keyword}%");
                  });
            });
        }

        return $query->latest('tanggal')->latest('id');
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'No. Transaksi',
            'Kategori',
            'Jenis',
            'Siswa / Keterangan',
            'Bulan Pembayaran (SPP)',
            'Nominal (Rp)',
        ];
    }

    public function map($transaksi): array
    {
        return [
            date('d-m-Y', strtotime($transaksi->tanggal)),
            $transaksi->no_transaksi,
            $transaksi->kategori->nama_kategori,
            $transaksi->jenis,
            $transaksi->siswa ? $transaksi->siswa->nama_siswa . ' (Kelas ' . $transaksi->siswa->kelas->nama_kelas . ')' : $transaksi->keterangan,
            $transaksi->untuk_bulan ?? '-',
            $transaksi->nominal, // Biarkan angka mentah, nanti di-styling via Excel
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Menebalkan baris pertama (Heading)
            1 => ['font' => ['bold' => true]],
        ];
    }
}
