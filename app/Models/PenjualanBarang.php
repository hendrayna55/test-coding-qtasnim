<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanBarang extends Model
{
    use HasFactory;
    protected $table = 'penjualan_barangs';
    protected $guarded = ['id'];

    public function barang(){
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
