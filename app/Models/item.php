<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class item extends Model
{
    use HasFactory;


    protected $table = 'items';
    protected $primaryKey = 'id';


    protected $fillable = [
        'nama_service',
        'harga',
        'tipe_service',
        'deskripsi'
];

public function items() {
    return $this->hasMany(PesananLaundry::class, 'pesanan_id');
}

}
