<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\PesananLaundry;
use App\Models\Pembayaran;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\PesananExport;

class PesananController extends Controller
{

    public function search(Request $request)
    {
        $keyword = $request->search;
        $dateRange = $request->date_range;

        $pesanan = Pesanan::with('user')
            ->where(function($q) use ($keyword) {
                if ($keyword) {
                    $q->where('nomor_invoice', 'like', "%$keyword%")
                    ->orWhereHas('user', function($u) use ($keyword) {
                        $u->where('name', 'like', "%$keyword%");
                    });
                }
            });

        if ($dateRange) {
            [$start, $end] = explode(' to ', $dateRange);

            $pesanan->whereBetween('created_at', [
                $start . ' 00:00:00',
                $end . ' 23:59:59'
            ]);
        }

        $pesanan = $pesanan
            ->orderBy('created_at','desc')
            ->paginate(10)
            ->appends([
                'search' => $keyword,
                'date_range' => $dateRange
            ]);


    $htmlOrders = '<div class="orders-table-container">
        <div class="orders-table">
            <div class="table-header">
                <span>Order ID</span>
                <span>Customer</span>
                <span>Date</span>
                <span>Status</span>
                <span>Total</span>
                <span>Action</span>
            </div>';

    foreach($pesanan as $p){
        $htmlOrders .= '<div class="table-row">
            <span class="order-id">#'.($p->nomor_invoice ?? 'INV-'.$p->id).'</span>
            <span>'.$p->user->name.'</span>
            <span>'.$p->created_at->format('M d, Y').'</span>
            <span><span class="status '.($p->status == 'done' ? 'completed' : ($p->status == 'processing' ? 'processing' : 'pending')).'">'.ucfirst($p->status).'</span></span>
            <span class="total">Rp '.number_format($p->total_harga,0,',','.').'</span>
            <span class="action-buttons">
                <button class="btn btn-edit" onclick="window.location.href=\''.route('order.edit',$p->id).'\'"><i class="fa-solid fa-edit"></i></button>
                <button class="btn btn-detail" onclick="window.location.href=\''.route('order.show',$p->id).'\'"><i class="fa-solid fa-info-circle"></i></button>
            </span>
        </div>';
    }

    $htmlOrders .= '</div></div>';



        $htmlPagination = '<ul class="pagination">';
        if($pesanan->onFirstPage()){
            $htmlPagination .= '<li class="disabled"><a href="#">Prev</a></li>';
        } else {
            $htmlPagination .= '<li><a href="'.route('order.search',['page'=>$pesanan->currentPage()-1,'search'=>$keyword]).'">Prev</a></li>';
        }
        foreach ($pesanan->getUrlRange(1, $pesanan->lastPage()) as $page => $url) {
            $active = $page == $pesanan->currentPage() ? 'active' : '';
            $htmlPagination .= '<li class="'.$active.'"><a href="'.$url.'">'.$page.'</a></li>';
        }
        if($pesanan->hasMorePages()){
            $htmlPagination .= '<li><a href="'.route('order.search',['page'=>$pesanan->currentPage()+1,'search'=>$keyword]).'">Next</a></li>';
        } else {
            $htmlPagination .= '<li class="disabled"><a href="#">Next</a></li>';
        }
        $htmlPagination .= '</ul>';

        return response()->json(['data'=>$htmlOrders,'pagination'=>$htmlPagination]);
    }


public function create()
{
    $items = Item::all();

    // Jika admin → tampilkan semua user
    // Jika user biasa → hanya tampilkan dirinya sendiri
    if (auth()->user()->role === 'admin') {
        $customers = User::all();
    } else {
        $customers = User::where('id', auth()->id())->get();
    }

    return view('order.create', compact('items', 'customers'));
}




 public function store(Request $request)
{
    $items = json_decode($request->items);

    $total = 0;
    foreach ($items as $item) {
        $itemData = Item::findOrFail($item->id);
        $total += $itemData->harga * $item->jumlah;
    }

    // Gunakan user_id yang dikirim form (admin pilih / customer sendiri)
    $userId = $request->user_id;

    $user = User::findOrFail($userId);

    $nomorInvoice = 'INV-' . strtoupper(Str::random(8));

    $order = Pesanan::create([
        'user_id' => $user->id,
        'nomor_invoice' => $nomorInvoice,
        'tanggal_pesan' => now(),
        'alamat' => $user->address,
        'total_harga' => $total,
    ]);

    foreach ($items as $item) {
        $itemData = Item::findOrFail($item->id);
        PesananLaundry::create([
            'pesanan_id' => $order->id,
            'item_id' => $item->id,
            'jumlah' => $item->jumlah,
            'harga_item' => $itemData->harga,
            'subtotal' => $itemData->harga * $item->jumlah
        ]);
    }

    return redirect()->route('order.proses', $order->id);
}



    public function index(Request $request)
    {
        $query = Pesanan::with('user')->orderBy('created_at', 'desc');

        if ($request->date_range) {
            [$start, $end] = explode(' to ', $request->date_range);

            $query->whereBetween('created_at', [
                $start . ' 00:00:00',
                $end . ' 23:59:59'
            ]);
        }

        $pesanan = $query->paginate(10)->withQueryString();

        return view('order.index', compact('pesanan'));
    }

public function userOrders()
{
    $pesanan = Pesanan::where('user_id', auth()->id())
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    return view('order.index', compact('pesanan'));
}


// app/Http/Controllers/PesananController.php (Perubahan di method proses)

public function proses($id)
{
    $order = Pesanan::findOrFail($id);
    $customer = User::find($order->user_id);

    // --- PERBAIKAN: Menggunakan 'Item' (sesuai nama fungsi di Model) ---
    $orderItems = PesananLaundry::with('Item') // <-- Diubah dari 'itemData' ke 'Item'
                                 ->where('pesanan_id', $order->id)
                                 ->get();

    return view('order.proses', compact('order', 'customer', 'orderItems'));
}


    public function show($id)
{
    // Ambil pesanan beserta relasi user dan items
    $pesanan = Pesanan::with(['user', 'items.Item'])->findOrFail($id);

    return view('order.show', compact('pesanan'));
}


    public function edit($id)
{
    $pesanan = Pesanan::findOrFail($id);
    return view('order.edit', compact('pesanan'));
}

public function update(Request $request, $id)
{
    $pesanan = Pesanan::findOrFail($id);

    $request->validate([
        'status' => 'required|in:pending,processing,done',
    ]);

    $pesanan->update([
        'status' => $request->status,
    ]);

    return redirect()->route('order.index')->with('success', 'Status pesanan berhasil diperbarui.');
}


public function destroy($id)
{
    $pesanan = Pesanan::findOrFail($id);
    $pesanan->delete();

    return redirect()->route('order.index')->with('success', 'Pesanan berhasil dihapus.');
}


public function exportPDF(Request $request)
{
    $query = Pesanan::with(['user', 'items.Item'])
        ->orderBy('created_at', 'desc');

    if ($request->date_range) {
        [$start, $end] = explode(' to ', $request->date_range);

        $query->whereBetween('created_at', [
            $start . ' 00:00:00',
            $end . ' 23:59:59'
        ]);
    }

    $pesanan = $query->get();

    $pdf = Pdf::loadView('order.export_pdf', compact('pesanan'))
              ->setPaper('a4', 'portrait');

    return $pdf->download('laporan_pesanan.pdf');
}


public function exportExcel(Request $request)
{
    return Excel::download(
        new PesananExport($request->date_range),
        'Laporan_Pesanan.xlsx'
    );
}

}
