@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">    
    <link rel="stylesheet" href="{{ asset('js/gijgo/css/gijgo.min.css') }}">
    <style>
        #title_value {
            font-size: 1.2em;
            font-weight: 600;
            text-align: center;
        }

        #title_value_additional{
            font-size: 1.2em;
            font-weight: 600;
            text-align: center;
        }

        .index-title {
            color: #333;
            font-size:
        }

        #tableSlip tr th {
            font-size: 14px;
            text-align: center;
            padding: 5px;
            vertical-align: middle;
        }
        #tableSlip tr td {            
            padding: 5px;
        }

        #tableNota tr th {
            font-size: 14px;
            text-align: center;
            padding: 5px;
            vertical-align: middle;
        }
        #tableNota tr td {            
            padding: 5px;
        }

        #tableAdditional tr th {
            font-size: 14px;
            text-align: center;
            padding: 5px;
            vertical-align: middle;
        }

        #tableAdditional tr td {
            padding: 5px;
        }

        .label_td{
            font-weight: bold;
            width: 10%;
            background-color: darkgray;;
        }

        .value_selected{
            width: 50%;
            background-color: #f3f3f3;
        }
        
        .slip_btn:nth-child(even):hover{
            background-color: #fff000;
            cursor: pointer;
        }

        .slip_btn:nth-child(odd):hover{
            background-color: #fff000;
            cursor: pointer;
        }

        .slip_container > * {
            
        }
        
    </style>
@endsection

@section('nav-title')
    Pengeluaran Harian Sparepart
@endsection

@section('content-header')
    {{-- <div class="row">
        <div class="col-12" align="right">
            <button class="btn btn-sm btn-primary">
                <i class="fa fa-history"></i>
                Riwayat Pengeluaran Sparepart
            </button>
        </div>
    </div> --}}
@endsection

