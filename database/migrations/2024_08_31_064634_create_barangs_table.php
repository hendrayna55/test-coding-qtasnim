<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang');
            $table->unsignedBigInteger('kategori_id');
            $table->foreign('kategori_id')->references('id')->on('kategori_barangs')->onDelete('cascade');
            $table->bigInteger('stock');
            $table->timestamps();
        });

        DB::table('barangs')->insert([
            [
                'nama_barang' => 'Kopi',
                'kategori_id' => 1,
                'stock' => 75,
            ],
            [
                'nama_barang' => 'Teh',
                'kategori_id' => 1,
                'stock' => 76,
            ],
            [
                'nama_barang' => 'Pasta Gigi',
                'kategori_id' => 2,
                'stock' => 80,
            ],
            [
                'nama_barang' => 'Sabun Mandi',
                'kategori_id' => 2,
                'stock' => 70,
            ],
            [
                'nama_barang' => 'Sampo',
                'kategori_id' => 2,
                'stock' => 75,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
