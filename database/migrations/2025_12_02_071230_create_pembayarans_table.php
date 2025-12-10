<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanans')->onDelete('cascade');
            $table->string('xendit_invoice_id')->nullable()->index();
            $table->text('url_pembayaran')->nullable();
            $table->dateTime('tanggal_pembayaran')->nullable();
            $table->string('metode')->nullable();
            $table->decimal('jumlah', 12, 2)->default(0);
            $table->enum('status', ['pending','paid','expired'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