@section('content')
    <div class="container">
        {{-- <div class="row">
            <div class="col-2">List Pengeluaran</div>
            <div class="col-6">Detail Pengeluaran</div>
            <div class="col-4">Input Pencatatan</div>
        </div> --}}
        <div class="row">
            <div class="col-2">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body p-0">
                                <table id="tableSlip" class="table table-bordered table-striped table-hovered">
                                    <thead>
                                        <tr>
                                            <th style="width:1%;">#</th>
                                            <th>Slip</th>
                                        </tr>
                                    </thead>
                                    <tbody>                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <input type="hidden" id="value_outgoing_id">
                                <div class="row">
                                    <div class="col-4" style="font-weight: 600;">
                                        Slip : <span id="value_slip"></span>   
                                    </div>
                                    <div class="col-4" style="font-weight: 600;">
                                        Akun : <span id="value_account"></span>
                                    </div>            
                                    <div class="col-1"></div>                        
                                    <div class="col-3">
                                        {{-- delete button --}}
                                        <button id="deleteSlipBtn" class="btn btn-sm btn-danger float-right" style="display: none;" onclick="deleteThisSlip()">
                                            <i class="fa fa-trash"></i> Hapus Slip
                                        </button>
                                    </div>
                                </div>

                                <hr>                                
                                <table class="table table-bordered">
                                    <tr>
                                        <td class="label_td">Kategori</td>
                                        <td id="value_category" class="value_selected"></td>                                        
                                        <td class="label_td">Tanggal</td>
                                        <td id="value_date" class="value_selected"></td>
                                    </tr>
                                    <tr>
                                        <td class="label_td">Kendaraan</td>
                                        <td id="value_vehicle" class="value_selected"></td>

                                        <td class="label_td">Sopir</td>
                                        <td id="value_driver" class="value_selected"></td>
                                    </tr>
                                </table>

                            </div>
        
                            <div class="card-body p-0">
                                <table id="tableNota" class="table  table-striped">
                                    <thead>
                                        <tr style="background-color: #343a40; color:white;">
                                            <th style="width: 1%;">No</th>
                                            <th>Nama Barang</th>
                                            <th>Kategori</th>
                                            <th style="width: 12%; text-align:center; vertical-align:middle;">Stk. Awal</th>
                                            <th style="width: 12%; text-align:center; vertical-align:middle;">Stk. Akhir</th>
                                            <th style="width: 1%; text-align:center; vertical-align:middle;">Qty</th>
                                            <th style="width: 1%; text-align:center; vertical-align:middle;">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                    </tfoot>
                                </table>
                                <hr>
                                <p style="padding:0 0 2% 2%; margin:0;"><b>~Tambahan</b></p>
                                <table id="tableAdditional" class="table table-bordered table-striped">
                                    <thead>
                                        <tr style="background-color: #343a40; color:white;">
                                            <th style="width: 1%;">No</th>
                                            <th>Deskripsi</th>
                                            <th style="width: 1%">Banyaknya</th>
                                            <th style="width: 17%;">Jumlah Satuan</th>
                                            <th style="width: 10%;">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>                            
            </div>
            <div class="col-4">
                {{-- Input form --}}
                <div class="form-group">
                    <div class="row">
                        {{-- <div class="col-12">
                            <label>Pilih Akun<span class="text-danger">*</span></label>
                            <select class="form-control select-account" id="select_account" style="width: 100%;">
                                @foreach ($financial_accounts as $account)
                                    <option value="{{ $account->account_id }}">{{ $account->account_id }}</option>
                                @endforeach
                            </select>
                        </div> --}}

                        <div class="col-6">
                            <label>Kredit<span class="text-danger">*</span></label>
                            <select class="form-control select-account" id="select_credit" style="width: 100%;">
                                @foreach ($assets as $aset)
                                    @if ($aset->account_id == 'Persediaan Gudang')
                                        <option value="{{ $aset->account_id }}" selected>{{ $aset->account_id }}</option>
                                    @else
                                        <option value="{{ $aset->account_id }}">{{ $aset->account_id }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label>Debit<span class="text-danger">*</span></label>
                            <select class="form-control select-account" id="select_debit" style="width: 100%;">
                                @foreach ($source as $sumber)
                                    <option value="{{ $sumber->account_id }}">{{ $sumber->account_id }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label>Kategori<span class="text-danger">*</span></label>
                            <select class="form-control select-category" id="select_category" style="width: 100%;">
                                <option value="Perbaikan">Perbaikan</option>
                                <option value="Perawatan">Perawatan</option>
                                <option value="Penjualan">Penjualan</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label>Tanggal<span class="text-danger">*</span></label>
                            <input type="text" class="form-control datepicker" id="input_date" value="{{ date('d/m/Y') }}">
                        </div>
                    </div>
                </div>
                <div class="form-group" id="select_vehicle_container">
                    <label>Kendaraan<span class="text-danger">*</span></label>
                    <div class="row">
                        <div class="col-6">
                            <select class="form-control select-vehicle" id="select_vehicle" style="width: 100%;">
                            </select>
                        </div>
                        <div class="col-6">
                            <select class="form-control select-driver" id="select_driver" style="width: 100%;">
                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary card-outline card-outline-tabs">
                            <div class="card-header p-0 border-bottom-0">
                                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="custom-tabs-four-barang-tab" data-toggle="pill" href="#custom-tabs-four-barang" role="tab" aria-controls="custom-tabs-four-barang-tab" aria-selected="true" onclick="inputOutgoingType('Barang')">Barang</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-four-additional-tab" data-toggle="pill" href="#custom-tabs-four-additional" role="tab" aria-controls="custom-tabs-four-additional-tab" aria-selected="false" onclick="inputOutgoingType('Tambahan')">Tambahan</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-four-tabContent">
                                    <div class="tab-pane fade show active" id="custom-tabs-four-barang" role="tabpanel" aria-labelledby="custom-tabs-four-barang-tab">
                                        <div class="form-group">
                                            <label>Barang</label>
                                            <div class="row">
                                                <div class="col-6">
                                                    <select class="form-control select-product" id="select_product" style="width: 100%;">
                                                    </select>
                                                </div>
                                                <div class="col-6">
                                                    <input type="number" class="form-control numpad" id="input_qty" placeholder="Jumlah">
                                                </div>
                                            </div>
                                            <div class="row">                                                
                                                <div class="col-3">
                                                    <label for="stock_awal_barang">Stc Awal</label>                                                    
                                                    <input type="text" class="form-control" id="stock_awal_barang" readonly>
                                                </div>                                                
                                                <div class="col-3">
                                                    <label for="stock_akhir_barang">Stc Akhir</label>
                                                    <input type="text" class="form-control" id="stock_akhir_barang" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="custom-tabs-four-additional" role="tabpanel" aria-labelledby="custom-tabs-four-additional-tab">
                                        <div class="form-group">
                                            <label>Tambahan</label>
                                            <div class="row">
                                                <div class="col-5">
                                                    <select class="form-control select-additional" id="select_additional"></select>
                                                </div>
                                                <div class="col-3">
                                                    <input type="number" class="form-control numpad" id="input_additional_qty" placeholder="Jumlah">
                                                </div>
                                                <div class="col-4">
                                                    <input type="number" class="form-control numpad" id="input_additional_price" placeholder="Harga">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button style="width:100%;" class="btn btn-lg btn-success" id="btn_add" onclick="addToCart()"><i class="fa fa-plus"></i> Tambah</button>
                                </div>
                            </div>                                
                        </div>
                    </div>
                </div>
                <hr>                                

                <div class="form-group">
                    <div class="row">
                        {{-- <div class="col-6">
                            <button style="width:100%;" class="btn btn-lg btn-danger" id="btn_cancel" onclick="cancelCart()"><i class="fa fa-times"></i> Batal</button>
                        </div> --}}
                        <div class="col-5">
                            <button style="width:100%;" class="btn btn-lg bg-danger" id="btn_done" onclick="cancelCart()">
                                <i class="fa fa-times"></i>
                                Batalkan
                            </button>
                        </div>
                        <div class="col-7">
                            <button style="width:100%;" class="btn btn-lg btn-primary" id="btn_submit" onclick="submitCart()">
                                <i class="fa fa-save"></i>
                                Simpan
                            </button>
                        </div>                        
                    </div>
                </div>
                <div class="row" id="confirmation_button">                    
                    <div class="col-12">
                        <button style="width:100%;" class="btn btn-lg bg-green" id="btn_done" onclick="commitCart()">
                            <i class="fa fa-check"></i>
                            Selesaikan
                        </button>
                    </div>
                </div>    

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/gijgo/js/gijgo.min.js') }}"></script>
    <script>
        var cart = [];
        var additional_cart = [];
        var inventories = [];
        var vehicle = [];
        var driver = [];
        var additionals = [];
        var outgoing_type = 'Barang';
        var outgoing_history = [];
        var outgoing_slips = [];

        $(document).ready(function() {            
            fetchOutgoingData();

            fetchUncompletedOutgoingData()

            $('body').addClass('sidebar-collapse');            
        });

        // function checkLocalStorageCart() {
        //     var localCart = localStorage.getItem('out_cart');
        //     var localAdditionalCart = localStorage.getItem('additional_cart');
// 
        //     var localCategory = localStorage.getItem('select_category');
        //     var localVehicle = localStorage.getItem('select_vehicle');
        //     var localDriver = localStorage.getItem('select_driver');
// 
        //     var localDate = localStorage.getItem('outgoing_date');

        //     if (localCategory && localVehicle && localDriver) {
        //         $('#value_category').text(localCategory);
        //         $('#value_vehicle').text(localVehicle);
        //         $('#value_driver').text(localDriver);
        //     }
                        
        //     let vehicle_data = vehicle.find((item) => item.vehicle_code == localVehicle);

        //     if (vehicle_data) {
        //         let vehicle_id = vehicle_data.vehicle_id;                
        //         $('#input_title_value').text(localCategory + ' ' + vehicle_id + ' - ' + vehicle_data.vehicle_description);            
        //     }
            
        //     // if exist localCart and localAdditionalCart
        //     if (localCart || localAdditionalCart) {
        //         cart = JSON.parse(localCart);
        //         additional_cart = JSON.parse(localAdditionalCart);
        //         renderCart();
        //     } else if (localCart) {
        //         cart = JSON.parse(localCart);
        //         renderCart();
        //     } else if (localAdditionalCart) {
        //         additional_cart = JSON.parse(localAdditionalCart);
        //         renderCart();
        //     }
        // }

        function fetchOutgoingData() {
            $.get("{{ route('warehouse.fetchOutgoingData') }}", function(result) {
                if (result.status == 200) {
                    inventories = result.inventories;
                    vehicle = result.vehicle;
                    driver = result.driver;
                    additionals = result.additional;                    

                    renderCategories();
                    renderVehicle();
                    renderDriver();
                    renderInventories();
                    renderAdditional();

                    // checkLocalStorageCart();
                } else {
                    toastr.error('Gagal mengambil data');
                    // checkLocalStorageCart();
                }
            }).fail(function(err) {
                toastr.error('Gagal mengambil data');
                // checkLocalStorageCart();
            });
        }

        function renderCategories() {
            $('#select_category').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih Kategori',
                allowClear: true,
                tags: true,
            });

            $('#select_category').val(null).trigger('change');
        }

        function renderVehicle() {
            var vehicles = vehicle.map((item) => {
                return {
                    id: item.vehicle_code,
                    text: item.vehicle_id + ' - ' + item.vehicle_description,
                }
            });

            $('.select-vehicle').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih Kendaraan',
                allowClear: true,
                tags: true,
                data: vehicles,
            });

            $('#select_vehicle').val(null).trigger('change');
        }

        function renderDriver() {
            var drivers = driver.map((item) => {
                return {
                    id: item.driver_code,
                    text: item.driver_name,
                }
            });

            $('.select-driver').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih Sopir',
                allowClear: true,
                tags: true,
                data: drivers,
            });

            $('#select_driver').val(null).trigger('change');
        }

        function renderInventories() {
            var products = inventories.map((item) => {
                return {
                    id: item.material_description,
                    text: item.material_description,
                }
            });

            $('.select-product').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih Produk',
                allowClear: true,
                tags: true,
                data: products,
            });

            $('#select_product').val(null).trigger('change');
        }

        function renderAdditional() {
            var addition = additionals.map((item) => {
                return {
                    id: item.additional_name,
                    text: item.additional_name,
                }
            });

            $('.select-additional').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih Tambahan',
                allowClear: true,
                tags: true,
                data: addition,                
            });

            $('#select_additional').val(null).trigger('change');

        }

        function inputOutgoingType(value) {            
            outgoing_type = value;            
        }

        function addToCart() {

            switch (outgoing_type) {
                case 'Barang':                    
                    var product = $('#select_product').val();
                    var qty = $('#input_qty').val();

                    if(product == null || qty == ''){
                        toastr.error('Data tidak boleh kosong');
                        return;
                    }                                        

                    // check quantity with stock
                    var product_data = inventories.find((item) => item.material_description == product);                    
                    if (product_data.quantity < qty) {
                        toastr.error('Stok tidak mencukupi, stok saat ini Berjumlah ' + product_data.quantity + ' Pcs' );
                        return;
                    }                    
                    
                    // cart.push({                        
                    //     product: product,
                    //     qty: qty,                        
                    //     outgoing_type: outgoing_type,
                    // });

                    // if product already in cart, update qty
                    var productIndex = cart.findIndex((item) => item.product == product);
                    if (productIndex != -1) {
                        cart[productIndex].qty = parseInt(cart[productIndex].qty) + parseInt(qty);
                    } else {
                        cart.push({                        
                            product: product,
                            qty: qty,
                            outgoing_type: outgoing_type,
                        });
                    }

                    // clear input
                    $('#select_product').val(null).trigger('change');
                    $('#input_qty').val('');
                    
                    $('#select_product').focus();
                    
                    break;

                case 'Tambahan':                    
                    var additional = $('#select_additional').val();
                    var qty = $('#input_additional_qty').val();
                    var price = $('#input_additional_price').val();

                    if(additional == null || qty == '' || price == ''){
                        toastr.error('Data tidak boleh kosong');
                        return;
                    }

                    // additional_cart.push({                        
                    //     additional: additional,
                    //     qty: qty,
                    //     price: price,
                    //     outgoing_type: outgoing_type,
                    // });

                    // if additional already in cart, update qty
                    var additionalIndex = additional_cart.findIndex((item) => item.additional == additional);
                    if (additionalIndex != -1) {
                        additional_cart[additionalIndex].qty = parseInt(additional_cart[additionalIndex].qty) + parseInt(qty);
                    } else {
                        additional_cart.push({                        
                            additional: additional,
                            qty: qty,
                            price: price,
                            outgoing_type: outgoing_type,
                        });
                    }

                    // clear input
                    $('#select_additional').val(null).trigger('change');
                    $('#input_additional_qty').val('');
                    $('#input_additional_price').val('');

                    $('#select_additional').focus();

                    break;
            
                default:
                    toastr.error('Tipe pengeluaran tidak ditemukan');
                    break;
            }

            renderCart();
        }

        function renderCart() {
            $('#tableNota tbody').empty();

            cart.forEach((item, index) => {
                var row = '';
                row += '<tr>';
                row += `<td>${index + 1}</td>`;
                row += `<td>${item.product}</td>`;

                // get product category                
                let product = inventories.find((inv) => inv.material_description == item.product);

                if (product == undefined) {
                    return;
                }

                row += `<td>${product.material_category}</td>`;                
                
                row += `<td style="text-align:center;">${product.quantity}</td>`;

                row += `<td style="text-align:center;">${product.quantity - item.qty}</td>`;
                
                row += `<td style="text-align:center; background-color:yellow; font-weight:bold;">${item.qty}</td>`;

                row += `<td><button class="btn btn-sm btn-danger" onclick="removeFromCart(${index})"><i class="fa fa-trash"></i></button></td>`;

                $('#tableNota tbody').append(row);
            });            

            $('#tableAdditional tbody').empty();            

            additional_cart.forEach((item, index) => {
                var row = '';
                row += '<tr>';
                row += `<td>${index + 1}</td>`;
                row += `<td>${item.additional}</td>`;
                row += `<td>${item.qty}</td>`;
                row += `<td>${item.price}</td>`;

                row += `<td><button class="btn btn-sm btn-danger" onclick="removeFromAdditionalCart(${index})"><i class="fa fa-trash"></i></button></td>`;

                $('#tableAdditional tbody').append(row);
            });

            // footer to Additonal_cart
            var total_price = 0;
            additional_cart.forEach((item) => {
                total_price += item.qty * item.price;
            });

            var row = '';
            row += '<tr>';
            row += '<td colspan="2" align="right">Total</td>';
            row += `<td colspan="4"><b>${total_price}</b> <br>`;
            let totalTerbilang = numberToWords(total_price);
            
            totalTerbilang = totalTerbilang.replace(/\s+/g, ' ');            
        
            row += `<span style="font-weight:600; font-size:12px;text-transform:uppercase;">${totalTerbilang}</span>`;

            row += '</tr>';

            $('#tableAdditional tfoot').empty();
            $('#tableAdditional tfoot').append(row);
            
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            renderCart();
        }

        function removeFromAdditionalCart(index) {
            additional_cart.splice(index, 1);
            renderCart();
        }

        function cancelCart() {
            cart = [];
            additional_cart = [];                        

            $('#select_category').val(null).trigger('change');
            $('#select_vehicle').val(null).trigger('change');
            $('#select_driver').val(null).trigger('change');
            renderCart();
            // toastr.success('Data berhasil dihapus');
        }

        function submitCart() {
            if (cart.length == 0) {
                toastr.error('Data tidak boleh kosong');
                return;
            }
            
            // Akun Kredit Debit
            // TODO

            if ($('#value_category').text() == '') {
                toastr.error('Kategori tidak boleh kosong');
                return;
            }

            if ($('#value_vehicle').text() == '') {
                toastr.error('Kendaraan tidak boleh kosong');
                return;
            }

            if ($('#value_driver').text() == '') {
                toastr.error('Sopir tidak boleh kosong');
                return;
            }            

            var formData = new FormData();            

            formData.append('slip', $('#value_outgoing_id').val());
            formData.append('category', $('#value_category').text());
            formData.append('account', $('#value_account').text());
            formData.append('date', $('#value_date').text());
                        
            let vehicle_data = vehicle.find((item) => item.vehicle_id == $('#value_vehicle').text().split(' - ')[0]);
            formData.append('vehicle', vehicle_data.vehicle_code);            

            let driver_data = driver.find((item) => item.driver_name == $('#value_driver').text());
            formData.append('driver', driver_data.driver_code);

            formData.append('cart', JSON.stringify(cart));
            formData.append('additional_cart', JSON.stringify(additional_cart));
            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                url: "{{ route('warehouse.submitOutgoingData') }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(result) {
                    if (result.status == 200) {                        
                        toastr.success(result.message);
                        cancelCart();
                        fetchOutgoingData();
                        fetchUncompletedOutgoingData();
                    } else {
                        toastr.error('Gagal menyimpan data');
                    }
                },
                error: function(err) {
                    toastr.error(err);
                }
            });
        }

        // Selesaikan
        function commitCart(){
            
            // check if cart or additional cart is different from the last saved one, if so return false then give error please save first 
            // do check like I said above
            // if different, then ask user to save first
            // if not different, then proceed to commit

            // get last saved cart
            let last_saved_cart = outgoing_history.filter((item) => item['outgoing_id'] == $('#value_outgoing_id').val());
            let last_saved_cart_items = [];

            last_saved_cart.forEach((item) => {
                if (item['type'] == 'Barang') {
                    last_saved_cart_items.push({
                        'product': item['description'],
                        'qty': item['quantity'],
                        'outgoing_type': item['type'],
                    });
                } else {
                    last_saved_cart_items.push({
                        'additional': item['description'],
                        'qty': item['quantity'],
                        'price': item['price'],
                        'outgoing_type': item['type'],
                    });
                }
            });

            // cart+additional_cart
            let current_cart = cart.concat(additional_cart);

            // check if cart is different
            if (JSON.stringify(last_saved_cart_items) != JSON.stringify(current_cart)) {
                // toastr.error('Silahkan simpan data terlebih dahulu');
                // return;
                submitCart();
            }

            var formData = new FormData();            

            formData.append('slip', $('#value_outgoing_id').val());            
            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                url: "{{ route('warehouse.commitOutgoingData') }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(result) {
                    if (result.status == 200) {
                        toastr.success('Data berhasil tercatat');
                        cancelCart();
                        fetchOutgoingData();
                        fetchUncompletedOutgoingData();

                    } else {
                        toastr.error('Gagal menyimpan data');
                    }
                },
                error: function(err) {
                    toastr.error('Gagal menyimpan data');
                }
            });
        }

        function fetchUncompletedOutgoingData() {            
            $.get("{{ route('warehouse.fetchUncompleted') }}", function(result) {                
                if (result.status == 200) {                    
                    outgoing_history = result.histories;
                    outgoing_slips = result.slipItems;

                    let slip = result.slip;
                    let slipLength = Object.keys(slip).length;
                    let slipItems = result.slipItems;
                    let data = result.histories;                                        
                    $('#tableSlip tbody').empty();
                    let html = '';                    

                    let newslip = '<tr class="slip_btn" onclick="newSlip()">';
                    newslip += '<td colspan="2" style="text-align:center; font-weight:bold;">Buat Slip Baru</td>';
                    newslip += '</tr>';

                    $('#tableSlip tbody').append(newslip);

                    for(let i = 0; i < slipLength; i++){
                        html += '<tr class="slip_btn" onclick="fetchOutgoingDataBySlip(\''+slip[i]+'\')">';
                        html += `<td>${i + 1}</td>`;
                        
                        let formatted_slip = slip[i];

                        month_shorname_id = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

                        let year = formatted_slip.substring(0, 4);
                        let month = formatted_slip.substring(4, 6);
                        let day = formatted_slip.substring(6, 8);
                        let slip_number = formatted_slip.substring(8, 16);

                        formatted_slip = year + '-' + month_shorname_id[parseInt(month) - 1] + '-' + day + ' ' + slip_number;

                        html += `<td style="font-size:14px; font-weight:600;">${formatted_slip}</td>`;
                        html += '</tr>';
                    }

                    $('#tableSlip tbody').append(html);


                } else {
                    toastr.error('Gagal mengambil data');
                }
            }).fail(function(err) {
                toastr.error('Gagal mengambil data');
            });
        }

        function newSlip() {

            $('#value_outgoing_id').val('');

            // DOM Hide delete button
            $('#deleteSlipBtn').hide();

            $('#value_slip').text('');
            $('#value_category').text('');
            $('#value_account').text('');
            $('#value_date').text('');
            $('#value_vehicle').text('');
            $('#value_driver').text('');            

            $('#tableNota tbody').empty();
            $('#tableAdditional tbody').empty();

            cart = [];
            additional_cart = [];

            $('#select_account').val(null).trigger('change');
            $('#select_category').val(null).trigger('change');
            $('#select_vehicle').val(null).trigger('change');
            $('#select_driver').val(null).trigger('change');

            $('#select_product').val(null).trigger('change');
            $('#input_qty').val('');

            $('#select_additional').val(null).trigger('change');
            $('#input_additional_qty').val('');
            $('#input_additional_price').val('');

            $('#tableAdditional tfoot').empty();

            $('#select_category').focus();
        }

        function updateDOMForHistoryItems(historyItems) {
            cart = []
            additional_cart = []

            historyItems.forEach((item) => {                
                if (item['type'] == 'Barang') {
                    cart.push({
                        'product': item['description'],
                        'qty': item['quantity'],
                        'outgoing_type': item['type'],
                    });
                } else {
                    additional_cart.push({
                        'additional': item['description'],
                        'qty': item['quantity'],
                        'price': item['price'],
                        'outgoing_type': item['type'],
                    });
                }                
            });                    

            renderCart();                        
        }

        function fetchOutgoingDataBySlip(slip){

            // DOM Show delete button
            // $('#deleteSlipBtn').show();

            const historySlip = outgoing_slips.find((item) => item['history_id_with_date'] == slip);
            if (historySlip == undefined) {                
                toastr.error('Slip: ' + slip + ' tidak ditemukan');
                return;
            }
            // console.log(historySlip);
            $('#value_outgoing_id').val(historySlip['outgoing_id'])
            $('#value_slip').text(slip);
            $('#value_account').text(historySlip['account_id']);
            $('#value_category').text(historySlip['category']);
            $('#value_date').text(historySlip['date']);
            
            var vehicle_id = historySlip['vehicle_id'];                        
            var vehicle_data = vehicle.find((item) => item.vehicle_code == vehicle_id);            
            $('#value_vehicle').text(vehicle_data.vehicle_id + ' - ' + vehicle_data.vehicle_description);

            let driver_id = vehicle_data.driver_code;
            let driver_data = driver.find((item) => item.driver_code == driver_id);
            $('#value_driver').text(driver_data.driver_name);

            const historyItems = outgoing_history.filter((item) => item['outgoing_id'] == historySlip['outgoing_id']);            

            updateDOMForHistoryItems(historyItems);
        }


        function deleteThisSlip() {
            let slip = $('#value_outgoing_id').val();

            // delete confirmation
            if (!confirm('Apakah anda yakin ingin menghapus slip ini?')) {
                return;
            }

            if (slip == '') {
                toastr.error('Slip tidak ditemukan');
                return;
            }            

            $.get("{{ route('warehouse.deleteOutgoingData') }}", { slip: slip }, function(result) {
                if (result.status == 200) {
                    toastr.success('Data berhasil dihapus');
                    fetchUncompletedOutgoingData();
                    newSlip();
                } else {
                    toastr.error('Gagal menghapus data');
                }
            }).fail(function(err) {
                toastr.error('Gagal menghapus data');
            });
        }

        // Event Handler
        $(function() {
            $('#select_debit').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih Akun',
                allowClear: true,                
            });

            $('#select_credit').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih Akun',
                allowClear: true,                
            });

            $('.select-vendor').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih Toko',
                allowClear: true,
                tags: true,
            })
            $('.select-product').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih Produk',
                allowClear: true,
                tags: true,
            })

            $('.datepicker').datepicker({
                uiLibrary: 'bootstrap4',
                format: 'dd/mm/yyyy',
            });
            // event

            $('#select_account').on('select2:select', function(e) {
                var data = e.params.data;
                $('#value_account').text(data.text);
            });

            // event select category
            $('#select_category').on('select2:select', function(e) {
                var data = e.params.data;                            
                $('#value_category').text(data.text);
            });

            // event select vehicle
            $('#select_vehicle').on('select2:select', function(e) {                
                var data = e.params.data;                
                $('#value_vehicle').text(data.text);

                // Driver                                
                let selected_vehicle = $('#select_vehicle').val();                            
                var find_vehicle = vehicle.find((item) => item.vehicle_code == selected_vehicle);                
                $('#select_driver').val(find_vehicle.driver_code).trigger('change');                

                var find_driver = driver.find((item) => item.driver_code == find_vehicle.driver_code);
                $('#value_driver').text(find_driver.driver_name);
            });

            $('#select_driver').on('select2:select', function(e) {
                var data = e.params.data;
                $('#value_driver').text(data.text);
            });

            $('#select_product').on('select2:select', function(e) {
                var data = e.params.data;
                var product = inventories.find((item) => item.material_description == data.text);
                
                $('#stock_awal_barang').val(product.quantity);
                $('#stock_akhir_barang').val(product.quantity - $('#input_qty').val());

                // if stock_akhir_barang < 0 background red
                if(product.quantity - $('#input_qty').val() < 0){
                    $('#stock_akhir_barang').css('background-color', 'red');                    
                    $('#stock_akhir_barang').css('color', 'white');
                }else{
                    $('#stock_akhir_barang').css('background-color', '#e9ecef');
                    $('#stock_akhir_barang').css('color', 'black');
                }
            });

            $('#input_date').on('change', function() {
                // convert date to dd-mmmm-yyyy
                var date = $('#input_date').val();
                
                let short_month = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                let date_split = date.split('/');
                let month = short_month[parseInt(date_split[1]) - 1];

                $('#value_date').text(date_split[0] + ' ' + month + ' ' + date_split[2]);                            
            });

            $('#input_qty').on('input', function() {
                var product = $('#select_product').val();
                var qty = $('#input_qty').val();

                if (product) {
                    var product_data = inventories.find((item) => item.material_description == product);
                    $('#stock_akhir_barang').val(product_data.quantity - qty);
                }

                // if stock_akhir_barang < 0 background red
                if(product_data.quantity - qty < 0){
                    $('#stock_akhir_barang').css('background-color', 'red');
                    $('#stock_akhir_barang').css('color', 'white');                                        
                }else{
                    $('#stock_akhir_barang').css('background-color', '#e9ecef');
                    $('#stock_akhir_barang').css('color', 'black');
                }
            });


            $('.numpad').keypress(function(e) {
                if (e.which == 13) {
                    addToCart();
                }
            });

            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });

            // // if refresh save last cart to local storage
            // window.onbeforeunload = function() {
            //     // save Kategori, kendaraan, driver
            //     var category = $('#value_category').text();
            //     var vehicle = $('#value_vehicle').text();
            //     var driver = $('#value_driver').text();
            //     var date = $('value_date').text();

            //     localStorage.setItem('select_category', category);
            //     localStorage.setItem('select_vehicle', vehicle);
            //     localStorage.setItem('select_driver', driver);
            //     localStorage.setItem('outgoing_date', date);

            //     localStorage.setItem('out_cart', JSON.stringify(cart));
            //     localStorage.setItem('additional_cart', JSON.stringify(additional_cart));
            // }

        });
    </script>
@endsection
