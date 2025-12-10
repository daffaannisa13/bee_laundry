<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pesananlaundry extends Model
{
    protected $table = 'pesanan_laundries';
    protected $guarded = [];

    protected $fillable = [
        'pesanan_id',
        'item_id',
        'jumlah',
        'harga_item',
        'subtotal'
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function Item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
