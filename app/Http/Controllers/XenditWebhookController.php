<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Log;

class XenditWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // 1️⃣ Validasi token callback
        $token = $request->header('X-Callback-Token');
        if ($token !== env('XENDIT_CALLBACK_TOKEN')) {
            Log::warning('Invalid Xendit callback token', ['token_received' => $token]);
            return response()->json(['message' => 'Invalid token'], 403);
        }

        // 2️⃣ Log payload untuk debugging
        Log::info('Xendit Webhook Received', $request->all());

        // 3️⃣ Ambil data dari payload
        $status = strtolower($request->input('status')); // pending, paid, expired
        $externalId = $request->input('external_id');    // contoh: order-1-1699999999

        // 4️⃣ Ambil ID pesanan dari external_id
        if (preg_match('/order-(\d+)-/', $externalId, $matches)) {
            $pesananId = $matches[1];
            Log::info('Parsed Pesanan ID', ['pesanan_id' => $pesananId]);

            // 5️⃣ Update pembayaran
            $pembayaran = Pembayaran::where('pesanan_id', $pesananId)->first();
            if ($pembayaran) {
                $pembayaran->status = $status;

                // Jika status paid, isi tanggal pembayaran otomatis
                if ($status === 'paid') {
                    $pembayaran->tanggal_pembayaran = now();
                }

                $pembayaran->save();
                Log::info('Pembayaran updated', [
                    'pembayaran_id' => $pembayaran->id,
                    'status' => $pembayaran->status,
                    'tanggal_pembayaran' => $pembayaran->tanggal_pembayaran
                ]);
            } else {
                Log::warning('Pembayaran not found', ['pesanan_id' => $pesananId]);
            }
        } else {
            Log::warning('External ID format invalid', ['external_id' => $externalId]);
        }

        return response()->json(['message' => 'Webhook processed']);
    }
}
