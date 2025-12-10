<!DOCTYPE html>
<html>
<head>
<style>
    body { font-family: Arial, sans-serif; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td {
        border: 1px solid #000;
        padding: 6px;
        font-size: 12px;
        vertical-align: top;
    }
    th { background: #eee; }

    .summary-table {
        width: 40%;
        margin-bottom: 20px;
        border: 1px solid #000;
        border-collapse: collapse;
        font-size: 13px;
    }
    .summary-table th, .summary-table td {
        padding: 6px;
        border: 1px solid #000;
    }
    h2 { text-align: center; margin-bottom: 5px; }
    h4 { margin-bottom: 2px; }
</style>
</head>

<body>

<h2>Laporan Pesanan Laundry</h2>

<!-- RINGKASAN -->
<h4>Ringkasan Keuangan</h4>
<table class="summary-table">
    <tr>
        <th>Status</th>
        <th>Total Keuangan</th>
    </tr>

    <tr>
        <td>Selesai</td>
        <td>Rp {{ number_format($pesanan->where('status','done')->sum('total_harga'),0,",",".") }}</td>
    </tr>

    <tr>
        <td>Proses</td>
        <td>Rp {{ number_format($pesanan->where('status','processing')->sum('total_harga'),0,",",".") }}</td>
    </tr>

    <tr>
        <td>Pending</td>
        <td>Rp {{ number_format($pesanan->where('status','pending')->sum('total_harga'),0,",",".") }}</td>
    </tr>

    <tr>
        <td>Batal</td>
        <td>Rp {{ number_format($pesanan->where('status','cancelled')->sum('total_harga'),0,",",".") }}</td>
    </tr>

    <tr>
        <th>Total Semua</th>
        <th>Rp {{ number_format($pesanan->sum('total_harga'),0,",",".") }}</th>
    </tr>
</table>

<!-- TABEL DETAIL -->
<table>
<thead>
<tr>
    <th>Invoice</th>
    <th>Customer</th>
    <th>Tanggal</th>
    <th>Status</th>
    <th>Total</th>
    <th>Item</th>
</tr>
</thead>

<tbody>
@foreach($pesanan as $p)
<tr>
    <td>{{ $p->nomor_invoice }}</td>
    <td>{{ $p->user->name }}</td>
    <td>{{ $p->created_at->format('d/m/Y') }}</td>
    <td>{{ ucfirst($p->status) }}</td>
    <td>Rp {{ number_format($p->total_harga,0,",",".") }}</td>
    <td>
        @foreach($p->items as $i)
            {{ $i->Item->nama_service }} (x{{ $i->jumlah }})<br>
        @endforeach
    </td>
</tr>
@endforeach
</tbody>

</table>

</body>
</html>
