@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}"> {{-- public/adminlte/plugins/select2/css/select2.min.css --}}
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">    
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/daterangepicker/daterangepicker.css') }}">        
    <style>
        #inventories_table {
            width: 100% !important;
        }

        #inventories_table tr td {
            padding: 0.2rem;
        }

        .table_button button {
            margin: 2px;
        }
    </style>
@endsection

@section('nav-title')
    Gudang
@endsection


@section('content-header')
    
@endsection

@section('content')
    <div class="container">
        <div class="row" id="resume_container">
        </div>
        <div class="row">
            <div class="col-12">
                <div id="inventories_table_container">
                    <center>
                        <i class="fa fa-spinner fa-spin"></i> Memuat data...
                    </center>
                </div>
            </div>
        </div>
    </div>

    {{-- showMoreModal --}}
    <div class="modal fade" id="showMoreModal" tabindex="-1" aria-labelledby="showMoreModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="showMoreModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>                
                <div class="modal-body">                    
                    <div id="showMoreTable_container"></div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- modalEditDatatable --}}
    <div class="modal fade" id="modalEditDatatable" tabindex="-1" aria-labelledby="modalEditDatatableLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditDatatableLabel">Edit Data Barang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">                    
                    <div class="form-group">
                        <label for="material_description">Jenis Barang</label>
                        <input type="text" class="form-control" id="material_description" name="material_description">
                    </div>
                    <div class="form-group">
                        <label for="quantity">Stok</label>
                        <input type="number" class="form-control" id="quantity" name="quantity">
                    </div>
                    <div class="form-group">
                        <label for="price">Harga</label>
                        <input type="number" class="form-control" id="price" name="price">
                    </div>
                    <button type="submit" class="btn btn-primary" onclick="postUpdateInventory()">Simpan</button>
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
        var inventories = [];

        $(document).ready(function() {
            fetchData();

            $('body').addClass('sidebar-collapse');

            // tes truka
            console.log(convertToINT('Rp 1.250.000'));

        });

        function fetchData() {
            $.get("{{ route('warehouse.fetchInventory') }}", function(result) {
                if (result.status == 200) {
                    inventories = result.data;
                    renderTable();
                    renderResume();
                } else {
                    console.log(result.message);
                }
            });
        }

        function renderTable() {
            initTable();

            let html = '';

            inventories.forEach((inventory, index) => {
                html += '<tr>';
                html += '<td style="text-align:center;">' + (index + 1) + '</td>';
                html += '<td>' + inventory.material_description + '</td>';

                let quantity = inventory.quantity;

                if (quantity <= 0) {
                    html += '<td style="text-align:center; background-color:red; color:white;">' + quantity + '</td>';
                } else {
                    html += '<td style="text-align:center;">' + quantity + '</td>';
                }

                let price = convertToIDR(inventory.price);
                html += '<td>' + price + '</td>';
                
                html += '<td>' + inventory.price + '</td>';


                let total = convertToIDR(inventory.price * inventory.quantity);
                html += '<td>' + total + '</td>';

                html += '<td>' + (inventory.price * inventory.quantity) + '</td>';

                html += '<td class="table_button" style="text-align:center;">';
                html += '<button class="btn btn-sm btn-warning" onclick="editInventory(' + inventory.id + ')"><i class="fa fa-edit"></i></button>';
                html += '<button class="btn btn-sm btn-info" onclick="historyInventory(' + inventory.id + ')"><i class="fa fa-history"></i></button>';
                html += '</td>';
                html += '</tr>';
            });

            $('#inventories_table_body').html(html);

            // $('#inventories_table tfoot th').each(function() {
            //     var title = $(this).text();

            //     // only index 1 show select filter inventories.material_description using select2                                
            //     if ($(this).index() == 1) {
            //         let html = '';
            //         html += '<select class="form-control form-control-sm select_data_material" style="width: 100%;">';
            //         html += '<option value="">Semua</option>';
            //         inventories.forEach(inventory => {
            //             html += '<option value="' + inventory.material_description + '">' + inventory.material_description + '</option>';
            //         });
            //         html += '</select>';
            //         $(this).html(html);

            //     } else {
            //         $(this).html('');
            //     }
            // });

            var table = $("#inventories_table").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "searching": true,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                "buttons": ["pageLength", "copy", "excel", "pdf", "print", "colvis"],
                // hide column index 5
                "columnDefs": [{
                    "targets": [4,6],
                    "visible": false
                }]
            });

            // table tfoot add total sum but pass to convertToINT
            // table.columns([2, 3, 5]).every(function() {
            //     var sum = this
            //         .data()
            //         .reduce(function(a, b) {
            //             return convertToINT(a) + convertToINT(b);
            //         }, 0);

            //     $(this.footer()).html(convertToIDR(sum));
            // });

            table.buttons().container().appendTo('#inventories_table_wrapper .col-md-6:eq(0)');

            // Apply the search
            // table.columns().every(function() {
            //     var that = this;

            //     $('input', this.footer()).on('keyup change clear', function() {
            //         if (that.search() !== this.value) {
            //             that
            //                 .search(this.value)
            //                 .draw();
            //         }
            //     });

            //     $('select', this.footer()).on('change', function() {
            //         var val = $.fn.dataTable.util.escapeRegex(
            //             $(this).val()
            //         );

            //         that
            //             .search(val ? '^' + val + '$' : '', true, false)
            //             .draw();
            //     });
            // });

            // $('#inventories_table tfoot tr').appendTo('#inventories_table thead');



            $('.select_data_material').select2({
                placeholder: 'Pilih Jenis Barang',
                allowClear: true
            });
        }

        function editInventory(id) {                        
            $('#material_description').val('');
            $('#quantity').val('');
            $('#price').val('');

            let inventory = inventories.find(inventory => inventory.id == id);

            $('#material_description').val(inventory.material_description);
            $('#quantity').val(inventory.quantity);
            $('#price').val(inventory.price);

            $('#modalEditDatatable').modal('show');
        }

        function postUpdateInventory(id) {
            let inventory = inventories.find(inventory => inventory.id == id);

            $('#material_description').val(inventory.material_description);
            $('#quantity').val(inventory.quantity);
            $('#price').val(inventory.price);

            $('#modalEditDatatable').modal('show');

            $('#formEditDatatable').submit(function(e) {
                e.preventDefault();

                let data = {
                    id: id,
                    material_description: $('#material_description').val(),
                    quantity: $('#quantity').val(),
                    price: $('#price').val()
                };

                $.post("{{ route('warehouse.updateInventory') }}", data, function(result) {
                    if (result.status == 200) {
                        $('#modalEditDatatable').modal('hide');
                        fetchData();
                    } else {
                        console.log(result.message);
                    }
                });
            });
        }

        function showMoreModal(category) {
            let title = '';
            let data = [];

            $('#showMoreTable_container').html('');

            switch (category) {
                case 'in':
                    title = 'Barang Masuk';
                    modalDataIncomings();

                    break;
                case 'out':
                    title = 'Barang Keluar';

                    break;
                case 'empty':
                    title = 'Stok Habis';                    
                    modalDataStokHabis();
                    break;
                case 'amount':
                    title = 'Nilai Materil';
                    data = inventories;
                    break;

                default:
                    break;

            }

            $('#showMoreModalLabel').html(title);
            $('#showMoreModal').modal({
                backdrop: 'static',
                keyboard: false
            });
            $('#showMoreModal').modal('show');
        }

        function modalDataIncomings(){
            let searchField = '';

            // searchfield date from and date to
            searchField += '<div class="row">';            
            searchField += '<div class="col-4 form-group">';            
            // input group
            searchField += '<div class="input-group">';
            searchField += '<div class="input-group-prepend">'; 
            searchField += '<span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>';
            searchField += '</div>';
            searchField += '<input type="text" class="form-control datepicker" id="date_from" name="date_from" placeholder="Tanggal dari">';
            searchField += '</div>';
            searchField += '</div>';
            // end input group
                    
            searchField += '<div class="col-4 form-group">';            
            searchField += '<div class="input-group">';
            searchField += '<div class="input-group-prepend">'; 
            searchField += '<span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>';
            searchField += '</div>';
            searchField += '<input type="text" class="form-control datepicker" id="date_to" name="date_to" placeholder="Tanggal Sampai">';
            searchField += '</div>';
            searchField += '</div>';
            
            searchField += '<div class="col-4">';
            searchField += '<label for=""></label>';
            searchField += '<button class="btn btn-primary" onclick="searchDataIncomings()">Cari</button>';
            searchField += '</div>';

            $('#showMoreTable_container').html(searchField);

            $('.datepicker').daterangepicker({
                singleDatePicker: true,                
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });

            let initTable = '';


        }


        function modalDataStokHabis() {
            let html = '';

            data = inventories.filter(inventory => inventory.quantity <= 0);

            let initTable = '';

            initTable += '<table id="showMoreTable" class="table table-bordered table-hover">';
            initTable += '<thead>';
            initTable += '<tr>';
            initTable += '<th style="width:1%; text-align:center;">#</th>';
            initTable += '<th>Jenis Barang</th>';
            initTable += '<th style="text-align:center; width:1%;">Stok</th>';
            initTable += '<th>Harga</th>';
            initTable += '<th>Jumlah</th>';
            initTable += '<th style="width:15%;">#</th>';
            initTable += '</tr>';
            initTable += '</thead>';
            initTable += '<tbody id="showMoreTableBody">';
            initTable += '</tbody>';
            initTable += '</table>';

            $('#showMoreTable_container').html(initTable);

            data.forEach((inventory, index) => {
                html += '<tr>';
                html += '<td style="text-align:center;">' + (index + 1) + '</td>';
                html += '<td>' + inventory.material_description + '</td>';

                let quantity = inventory.quantity;

                if (quantity <= 0) {
                    html += '<td style="text-align:center; background-color:red; color:white;">' + quantity + '</td>';
                } else {
                    html += '<td style="text-align:center;">' + quantity + '</td>';
                }

                let price = convertToIDR(inventory.price);
                html += '<td>' + price + '</td>';

                let total = convertToIDR(inventory.price * inventory.quantity);
                html += '<td>' + total + '</td>';

                html += '<td class="table_button" style="text-align:center;">';
                html += '<button class="btn btn-sm btn-warning" onclick="editInventory(' + inventory.id + ')"><i class="fa fa-edit"></i></button>';
                html += '<button class="btn btn-sm btn-info" onclick="historyInventory(' + inventory.id + ')"><i class="fa fa-history"></i></button>';
                html += '</td>';
                html += '</tr>';
            });

            $('#showMoreTableBody').html(html);            

            // datatable
            var showMoreTable = $("#showMoreTable").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                "buttons": ["pageLength", "copy", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#showMoreTable_wrapper .col-md-6:eq(0)');
        }



        function renderResume() {
            $('#resume_container').html('');
            let html = '';

            let box_parameter = ['in', 'out', 'empty', 'amount']
            let box_header_title = ['Total Barang Masuk', 'Barang Keluar', 'Stok Habis', 'Nilai Materil'];
            let box_header_subtitle = ['Bulan ini', 'Bulan ini', '&nbsp;', '&nbsp;'];
            let box_color = ['bg-primary', 'bg-success', 'bg-danger', 'bg-info'];
            let box_icon = ['fa-boxes-packing', 'fa-arrow-right-from-bracket', 'fa-boxes', 'fa-money-bill-wave'];

            box_parameter.forEach((title, index) => {
                html += '<div class="col-3">';
                html += '<div class="small-box ' + box_color[index] + '" onclick="showMoreModal(\'' + title + '\')" style="cursor:pointer;">';
                html += '<div class="inner">';
                html += '<h3 style="font-size:1.5rem;" id="box_' + title + '">0</h3>';
                html += '<p style="margin:0;">' + box_header_title[index] + '</p>';
                html += '<small>' + box_header_subtitle[index] + '</small>';
                html += '</div>';
                html += '<div class="icon">';
                html += '<i class="fas ' + box_icon[index] + '"></i>';
                html += '</div>';                
                html += '</div>';
                html += '</div>';
            });

            $('#resume_container').html(html);


            // handle data
            let total_in = inventories.reduce((total, inventory) => {
                return total + inventory.quantity_in;
            }, 0);
            // total_in Nan null or undefined = 0
            total_in = total_in ? total_in : 0;

            let total_out = inventories.reduce((total, inventory) => {
                return total + inventory.quantity_out;
            }, 0);
            // total_out Nan null or undefined = 0
            total_out = total_out ? total_out : 0;

            let total_empty = inventories.filter(inventory => inventory.quantity <= 0).length;
            total_empty = total_empty ? total_empty : 0;

            let total_amount = inventories.reduce((total, inventory) => {
                return total + (inventory.price * inventory.quantity);
            }, 0);
            // total_amount Nan null or undefined = 0
            total_amount = total_amount ? total_amount : 0;

            $('#box_in').html(total_in);
            $('#box_out').html(total_out);
            $('#box_empty').html(total_empty);
            $('#box_amount').html(convertToIDR(total_amount));
        }

        function initTable() {
            $('#inventories_table_container').html('');

            let table = '';
            table += '<table id="inventories_table" class="table table-bordered table-hover">';
            table += '<thead>';
            table += '<tr>';
            table += '<th style="width:1%; text-align:center;">#</th>';
            table += '<th>Jenis Barang</th>';
            table += '<th style="text-align:center; width:1%;">Stok</th>';
            table += '<th>Harga</th>';
            table += '<th>Harga</th>';
            table += '<th>Jumlah</th>';
            table += '<th>Jumlah</th>';
            table += '<th style="width:10%;">#</th>';
            table += '</tr>';
            table += '</thead>';
            table += '<tbody id="inventories_table_body">';
            table += '</tbody>';
            table += '<tfoot>';
            table += '<tr>';
            table += '<th style="width:1%; text-align:center;">#</th>';
            table += '<th>Jenis Barang</th>';
            table += '<th style="text-align:center; width:1%;">Stok</th>';
            table += '<th>Harga</th>';
            table += '<th>Harga</th>';
            table += '<th>Jumlah</th>';
            table += '<th>Jumlah</th>';
            table += '<th style="width:10%;">#</th>';
            table += '</tr>';
            table += '</tfoot>';
            table += '</table>';

            $('#inventories_table_container').html(table);
        }

        // event listener                
        
        $(function() {                        

            //Date range picker
            $('.datepicker').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });


        });
    </script>
@endsection
