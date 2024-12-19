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
        #table_account {
            width: 100%;
        }

        #table_account th {
            font-size: 12px;            
        }
        
        #table_account td {
            font-size: 12px;
            padding: 2px 0 0 4px;
            vertical-align: middle;
            background-color: #fff;
        }

        .align-right {
            text-align: right;
        }

        .label_input {
            padding-top: 5px;            
            font-weight: 500;
        }
    </style>
@endsection

@section('nav-title')
    Menu Financial
@endsection

@section('content')
    <div class="container">        
        <div class="row">
            <div class="col-12">
                <div id="table_container"></div>
            </div>
        </div>
    </div>


    {{-- modal Edit --}}
    <div class="modal fade" id="modalEditAccount" tabindex="-1" aria-labelledby="modalEditAccountLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-purple">
                    <h5 class="modal-title" id="modalEditAccountLabel">Edit Akun Keuangan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="formEditAccount">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-2 align-right">
                                    <label class="label_input" for="input_account_id">Akun</label>
                                </div>
                                <div class="col-10">
                                    <input type="text" class="form-control" id="input_account_id" name="account_id" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-2 align-right">
                                    <label class="label_input" for="input_sn">SN</label>
                                </div>
                                <div class="col-10">
                                    <input type="text" class="form-control" id="input_sn" name="sn" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-2 align-right">
                                    <label class="label_input" for="input_pos">POS</label>
                                </div>
                                <div class="col-10">
                                    <input type="text" class="form-control" id="input_pos" name="pos" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-2 align-right">
                                    <label class="label_input" for="input_category">Kategori</label>
                                </div>
                                <div class="col-10">
                                    <input type="text" class="form-control" id="input_category" name="category" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-2 align-right">
                                    <label class="label_input" for="input_saldo">Saldo</label>
                                </div>
                                <div class="col-10">
                                    <input type="text" class="form-control" id="input_saldo" name="saldo" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="updateAccount()">Simpan</button>
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

        var accounts_data = [];

        $(document).ready(function() {
            fetchData();
        });

        $(function() {

            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });

            $('.select2').select2({
                theme: 'bootstrap4'
            });

            $('#filter_date').daterangepicker();
        });

        function editAccount(id) {
            let account = accounts_data.find(item => item.id == id);

            $('#input_account_id').val(account.account_id);
            $('#input_sn').val(account.sn);
            $('#input_pos').val(account.pos);
            $('#input_category').val(account.category);
            $('#input_saldo').val(account.saldo);

            $('#modalEditAccount').modal('show');
            
        }

        function fetchData() {

            $.ajax({
                url: "{{ route('financial.fetchAccountData') }}",
                type: 'GET',
                success: function(response) {
                    if(response.status == 'success') {
                        accounts_data = response.data;
                        renderData(response.data);
                    } else {
                        toastr.error('Terjadi kesalahan sistem');
                    }
                }, error: function(xhr) {
                    toastr.error('Terjadi kesalahan pada Server saat mengambil data');
                }
            });

        }

        function renderData(data) {
            initTable();

            let html = '';

            data.forEach((item, index) => {
                let saldo_rupiah = convertToIDR(item.saldo);

                html += `
                    <tr>
                        <td style="text-align:center;">${index + 1}</td>
                        <td>${item.account_id}</td>
                        <td>${item.sn}</td>
                        <td>${item.pos}</td>
                        <td>${item.category}</td>
                        <td>${item.saldo}</td>
                        <td>${saldo_rupiah}</td>                        
                        <td>
                            <button class="btn btn-xs bg-yellow" onclick="editAccount(${item.id})">Edit</button>
                            <button class="btn btn-xs btn-danger" onclick="deleteAccount(${item.id})">Delete</button>
                        </td>
                    </tr>
                `;
            });

            $('#table_container table tbody').html(html);

            // datatable
            $('#table_account').DataTable({                
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });

        }


        function initTable() {

            $('#table_container').html('');

            let html = '';

            html += '<table id="table_account" class="table table-bordered table-hovered">'
            html += '<thead class="bg-white">'
            html += '<tr>'
            html += '<th style="width:0.1%;">No</th>'
            html += '<th>Akun</th>'
            html += '<th>SN</th>'
            html += '<th>POS</th>'
            html += '<th>Kategori</th>'
            html += '<th>Saldo</th>'
            html += '<th>Saldo Rupiah</th>'
            html += '<th>#</th>'
            html += '</tr>'
            html += '</thead>'
            html += '<tbody>'
            html += '</tbody>'
            html += '</table>'

            $('#table_container').html(html);
        }

        function updateAccount() {
            let account_id = $('#input_account_id').val();
            let sn = $('#input_sn').val();
            let pos = $('#input_pos').val();
            let category = $('#input_category').val();
            let saldo = $('#input_saldo').val();

            let formData = new FormData();

            formData.append('account_id', account_id);
            formData.append('sn', sn);
            formData.append('pos', pos);
            formData.append('category', category);
            formData.append('saldo', saldo);
            formData.append('_token', '{{ csrf_token() }}');


            $.ajax({
                url: "{{ route('financial.updateAccount') }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if(response.status == 'success') {
                        toastr.success('Data berhasil diupdate');
                        $('#modalEditAccount').modal('hide');
                        fetchData();
                    } else {
                        toastr.error('Terjadi kesalahan sistem');
                    }
                }, error: function(xhr) {
                    toastr.error('Terjadi kesalahan pada Server saat mengambil data');
                }
            });
        }
    </script>
    <!-- Add your JavaScript code here -->
@endsection
