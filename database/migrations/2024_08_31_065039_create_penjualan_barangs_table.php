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
        Schema::create('penjualan_barangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barang_id');
            $table->foreign('barang_id')->references('id')->on('barangs')->onDelete('cascade');
            $table->bigInteger('qty');
            $table->date('tanggal_transaksi');
            $table->timestamps();
        });

        DB::table('penjualan_barangs')->insert([
            [
                'barang_id' => 1,
                'qty' => 10,
                'tanggal_transaksi' => '2021-05-01',
                'created_at' => '2021-05-01 00:00:00'
            ],
            [
                'barang_id' => 2,
                'qty' => 19,
                'tanggal_transaksi' => '2021-05-05',
                'created_at' => '2021-05-05 00:00:00'
            ],
            [
                'barang_id' => 1,
                'qty' => 15,
                'tanggal_transaksi' => '2021-05-10',
                'created_at' => '2021-05-10 00:00:00'
            ],
            [
                'barang_id' => 3,
                'qty' => 20,
                'tanggal_transaksi' => '2021-05-11',
                'created_at' => '2021-05-11 00:00:00'
            ],
            [
                'barang_id' => 4,
                'qty' => 30,
                'tanggal_transaksi' => '2021-05-11',
                'created_at' => '2021-05-11 00:00:00'
            ],
            [
                'barang_id' => 5,
                'qty' => 25,
                'tanggal_transaksi' => '2021-05-12',
                'created_at' => '2021-05-12 00:00:00'
            ],
            [
                'barang_id' => 2,
                'qty' => 5,
                'tanggal_transaksi' => '2021-05-12',
                'created_at' => '2021-05-12 00:00:00'
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan_barangs');
    }
};
