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
    protected $dateRange;

    public function __construct($dateRange = null)
    {
        $this->dateRange = $dateRange;
    }

    public function headings(): array
    {
        return [
            ['Ringkasan Keuangan'],
            ['Status', 'Total'],
            ['Selesai', $this->rupiah($this->sumStatus('done'))],
            ['Proses', $this->rupiah($this->sumStatus('processing'))],
            ['Pending', $this->rupiah($this->sumStatus('pending'))],
            ['Batal', $this->rupiah($this->sumStatus('cancelled'))],
            ['Total Semua', $this->rupiah($this->totalSemua())],
            [''],
            ['DETAIL PESANAN'],
            ['Invoice', 'Customer', 'Tanggal', 'Status', 'Total', 'Items']
        ];
    }

    public function collection()
    {
        $query = Pesanan::with('user','items.item');

        if ($this->dateRange) {
            [$start, $end] = explode(' to ', $this->dateRange);
            $query->whereBetween('created_at', [
                $start.' 00:00:00',
                $end.' 23:59:59'
            ]);
        }

        return $query->orderBy('created_at','desc')->get()
            ->map(function ($p) {
                return [
                    $p->nomor_invoice ?? 'INV-'.$p->id,
                    $p->user->name ?? '-',
                    $p->created_at->format('d/m/Y'),
                    ucfirst($p->status),
                    $this->rupiah($p->total_harga),
                    $p->items
                        ->map(fn($i) => $i->item->nama_service.' (x'.$i->jumlah.')')
                        ->implode(', ')
                ];
            });
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1  => ['font' => ['bold' => true, 'size' => 14]],
            2  => ['font' => ['bold' => true]],
            8  => ['font' => ['bold' => true]],
            10 => ['font' => ['bold' => true]],
        ];
    }

    private function sumStatus($status)
    {
        $query = Pesanan::where('status', $status);

        if ($this->dateRange) {
            [$start, $end] = explode(' to ', $this->dateRange);
            $query->whereBetween('created_at', [
                $start.' 00:00:00',
                $end.' 23:59:59'
            ]);
        }

        return $query->sum('total_harga');
    }

    private function totalSemua()
    {
        $query = Pesanan::query();

        if ($this->dateRange) {
            [$start, $end] = explode(' to ', $this->dateRange);
            $query->whereBetween('created_at', [
                $start.' 00:00:00',
                $end.' 23:59:59'
            ]);
        }

        return $query->sum('total_harga');
    }

    private function rupiah($angka)
    {
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }
}
