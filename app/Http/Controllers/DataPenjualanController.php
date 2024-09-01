<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\PenjualanBarang;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Resources\AppResource;

class DataPenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangs = Barang::all();
        $penjualans  = PenjualanBarang::orderBy('tanggal_transaksi', 'asc')->get();

        return view('data.penjualan.index', compact('penjualans', 'barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => ['required'],
            'jumlah_penjualan' => ['required'],
            'tanggal_transaksi' => ['required'],
        ]);

        $barang = Barang::where('id', $request->barang_id)->first();
        $stokAwal = $barang->stock;
        $stokAkhir = $stokAwal - $request->jumlah_penjualan;

        $barang->update([
            'stock' => $stokAkhir
        ]);

        $data = PenjualanBarang::create([
            'barang_id' => $request->barang_id,
            'qty' => $request->jumlah_penjualan,
            'tanggal_transaksi' => $request->tanggal_transaksi,
        ]);

        alert()->success('Tambah Penjualan', $data->barang->nama_barang);
        return back();
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'barang_id' => ['required'],
            'jumlah_penjualan' => ['required'],
            'tanggal_transaksi' => ['required'],
        ]);

        $penjualan = PenjualanBarang::find($id);
        $barang = Barang::where('id', $penjualan->barang_id)->first();
        
        $stokAwal = $barang->stock;
        if ($request->jumlah_penjualan > $penjualan->qty) {
            $updateStok = $request->jumlah_penjualan - $penjualan->qty;
            $stokAkhir = $stokAwal - $updateStok;
        } else if($request->jumlah_penjualan < $penjualan->qty) {
            $updateStok = $penjualan->qty - $request->jumlah_penjualan;
            $stokAkhir = $stokAwal + $updateStok;
        } else {
            $stokAkhir = $stokAwal + 0;
        }
        
        $barang->update([
            'stock' => $stokAkhir
        ]);

        $penjualan->update([
            'barang_id' => $request->barang_id,
            'qty' => $request->jumlah_penjualan,
            'tanggal_transaksi' => $request->tanggal_transaksi,
        ]);

        alert()->success('Update Penjualan', $barang->nama_barang);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $penjualan = PenjualanBarang::find($id);
        $barang = Barang::where('id', $penjualan->barang_id)->first();
        $stokAwal = $barang->stock;
        $stokAkhir = $stokAwal + $penjualan->qty;

        $barang->update([
            'stock' => $stokAkhir
        ]);
        $penjualan->delete();

        alert()->success('Hapus Penjualan', $penjualan->barang->nama_barang);
        return back();
    }

    public function chart()
    {
        $barangs = Barang::with('penjualans')->orderBy('created_at', 'asc')->get();
        $penjualans  = PenjualanBarang::with('barang')->orderBy('tanggal_transaksi', 'asc')->get();
        return view('data.penjualan.chart', compact('penjualans', 'barangs'));
    }

    public function apiDataPenjualan()
    {
        $barangs = Barang::orderBy('created_at', 'asc')->get();
        $penjualans  = PenjualanBarang::orderBy('tanggal_transaksi', 'asc')->get();
        
        $data = [
            'data_barang' => $barangs,
            'data_penjualan' => $penjualans,
        ];
        return new AppResource(true, 'Data Barang dan Penjualan', $data);
    }
}
