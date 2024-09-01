@extends('layouts.app')

@section('title')
    Data Penjualan
@endsection

@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('AdminLTE')}}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{asset('AdminLTE')}}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="{{asset('AdminLTE')}}/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('AdminLTE')}}/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{asset('AdminLTE')}}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
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

    <!-- Select2 -->
    <script src="{{asset('AdminLTE')}}/plugins/select2/js/select2.full.min.js"></script>

    <!-- Inisialisasi Select2 -->
    <script>
        $(document).ready(function(){
            // Regex Validasi
            const multipleSpacesRegex = /\s{2,}/;
            const validRegex = /^[a-zA-Z0-9\s]*$/;
            
            $('#barang_id').select2();

            // Fungsi untuk menampilkan pesan sukses atau error
            function showAlert(type, message, spanSukses, spanError, input) {
                const isSuccess = type === 'success';
                spanSukses.classList.toggle('d-none', !isSuccess);
                spanError.classList.toggle('d-none', isSuccess);
                input.classList.toggle('is-valid', isSuccess);
                input.classList.toggle('is-invalid', !isSuccess);
                if (isSuccess) {
                    spanSukses.textContent = message;
                } else {
                    spanError.textContent = message;
                }
            }

            // Fungsi untuk reset pesan alert
            function resetShowAlert(spanError, spanSukses, input) {
                spanError.classList.add('d-none');
                spanSukses.classList.add('d-none');
                input.classList.remove('is-invalid', 'is-valid');
            }

            // Fungsi untuk memeriksa validitas form
            function checkFormValidity(valid, button) {
                button.disabled = !valid;
            }

            // Fungsi untuk memvalidasi kategori
            function validateStok(inputElement, stockElement, successAlertElement, errorAlertElement, buttonElement, currentJumlah = null) {
                const jumlahPenjualan = parseInt(inputElement.value); // Konversi ke integer
                const stokBarang = parseInt(stockElement.value); // Konversi ke integer
                const jumlahSaatIni = currentJumlah;
                let pesanAlert = '';

                if (jumlahPenjualan === '') {
                    pesanAlert = 'Jumlah penjualan tidak boleh kosong';
                    showAlert('error', pesanAlert, successAlertElement, errorAlertElement, inputElement);
                    checkFormValidity(false, buttonElement);
                } else if (!validRegex.test(jumlahPenjualan)) {
                    pesanAlert = 'Tidak boleh menggunakan karakter spesial';
                    showAlert('error', pesanAlert, successAlertElement, errorAlertElement, inputElement);
                    checkFormValidity(false, buttonElement);
                } else if (jumlahPenjualan == jumlahSaatIni) {
                    pesanAlert = 'Jumlah penjualan valid';
                    showAlert('success', pesanAlert, successAlertElement, errorAlertElement, inputElement);
                    checkFormValidity(true, buttonElement);
                } else if (jumlahPenjualan > stokBarang) {
                    pesanAlert = 'Stok tidak cukup';
                    showAlert('error', pesanAlert, successAlertElement, errorAlertElement, inputElement);
                    checkFormValidity(false, buttonElement);
                } else if (jumlahPenjualan <= stokBarang && jumlahPenjualan > 0) {
                    pesanAlert = 'Jumlah penjualan valid';
                    showAlert('success', pesanAlert, successAlertElement, errorAlertElement, inputElement);
                    checkFormValidity(true, buttonElement);
                } else {
                    pesanAlert = 'Jumlah penjualan tidak valid';
                    showAlert('error', pesanAlert, successAlertElement, errorAlertElement, inputElement);
                    checkFormValidity(false, buttonElement);
                }
            }

            const namaBarang = $('#barang_id');
            const stokBarang = document.getElementById('stock_saat_ini');
            const jumlahPenjualan = document.getElementById('jumlah_penjualan');
            const tanggalTransaksi = document.getElementById('tanggal_transaksi');
            const errorAlert = document.getElementById('errorAlert');
            const successAlert = document.getElementById('successAlert');
            const addBtn = document.getElementById('addButton');

            jumlahPenjualan.disabled = true;
            tanggalTransaksi.disabled = true;
            addBtn.disabled = true;

            namaBarang.change(function(){
                var selectedOption = $(this).find('option:selected');
                var stock = selectedOption.data('stock');
                stokBarang.value = stock;
                jumlahPenjualan.disabled = false;
                tanggalTransaksi.disabled = false;
                jumlahPenjualan.value = '';
                tanggalTransaksi.value = '';
                resetShowAlert(errorAlert, successAlert, jumlahPenjualan);
                validateStok(jumlahPenjualan, stokBarang, successAlert, errorAlert, addBtn);
            });

            jumlahPenjualan.addEventListener('input', function() {
                addButton.disabled = true;
                resetShowAlert(errorAlert, successAlert, jumlahPenjualan);
                validateStok(jumlahPenjualan, stokBarang, successAlert, errorAlert, addButton);
            });

            const dataBase = @json($penjualans);
            dataBase.forEach(item => {
                const namaBarangUpdate = $('#barang_id_' + item.id);
                const stokBarangUpdate = document.getElementById('stock_saat_ini_' + item.id);
                const jumlahPenjualanUpdate = document.getElementById('jumlah_penjualan_' + item.id);
                const tanggalTransaksiUpdate = document.getElementById('tanggal_transaksi_' + item.id);
                const errorAlertUpdate = document.getElementById('errorAlert_' + item.id);
                const successAlertUpdate = document.getElementById('successAlert_' + item.id);
                const addBtnUpdate = document.getElementById('addButton_' + item.id);

                addBtnUpdate.disabled = true;

                var selectedOptionUpdate = namaBarangUpdate.find('option:selected');
                var stockUpdate = selectedOptionUpdate.data('stock');
                
                stokBarangUpdate.value = stockUpdate;
                jumlahPenjualanUpdate.disabled = false;
                tanggalTransaksiUpdate.disabled = false;
                resetShowAlert(errorAlertUpdate, successAlertUpdate, jumlahPenjualanUpdate);
                validateStok(jumlahPenjualanUpdate, stokBarangUpdate, successAlertUpdate, errorAlertUpdate, addBtnUpdate, jumlahPenjualanUpdate.value);

                namaBarangUpdate.change(function(){
                    selectedOptionUpdate = $(this).find('option:selected');
                    stockUpdate = selectedOptionUpdate.data('stock');
                    stokBarangUpdate.value = stockUpdate;
                    jumlahPenjualanUpdate.disabled = false;
                    tanggalTransaksiUpdate.disabled = false;
                    jumlahPenjualanUpdate.value = '';
                    tanggalTransaksiUpdate.value = '';
                    resetShowAlert(errorAlertUpdate, successAlertUpdate, jumlahPenjualanUpdate);
                    validateStok(jumlahPenjualanUpdate, stokBarangUpdate, successAlertUpdate, errorAlertUpdate, addBtnUpdate);
                });

                jumlahPenjualanUpdate.addEventListener('input', function() {
                    addBtnUpdate.disabled = true;
                    resetShowAlert(errorAlertUpdate, successAlertUpdate, jumlahPenjualanUpdate);
                    validateStok(jumlahPenjualanUpdate, stokBarangUpdate, successAlertUpdate, errorAlertUpdate, addBtnUpdate);
                });
            });
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
                        <h1>Data Penjualan</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">Data Penjualan</li>
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
                                <h3 class="card-title">Data Penjualan</h3>
                                <button type="button" class="float-right btn btn-sm btn-success shadow" data-toggle="modal" data-target="#modal-tambah"><i class="fa fa-plus"></i> Tambah Penjualan</button>
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
                                                <td class="align-middle text-center">{{date('d-m-Y', strtotime($item->tanggal_transaksi))}}</td>
                                                <td class="align-middle text-center">{{$item->barang->kategori->nama_kategori_barang}}</td>
                                                <td class="align-middle text-center">
                                                    <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modal-edit-{{$item->id}}">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modal-hapus-{{$item->id}}">
                                                        <i class="fa fa-trash-alt"></i>
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Modal Edit Penjualan -->
                                            <div class="modal fade text-sm" id="modal-edit-{{$item->id}}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-success">
                                                            <h4 class="tw-text-xl tw-font-bold">Edit Penjualan</h4>
                                                        </div>
                                                        <form action="{{url('/data-penjualan/' . $item->id)}}" method="post">
                                                            @csrf
                                                            @method('put')

                                                            <div class="modal-body">
                                                                <div class="row justify-content-center">
                                                                    <div class="col-12 col-sm-12 col-lg-12">

                                                                        <!-- Nama Barang -->
                                                                        <div class="form-group row">
                                                                            <label for="barang_id_{{$item->id}}" class="col-12 col-sm-12 col-lg-12 col-form-label">Nama Barang</label>
                                                                            <div class="input-group col-12 col-sm-12 col-lg-12">
                                                                                <select name="barang_id" id="barang_id_{{$item->id}}" class="form-control select2 @error('barang_id')
                                                                                    is-invalid
                                                                                @enderror" style="width: 100%;">
                                                                                    <option value="" selected disabled>-- Pilih Barang --</option>

                                                                                    @foreach ($barangs as $barang)
                                                                                        <option value="{{$barang->id}}" data-stock="{{$barang->stock}}" {{(old('barang_id', $item->barang_id) == $barang->id) ? 'selected' : ''}}>{{$barang->nama_barang}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                                
                                                                                @error('barang_id')
                                                                                    <span class="invalid-feedback" role="alert">
                                                                                        <strong>{{ $message }}</strong>
                                                                                    </span>
                                                                                @enderror
                                                                            </div>
                                                                        </div>

                                                                        <!-- Stok Saat Ini -->
                                                                        <div class="form-group row">
                                                                            <label for="stock_saat_ini_{{$item->id}}" class="col-12 col-sm-12 col-lg-12 col-form-label">Stok Saat Ini</label>
                                                                            <div class="input-group col-12 col-sm-12 col-lg-12">
                                                                                <input type="number" class="form-control" id="stock_saat_ini_{{$item->id}}" placeholder="Pilih Barang" value="" readonly disabled>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Jumlah Penjualan -->
                                                                        <div class="form-group row">
                                                                            <label for="jumlah_penjualan_{{$item->id}}" class="col-12 col-sm-12 col-lg-12 col-form-label">Jumlah Penjualan</label>
                                                                            <div class="input-group col-12 col-sm-12 col-lg-12">
                                                                                <input type="number" class="form-control @error('jumlah_penjualan') is-invalid @enderror" id="jumlah_penjualan_{{$item->id}}" placeholder="Cth: 15" value="{{old('jumlah_penjualan')?old('jumlah_penjualan'):$item->qty}}" name="jumlah_penjualan" required>

                                                                                <!-- Alert Error -->
                                                                                <div class="tw-font-bold invalid-feedback" id="errorAlert_{{$item->id}}"></div>

                                                                                <!-- Alert Sukses -->
                                                                                <div class="tw-font-bold valid-feedback d-none" id="successAlert_{{$item->id}}"></div>
                                                                                
                                                                                @error('jumlah_penjualan')
                                                                                    <span class="invalid-feedback" role="alert">
                                                                                        <strong>{{ $message }}</strong>
                                                                                    </span>
                                                                                @enderror
                                                                            </div>
                                                                        </div>

                                                                        <!-- Tanggal Transaksi -->
                                                                        <div class="form-group row">
                                                                            <label for="tanggal_transaksi_{{$item->id}}" class="col-12 col-sm-12 col-lg-12 col-form-label">Tanggal Transaksi</label>
                                                                            <div class="input-group col-12 col-sm-12 col-lg-12">
                                                                                <input type="date" class="form-control @error('tanggal_transaksi') is-invalid @enderror" id="tanggal_transaksi_{{$item->id}}" placeholder="Cth: 150000" value="{{old('tanggal_transaksi')?old('tanggal_transaksi'):$item->tanggal_transaksi}}" name="tanggal_transaksi" required>
                            
                                                                                @error('tanggal_transaksi')
                                                                                    <span class="invalid-feedback" role="alert">
                                                                                        <strong>{{ $message }}</strong>
                                                                                    </span>
                                                                                @enderror
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="modal-footer justify-content-between">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>

                                                                <button type="submit" class="btn btn-success btn-sm mr-2" id="addButton_{{$item->id}}">Simpan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->
                                            </div>
                                            <!-- /.modal -->

                                            <!-- Modal Hapus -->
                                            <div class="modal fade" id="modal-hapus-{{$item->id}}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-danger">
                                                            <h4 class="tw-text-xl tw-font-bold">Hapus Penjualan</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p class="">Anda yakin ingin menghapus Penjualan <b>{{$item->barang->nama_barang}}</b> sejumlah <b>{{$item->qty}}</b> ini?</p>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>

                                                            <form action="{{url('/data-penjualan/' . $item->id)}}" method="post" class="">
                                                                @csrf
                                                                @method('DELETE')

                                                                <button type="submit" class="btn btn-danger btn-sm mr-2">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->
                                            </div>
                                            <!-- /.modal -->
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->

                    <!-- Modal Tambah Penjualan -->
                    <div class="modal fade text-sm" id="modal-tambah">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-info">
                                    <h4 class="tw-text-xl tw-font-bold">Tambah Penjualan</h4>
                                </div>
                                <form action="{{url('/data-penjualan')}}" method="post">
                                    @csrf

                                    <div class="modal-body">
                                        <div class="row justify-content-center">
                                            <div class="col-12 col-sm-12 col-lg-12">

                                                <!-- Nama Barang -->
                                                <div class="form-group row">
                                                    <label for="barang_id" class="col-12 col-sm-12 col-lg-12 col-form-label">Nama Barang</label>
                                                    <div class="input-group col-12 col-sm-12 col-lg-12">
                                                        <select name="barang_id" id="barang_id" class="form-control select2 @error('barang_id')
                                                            is-invalid
                                                        @enderror" style="width: 100%;">
                                                            <option value="" selected disabled>-- Pilih Barang --</option>

                                                            @foreach ($barangs as $barang)
                                                                <option value="{{$barang->id}}" data-stock="{{$barang->stock}}">{{$barang->nama_barang}}</option>
                                                            @endforeach
                                                        </select>
                                                        
                                                        @error('barang_id')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <!-- Stok Saat Ini -->
                                                <div class="form-group row">
                                                    <label for="stock_saat_ini" class="col-12 col-sm-12 col-lg-12 col-form-label">Stok Saat Ini</label>
                                                    <div class="input-group col-12 col-sm-12 col-lg-12">
                                                        <input type="number" class="form-control" id="stock_saat_ini" placeholder="Pilih Barang" value="" readonly disabled>
                                                    </div>
                                                </div>

                                                <!-- Jumlah Penjualan -->
                                                <div class="form-group row">
                                                    <label for="jumlah_penjualan" class="col-12 col-sm-12 col-lg-12 col-form-label">Jumlah Penjualan</label>
                                                    <div class="input-group col-12 col-sm-12 col-lg-12">
                                                        <input type="number" class="form-control @error('jumlah_penjualan') is-invalid @enderror" id="jumlah_penjualan" placeholder="Cth: 15" value="{{old('jumlah_penjualan')}}" name="jumlah_penjualan" required>

                                                        <!-- Alert Error -->
                                                        <div class="tw-font-bold invalid-feedback" id="errorAlert"></div>

                                                        <!-- Alert Sukses -->
                                                        <div class="tw-font-bold valid-feedback d-none" id="successAlert"></div>
                                                        
                                                        @error('jumlah_penjualan')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <!-- Tanggal Transaksi -->
                                                <div class="form-group row">
                                                    <label for="tanggal_transaksi" class="col-12 col-sm-12 col-lg-12 col-form-label">Tanggal Transaksi</label>
                                                    <div class="input-group col-12 col-sm-12 col-lg-12">
                                                        <input type="date" class="form-control @error('tanggal_transaksi') is-invalid @enderror" id="tanggal_transaksi" placeholder="Cth: 150000" value="{{old('tanggal_transaksi')}}" name="tanggal_transaksi" required>
    
                                                        @error('tanggal_transaksi')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>

                                        <button type="submit" class="btn btn-success btn-sm mr-2" id="addButton">Tambah</button>
                                    </div>
                                </form>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->

                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection