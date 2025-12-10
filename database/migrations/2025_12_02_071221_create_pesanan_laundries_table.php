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
        Schema::create('pesanan_laundries', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pesanan_id')->constrained('pesanans')->onDelete('cascade');
        $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
        $table->integer('jumlah')->default(1);
        $table->decimal('harga_item', 12, 2);
        $table->decimal('subtotal', 12, 2);
        $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan_laundries');
    }
};
