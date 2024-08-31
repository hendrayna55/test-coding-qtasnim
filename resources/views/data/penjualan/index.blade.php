@extends('layouts.app')

@section('title')
    Data Penjualan
@endsection

@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('AdminLTE')}}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{asset('AdminLTE')}}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="{{asset('AdminLTE')}}/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
@endpush

@push('scripts')
    <!-- DataTables  & Plugins -->
    <script src="{{asset('AdminLTE')}}/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{{asset('AdminLTE')}}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{asset('AdminLTE')}}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{asset('AdminLTE')}}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="{{asset('AdminLTE')}}/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{{asset('AdminLTE')}}/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="{{asset('AdminLTE')}}/plugins/jszip/jszip.min.js"></script>
    <script src="{{asset('AdminLTE')}}/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="{{asset('AdminLTE')}}/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="{{asset('AdminLTE')}}/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="{{asset('AdminLTE')}}/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="{{asset('AdminLTE')}}/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

    <!-- Page specific script -->
    <script>
        $(function () {
            $("#example1").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

            $('#example2').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": false,
                "responsive": false,
                "scrollX" : true,
                "buttons": ["excel", "pdf", "colvis"]
            }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
    </script>
@endpush

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Data Transaksi</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">Data Transaksi</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-info">
                                <h3 class="card-title">Data Transaksi</h3>
                                <a href="{{url('/daftar-transaksi/tambah')}}" class="float-right btn btn-sm btn-success shadow"><i class="fa fa-plus"></i> Tambah Transaksi</a>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example2" class="table table-sm table-bordered table-hover nowrap">
                                    <thead class="bg-secondary">
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Nama Barang</th>
                                            <th class="text-center">Stok</th>
                                            <th class="text-center">Jumlah Terjual</th>
                                            <th class="text-center">Tanggal Transaksi</th>
                                            <th class="text-center">Jenis Barang</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            // Inisialisasi stok awal berdasarkan data dari database
                                            $stokAwal = [];
                                            foreach ($penjualans as $item) {
                                                $barangId = $item->barang->id;

                                                // Hitung stok awal dengan menjumlahkan stok dari database dengan total qty yang sudah terjual
                                                if (!isset($stokAwal[$barangId])) {
                                                    $stokAwal[$barangId] = $item->barang->stock + $item->barang->penjualans->sum('qty');
                                                }
                                            }
                                        @endphp

                                        @foreach ($penjualans as $item)
                                            @php
                                                $barangId = $item->barang->id;
                                        
                                                // Ambil stok saat ini yang akan ditampilkan di tabel
                                                $stokSaatIni = $stokAwal[$barangId];
                                        
                                                // Kurangi stok dengan jumlah yang terjual pada transaksi ini
                                                $stokAwal[$barangId] -= $item->qty;
                                            @endphp

                                            <tr>
                                                <td class="align-middle text-center">{{$loop->iteration}}</td>
                                                <td class="align-middle text-center">{{$item->barang->nama_barang}}</td>
                                                <td class="align-middle text-center">{{$stokSaatIni}}</td>
                                                <td class="align-middle text-center">{{$item->qty}}</td>
                                                <td class="align-middle text-center">{{date('d-m-Y', strtotime($item->created_at))}}</td>
                                                <td class="align-middle text-center">{{$item->barang->kategori->nama_kategori_barang}}</td>
                                                <td class="align-middle text-center">
                                                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modal-hapus-{{$item->id}}">
                                                        <i class="fa fa-trash-alt"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection