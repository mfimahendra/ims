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
        #table_journal_transactions{
            width: 100%;            
        }

        #table_journal_transactions th, #table_journal_transactions td {
            white-space: nowrap;
        }

        #table_journal_transactions th {
            background-color: #f4f6f9;
        }

        #table_journal_transactions th, #table_journal_transactions td {
            text-align: center;
            font-size: 14px;
            padding: 5px;
        }

    </style>
@endsection

@section('nav-title')
    Riwayat Transaksi
@endsection

@section('content-header')
    <div class="row">
        <div class="col-10"></div>
        <div class="col-2" style="text-align: right;">
            <button class="btn bg-green btn-sm">
                <i class="fa-solid fa-plus"></i>
                Tambah Transaksi
            </button>
        </div>
    </div>
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
                                    <label for="filter">Debit</label>                                    
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-boxes-packing"></i>
                                            </span>
                                        </div>
                                        <select class="form-control select2" id="account_debit_filter" data-placeholder="Pilih Akun Debit">
                                            <option value="">-- Pilih Akun --</option>
                                            @foreach ($accounts as $account)
                                                <option value="{{ $account->account_id }}">{{ $account->account_id }}</option>\
                                            @endforeach        
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="filter">Kredit</label>                                    
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-boxes-packing"></i>
                                            </span>
                                        </div>
                                        <select class="form-control select2" id="account_kredit_filter" data-placeholder="Pilih Akun Kredit">
                                            <option value="">-- Pilih Akun --</option>
                                            @foreach ($accounts as $account)
                                                <option value="{{ $account->account_id }}">{{ $account->account_id }}</option>
                                            @endforeach        
                                        </select>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                        <div class="row">
                            <div class="col" style="text-align: right;">
                                <div class="form-group">                                    
                                    <button class="btn bg-green btn-sm" onclick="fetchData()">
                                        <i class="fa-solid fa-search"></i>
                                        Cari
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div id="table_container"></div>
                            </div>
                        </div>        
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- modal quick add journal transaction --}}
    <div class="modal fade" id="modal-quick-add-journal-transaction">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-green">
                    <h4 class="modal-title">Tambah Transaksi</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="form-quick-add-journal-transaction">
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-6">
                                <label for="date">Tanggal</label>
                                <input type="text" id="date" name="date" class="form-control datepicker">
                            </div>
                            <div class="form-group col-6">
                                <label for="account">Akun</label>
                                <select id="account" name="account" class="form-control">
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->account_id }}">{{ $account->account_id }}</option>\
                                    @endforeach
                                </select>
                            </div>
                            
                        </div>
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
        var journal_transactions = [];

        $(document).ready(function() {
            $('body').addClass('sidebar-collapse');                        

            $('.select2').each((_i, e) => {
                var e = $(e);
                e.select2({
                    allowClear: true,
                    theme: 'bootstrap4',
                    dropdownParent: e.parent()
                });
            });

            // set filter select2 null
            $('#account_debit_filter').val(null).trigger('change');
            $('#account_kredit_filter').val(null).trigger('change');

            $('#search_date').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });

            fetchData();
        });

        $(function() {

            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });

            $('#filter_date').daterangepicker();

        });


        function fetchData() {
            $.ajax({
                url: '{{ route('financial.fetchJournalData') }}',
                type: 'GET',
                data: {
                    date: $('#filter_date').val(),
                    account_debit: $('#account_debit_filter').val(),
                    account_kredit: $('#account_kredit_filter').val()
                },
                success: function(response) {
                    let data = response.data;
                    renderTable(data);
                }
            });
        }

        function initTable() {
            $('#table_container').html('');

            let html = '';
            html += '<table id="table_journal_transactions" class="table table-bordered table-striped">';
            html += '<thead>';
            html += '<tr>';
            html += '<th>No</th>';
            html += '<th>Tanggal</th>';
            html += '<th>Deskripsi</th>';
            html += '<th>Sumber</th>';
            html += '<th>Pajak</th>';
            html += '<th>Nominal</th>';
            html += '<th>Debit</th>';
            html += '<th>Kredit</th>';
            html += '<th>#</th>';
            html += '</tr>';
            html += '</thead>';
            html += '<tbody>';
            html += '</tbody>';

            $('#table_container').html(html);
        }

        function renderTable(data) {
            initTable();

            let journal_data = data;

            // if undefined
            if (journal_data == undefined) {
                journal_data = [];
            }

            let html = '';           

            journal_data.forEach((journal, index) => {
                html += '<tr>';
                html += '<td>' + (index + 1) + '</td>';
                html += '<td>' + journal.date + '</td>';
                html += '<td>' + journal.description + '</td>';
                html += '<td>' + journal.source + '</td>';
                html += '<td>' + journal.is_tax + '</td>';
                html += '<td>' + journal.price + '</td>';
                html += '<td>' + journal.debit + '</td>';
                html += '<td>' + journal.kredit + '</td>';
                html += '<td>';
                html += '<button class="btn btn-warning btn-sm" onclick="editJournalTransaction(' + journal.id + ')" style="margin:2px;">';
                html += '<i class="fa-solid fa-edit"></i>';
                html += '</button>';
                html += '<button class="btn bg-red btn-sm" onclick="deleteJournalTransaction(' + journal.id + ')">';
                html += '<i class="fa-solid fa-trash"></i>';
                html += '</button>';
                html += '</td>';
                html += '</tr>';
            });

            $('#table_journal_transactions tbody').html(html);

        }

    </script>
    <!-- Add your JavaScript code here -->
@endsection
