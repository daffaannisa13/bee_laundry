<?php

namespace App\Exports;

use App\Models\Pesanan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PesananExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    public function headings(): array
    {
        return [
            ['Ringkasan Keuangan'],            // Row 1
            ['Status', 'Total'],               // Row 2
            ['Selesai', $this->rupiah($this->sumStatus('done'))],
            ['Proses', $this->rupiah($this->sumStatus('processing'))],
            ['Pending', $this->rupiah($this->sumStatus('pending'))],
            ['Batal', $this->rupiah($this->sumStatus('cancelled'))],
            ['Total Semua', $this->rupiah(Pesanan::sum('total_harga'))],
            [''],                               // Spacer
            ['DETAIL PESANAN'],                 // Title
            [
                'Invoice', 'Customer', 'Tanggal',
                'Status', 'Total', 'Items'
            ]
        ];
    }

    public function collection()
    {
        return Pesanan::with('user','items.item')
            ->get()
            ->map(function ($p) {
                return [
                    $p->nomor_invoice,
                    $p->user->name,
                    $p->created_at->format('d/m/Y'),
                    ucfirst($p->status),
                    $this->rupiah($p->total_harga),
                    $p->items->map(fn($i) => $i->item->nama_service.' (x'.$i->jumlah.')')->implode(", ")
                ];
            });
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true, 'size' => 14]],
            2    => ['font' => ['bold' => true]],
            8    => ['font' => ['bold' => true]],
            10   => ['font' => ['bold' => true]],
        ];
    }

    private function sumStatus($status)
    {
        return Pesanan::where('status', $status)->sum('total_harga');
    }

    private function rupiah($angka)
    {
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }
}
