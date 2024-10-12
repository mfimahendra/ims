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

    </style>
@endsection

@section('nav-title')
    Riwayat Transaksi
@endsection

@section('content-header')
    <button class="btn bg-green btn-sm float-right">
        <i class="fa-solid fa-plus"></i>
        Tambah Transaksi
    </button>
@endsection

@section('content')
    <div class="container">        
        <div class="row">    
            <div class="col-3"></div>        
            <div class="col-6">
                <div class="row">                    
                    <div class="form-group col-6">
                        <label for="date">Tanggal</label>
                        <input type="text" id="search_date" class="form-control">
                    </div>
                    <div class="col-6"></div>
                    <div class="form-group col-6">
                        <label for="account">Debit</label>
                        <select id="search_debit" class="form-control">
                            <option value="">-- Pilih Akun --</option>
                        </select>
                    </div>
                    <div class="form-group col-6">
                        <label for="account">Kredit</label>
                        <select id="search_credit" class="form-control">
                            <option value="">-- Pilih Akun --</option>
                        </select>
                    </div>
                </div>
            </div>                        
            <div class="col-3"></div>
        </div>
        <div class="row">
            <table class="table table-hovered table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Remark</th>
                        <th>Sumber</th>
                        <th>Saldo</th>
                        <th>Debit</th>
                        <th>Kredit</th>
                    </tr>                    
                </thead>
                <tbody>                    
                </tbody>
            </table>
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

        $(function() {

        });
    </script>
    <!-- Add your JavaScript code here -->
@endsection
