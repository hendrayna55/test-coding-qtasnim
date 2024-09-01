@extends('layouts.app')

@section('title')
    Chart Penjualan
@endsection

@push('styles')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('AdminLTE')}}/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{asset('AdminLTE')}}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endpush

@php
    $rentangWaktu = ['All Time', 'Kemarin', '3 Hari', '7 Hari', '14 Hari', '30 Hari', '90 Hari', '180 Hari'];
@endphp

@push('scripts')
    <!-- Select2 -->
    <script src="{{asset('AdminLTE')}}/plugins/select2/js/select2.full.min.js"></script>

    <script>
        $(document).ready(function(){
            $('#rentang_waktu').select2();
        });
    </script>
    <!-- ChartJS -->
    <script src="{{asset('AdminLTE')}}/plugins/chart.js/Chart.min.js"></script>

    <!-- Page specific script -->
    <script>
        $(function () {
            const dataBarang = @json($barangs);
            const dataPenjualan = @json($penjualans);
            const predefinedColors = ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de', '#d81b60', '#3c8dbc', '#00a65a', '#f39c12'];

            function filterDataByRentangWaktu(rentangWaktu) {
                if (rentangWaktu === 'All Time') {
                    return dataPenjualan;  // Jika "All Time" dipilih, kembalikan semua data penjualan
                }

                const today = new Date();
                let startDate;

                switch (rentangWaktu) {
                    case 'Hari Ini':
                        startDate = new Date(today);
                        break;
                    case 'Kemarin':
                        startDate = new Date(today);
                        startDate.setDate(today.getDate() - 1);
                        break;
                    case '3 Hari':
                        startDate = new Date(today);
                        startDate.setDate(today.getDate() - 3);
                        break;
                    case '7 Hari':
                        startDate = new Date(today);
                        startDate.setDate(today.getDate() - 7);
                        break;
                    case '14 Hari':
                        startDate = new Date(today);
                        startDate.setDate(today.getDate() - 14);
                        break;
                    case '30 Hari':
                        startDate = new Date(today);
                        startDate.setDate(today.getDate() - 30);
                        break;
                    case '90 Hari':
                        startDate = new Date(today);
                        startDate.setDate(today.getDate() - 90);
                        break;
                    case '180 Hari':
                        startDate = new Date(today);
                        startDate.setDate(today.getDate() - 180);
                        break;
                    default:
                        startDate = new Date(today);
                        startDate.setDate(today.getDate() - 90); // Default ke 90 hari jika tidak ada yang cocok
                }

                const filteredPenjualans = dataPenjualan.filter(penjualan => {
                    const tanggalTransaksi = new Date(penjualan.tanggal_transaksi);
                    return tanggalTransaksi >= startDate && tanggalTransaksi <= today;
                });

                return filteredPenjualans;
            }

            function updateChartData(rentangWaktu) {
                const filteredPenjualans = filterDataByRentangWaktu(rentangWaktu);
                const labelBarang = [];
                const jumlahPenjualan = [];
                const backgroundColor = [];

                dataBarang.forEach((item, index) => {
                    const qtyPenjualan = filteredPenjualans
                        .filter(penjualan => penjualan.barang_id === item.id)
                        .reduce((total, penjualan) => total + penjualan.qty, 0);

                    if (qtyPenjualan > 0) {
                        labelBarang.push(item.nama_barang);
                        jumlahPenjualan.push(qtyPenjualan);
                        backgroundColor.push(predefinedColors[index % predefinedColors.length]);
                    }
                });

                // Perbarui data chart
                donutData.labels = labelBarang;
                donutData.datasets[0].data = jumlahPenjualan;
                donutData.datasets[0].backgroundColor = backgroundColor;
                pieChart.update();
            }

            // Inisialisasi chart dengan data awal
            var pieChartCanvas = $('#pieChart').get(0).getContext('2d');
            var donutData = {
                labels: [],
                datasets: [
                    {
                        data: [],
                        backgroundColor: [],
                    }
                ]
            };
            var pieOptions = {
                maintainAspectRatio: false,
                responsive: true,
            };
            var pieChart = new Chart(pieChartCanvas, {
                type: 'pie',
                data: donutData,
                options: pieOptions
            });

            // Initial load berdasarkan rentang waktu default
            const initialRentangWaktu = $('#rentang_waktu').val();
            updateChartData(initialRentangWaktu);

            // Event listener untuk rentang waktu
            $('#rentang_waktu').change(function () {
                const selectedRentangWaktu = $(this).val();
                updateChartData(selectedRentangWaktu);
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
                        <h1>Chart Penjualan</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">Chart Penjualan</li>
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
                        <!-- Chart Penjualan -->
                        <div class="card card-danger">
                            <div class="card-header">
                                <h3 class="card-title">Chart Penjualan</h3>
            
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>

                                <div class="row justify-content-center">
                                    <div class="col-12 col-sm-12 col-lg-12">
                                        <!-- Rentang Waktu -->
                                        <div class="form-group row">
                                            <label for="rentang_waktu" class="col-12 col-sm-12 col-lg-12 col-form-label">Rentang Waktu</label>
                                            <div class="input-group col-12 col-sm-12 col-lg-12">
                                                <select name="rentang_waktu" id="rentang_waktu" class="form-control select2" style="width: 100%;">
                                                    <option value="" disabled>-- Pilih Rentang Waktu --</option>

                                                    @foreach ($rentangWaktu as $item)
                                                        <option value="{{$item}}" {{$item == 'All Time' ? 'selected' : ''}}>{{$item}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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