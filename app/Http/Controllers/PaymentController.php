<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    // Menampilkan halaman checkout
    public function checkout($pesananId)
{
    $order = Pesanan::with('user', 'items.item')->findOrFail($pesananId);

    // Ambil langsung dari database
    $total = $order->total_harga;

    return view('order.checkout', compact('order', 'total'));
}


    // Proses pembayaran setelah memilih metode
    public function processPayment(Request $request, $pesananId)
    {
        $request->validate([
            'payment_method' => 'required|string',
        ]);

        $order = Pesanan::with('user')->findOrFail($pesananId);

        // Panggil Xendit create invoice
        $secret = env('XENDIT_API_KEY');
        if (!$secret) {
            return back()->withErrors('XENDIT_API_KEY belum diset di .env');
        }

        $payload = [
            'external_id' => 'order-'.$order->id.'-'.time(),
            'amount' => (int) round($order->total_harga),
            'payer_email' => $order->user->email ?? null,
            'description' => 'Pembayaran pesanan '.$order->nomor_invoice,
            
            'success_redirect_url' => url('/order/'.$order->id.'/success'),
            'failure_redirect_url' => url('/order/'.$order->id.'/failed'),
        ];

        $response = Http::withBasicAuth($secret, '')
            ->post('https://api.xendit.co/v2/invoices', $payload);

        if (!$response->successful()) {
            return back()->withErrors('Gagal membuat invoice: '.$response->body());
        }

        $data = $response->json();

        // Simpan data pembayaran
        $pembayaran = $order->pembayaran;
        if (!$pembayaran) {
            $pembayaran = Pembayaran::create([
                'pesanan_id' => $order->id,
                'jumlah' => $order->total_harga,
            ]);
        }

        $pembayaran->update([
            'xendit_invoice_id' => $data['id'] ?? null,
            'url_pembayaran' => $data['invoice_url'] ?? null,
            'status' => $data['status'] ?? 'pending',
            'metode' => $request->payment_method,
        ]);

        return redirect($pembayaran->url_pembayaran);
    }

public function success($pesananId)
{
    $order = Pesanan::findOrFail($pesananId);
    
    // Update status pesanan
    if ($order->pembayaran && $order->pembayaran->status === 'paid') {
        $order->status = 'processing'; // ✅ gunakan enum yang valid
        $order->save();
    }

    return view('order.success', compact('order'));
}


public function failed($pesananId)
{
    $order = Pesanan::findOrFail($pesananId);
    return view('order.failed', compact('order'));
}

}
