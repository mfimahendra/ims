@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">    
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/daterangepicker/daterangepicker.css') }}">

    <style>

        #tableLogs tr td{
            border: 1px solid #000;
            font-weight: 500;
        }

        #tableLogs tr th{
            background-color: #f4f6f9;
            text-align: center;
            border: 1px solid #000;
        }


    </style>
@endsection

@section('nav-title')
    Riwayat Transaksi
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="filter">Kategori</label>                                    
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-boxes-packing"></i>
                                            </span>
                                        </div>
                                        <select class="form-control select2" id="filter_category">
                                            <option value="all">Semua</option>
                                            <option value="incoming">Barang Masuk(IN)</option>
                                            <option value="outgoing">Barang Keluar(OUT)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-calendar-alt"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control float-right" id="filter_date">
                                    </div>                                    
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="filter">Barang</label>                                    
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-boxes-packing"></i>
                                            </span>
                                        </div>
                                        <select class="form-control select2" id="filter_material" multiple data-placeholder="Pilih barang">                                            
                                            @foreach ($materials as $material)
                                                <option value="{{ $material->material_description }}">{{ $material->material_description }}</option>                                                
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>                            
                        </div>    
                        <div class="row">
                            <div class="col">
                                <button class="btn btn-info btn-sm float-right" style="width: 100px;" onclick="searchLogs()">
                                    <i class="fas fa-search"></i>
                                    Cari
                                </button>                                
                                <button class="btn btn-danger btn-sm float-right" style="width: 100px; margin-right: 5px;" onclick="resetFilter()">
                                    <i class="fas fa-times"></i>
                                    Batal
                                </button>                                
                            </div>
                        </div>                    
                    </div>
                    <div class="card-body">
                        <div id="tableContainer"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script>
        $(document).ready(function() {

            $('body').addClass('sidebar-collapse');
        });



        function initTable() {
            $('#tableContainer').html(`
                <table id="tableLogs" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width:1%;">No</th>
                            <th>Tanggal</th>
                            <th>Kategori</th>
                            <th>Barang</th>
                            <th style="width:1%;">Banyaknya</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>                    
                </table>
            `);
        }

        function searchLogs() {
            initTable();

            var category = $('#filter_category').val();
            var date = $('#filter_date').val();
            var material = $('#filter_material').val();

            var materialQuery = '';
            if (material.length > 0) {
                materialQuery = `&material=${material}`;
            }            
            
            $.ajax({
                url: `{{ route('warehouse.fetchTransactionLogs') }}?category=${category}&date=${date}${materialQuery}`,
                type: 'GET',
                success: function(response) {
                    
                    let data = response.logs;
                    let html = '';

                    $.each(data, function(index, value) {
                        let transaction_category = value.category;                        

                        if(transaction_category == 'incoming') {
                            // html += '<tr style="background-color: #b0ff9d;">';                            
                            html += '<tr>';
                        } else {
                            // html += '<tr style="background-color: #ff9d9d;">';
                            html += '<tr>';
                        }

                        html += '<td style="text-align:center;">' + (index + 1) + '</td>';
                        html += '<td>' + value.date + '</td>';
                        
                        if(transaction_category == 'incoming') {
                            html += '<td style="color: green;">';
                            html += '<i class="fas fa-arrow-up"></i> '
                            html += 'Masuk';
                            html += '</td>';
                        } else {
                            html += '<td style="color: red;">';
                            html += '<i class="fas fa-arrow-down"></i> '
                            html += 'Keluar';
                            html += '</td>';
                        }                                            
                        html += '<td>' + value.material_description + '</td>';
                        html += '<td style="text-align:center;">' + value.quantity + '</td>';
                        html += '<td>' + convertToIDR(value.price) + '</td>';

                        let total = value.quantity * value.price;

                        html += '<td>' + convertToIDR(total) + '</td>';
                        html += '</tr>';                                                
                    });

                    $('#tableLogs tbody').html(html);

                    var table = $("#tableLogs").DataTable({
                        "responsive": true,
                        "lengthChange": false,
                        "autoWidth": false,
                        "searching": true,
                        lengthMenu: [
                            [10, 25, 50, -1],
                            [10, 25, 50, "All"]
                        ],
                        "buttons": ["pageLength", "copy", "excel", "pdf", "print"],
                        // hide column index 5
                        // "columnDefs": [{
                        //     "targets": [4,6],
                        //     "visible": false
                        // }]
                    });

                    table.buttons().container().appendTo('#tableLogs_wrapper .col-md-6:eq(0)');
                }
            });
        }

        $(function() {

            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });

            $('.select2').select2({
                theme: 'bootstrap4'
            });

            $('#filter_date').daterangepicker();


        });
    </script>
    <!-- Add your JavaScript code here -->
@endsection
