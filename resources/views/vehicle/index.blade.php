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
    Kendaraan
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-5">
                <div class="card">
                    <div class="card-body p-1">
                        <table id="tableVehicleLists" class="table table-hovered table-bordered" style="font-size: 14px;">
                            <thead>
                                <tr>
                                    <th class="p-2">No</th>
                                    <th class="p-2">Nama</th>
                                    <th class="p-2">Plat Nomor</th>
                                    <th class="p-2">Driver</th>
                                    <th class="p-2">#</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <div class="col-3">

            </div>
            <div class="col-4">

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

        var vehicles = {!! json_encode($vehicles) !!};

        $(document).ready(function() {
            $('body').addClass('sidebar-collapse');            

            renderTableVehicleLists();
        });

        function renderTableVehicleLists() {
            
            let html = '';            

            vehicles.forEach((vehicle, index) => {
                html += `
                    <tr>
                        <td class="p-2">${index + 1}</td>
                        <td class="p-2">${vehicle.vehicle_description}</td>
                        <td class="p-2">${vehicle.vehicle_id}</td>
                        <td class="p-2">${vehicle.driver_name ? vehicle.driver_name : '-'}</td>
                        <td class="p-2">
                            <button class="btn btn-sm btn-primary" onclick="editVehicle(${vehicle.id})">Edit</button>
                            <button class="btn btn-sm btn-danger" onclick="deleteVehicle(${vehicle.id})">Delete</button>
                        </td>
                    </tr>
                `;
            });

            $('#tableVehicleLists tbody').html(html);

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
