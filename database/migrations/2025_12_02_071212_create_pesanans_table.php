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
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nomor_invoice')->nullable()->unique();
            $table->date('tanggal_pesan')->nullable();
            $table->text('alamat')->nullable();
            $table->enum('status', ['pending','processing','done','cancelled'])->default('pending');
            $table->decimal('total_harga', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
