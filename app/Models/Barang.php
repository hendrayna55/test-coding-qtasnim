<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;
    protected $table = 'barangs';
    protected $guarde = ['id'];

    public function kategori(){
        return $this->belongsTo(KategoriBarang::class, 'kategori_id');
    }

    public function penjualans(){
        return $this->hasMany(PenjualanBarang::class, 'barang_id');
    }
}
