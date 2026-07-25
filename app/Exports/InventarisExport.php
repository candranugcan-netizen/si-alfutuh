<?php

namespace App\Exports;

use App\Models\Inventaris;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class InventarisExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        // Ambil semua data beserta relasinya
        return Inventaris::with(['kategori', 'ruangan'])->orderBy('nama_barang', 'asc')->get();
    }

    public function headings(): array
    {
        return [
            'Kode Barang',
            'Nama Barang',
            'Merek',
            'Kategori',
            'Ruangan',
            'Total Jumlah',
            'Kondisi',
            'Sumber Dana',
            'Tanggal Pengadaan'
        ];
    }

    public function map($item): array
    {
        return [
            $item->kode_barang,
            $item->nama_barang,
            $item->merk ?? '-',
            $item->kategori->nama_kategori ?? 'Tanpa Kategori',
            $item->ruangan->nama_ruangan ?? 'Belum Ditempatkan',
            $item->jumlah_total,
            $item->kondisi,
            $item->sumber_dana ?? '-',
            $item->tanggal_pengadaan ? Carbon::parse($item->tanggal_pengadaan)->format('d-m-Y') : '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Buat baris pertama (Heading) menjadi tebal
            1 => ['font' => ['bold' => true]],
        ];
    }
}
