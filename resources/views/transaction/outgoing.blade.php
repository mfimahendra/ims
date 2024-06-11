@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
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
    </style>
@endsection

@section('nav-title')
    Pengeluaran Harian Sparepart
@endsection

@section('content-header')
    <div class="row">
        <div class="col-12" align="right">
            <button class="btn btn-sm btn-primary">
                <i class="fa fa-history"></i>
                Riwayat Pengeluaran Sparepart
            </button>
        </div>
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-6">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">                                
                                <p style="margin: 0; font-weight:bold;">
                                    <span id="title_category"></span>
                                    <span id="title_vehicle"></span>
                                    <span id="title_date"></span>
                                </p>                                
                            </div>
        
                            <div class="card-body p-0">
                                <table id="tableNota" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width: 1%;">No</th>
                                            <th>Nama Barang</th>
                                            <th>Kategori</th>
                                            <th style="width: 1%; text-align:center; vertical-align:middle;">Stock Awal</th>
                                            <th style="width: 1%; text-align:center; vertical-align:middle;">Stock Akhir</th>
                                            <th style="width: 1%; text-align:center; vertical-align:middle;">Qty</th>
                                            <th style="width: 1%; text-align:center; vertical-align:middle;">#</th>
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
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <p style="text-align: center; padding:0; margin:0;"><b>Tambahan</b></p>
                                <div class="row">
                                    <div class="col-6">
        
                                    </div>
                                    <div class="col-6">
        
                                    </div>
                                </div>
                            </div>
        
                            <div class="card-body p-0">
                                <table id="tableAdditional" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width: 10px;">No</th>
                                            <th>Deskripsi</th>
                                            <th style="width: 20px">Banyaknya</th>
                                            <th style="width: 20px">Jumlah Satuan</th>
                                            <th style="width: 10px">#</th>
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
                    <label>Kategori<span class="text-danger">*</span></label>
                    <select class="form-control select-category" id="select_category" style="width: 100%;">
                        <option value="Perbaikan">Perbaikan</option>
                        <option value="Perawatan">Perawatan</option>
                        <option value="Penjualan">Penjualan</option>
                    </select>
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
                        <div class="col-6">
                            <button style="width:100%;" class="btn btn-lg btn-danger" id="btn_cancel" onclick="cancelCart()"><i class="fa fa-times"></i> Batal</button>
                        </div>
                        <div class="col-6">
                            <button style="width:100%;" class="btn btn-lg btn-primary" id="btn_submit" onclick="submitCart()">
                                <i class="fa fa-save"></i>
                                Simpan
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        var cart = [];
        var additional_cart = [];
        var inventories = [];
        var vehicle = [];
        var driver = [];
        var additionals = [];
        var outgoing_type = 'Barang';        

        $(document).ready(function() {
            fetchOutgoingData();

            $('body').addClass('sidebar-collapse');            
        });

        function checkLocalStorageCart() {
            var localCart = localStorage.getItem('cart');
            var localAdditionalCart = localStorage.getItem('additional_cart');

            var localCategory = localStorage.getItem('select_category');
            var localVehicle = localStorage.getItem('select_vehicle');
            var localDriver = localStorage.getItem('select_driver');            

            if (localCategory && localVehicle && localDriver) {
                $('#select_category').val(localCategory).trigger('change');
                $('#select_vehicle').val(localVehicle).trigger('change');
                $('#select_driver').val(localDriver).trigger('change');
            }
                        
            let vehicle_data = vehicle.find((item) => item.vehicle_code == localVehicle);

            if (vehicle_data) {
                let vehicle_id = vehicle_data.vehicle_id;                
                $('#input_title_value').text(localCategory + ' ' + vehicle_id + ' - ' + vehicle_data.vehicle_description);            
            }


            
            // if exist localCart and localAdditionalCart
            if (localCart && localAdditionalCart) {
                cart = JSON.parse(localCart);
                additional_cart = JSON.parse(localAdditionalCart);
                renderCart();
            } else if (localCart) {
                cart = JSON.parse(localCart);
                renderCart();
            } else if (localAdditionalCart) {
                additional_cart = JSON.parse(localAdditionalCart);
                renderCart();
            }
        }

        function fetchOutgoingData() {
            $.get("{{ route('transaction.fetchOutgoingData') }}", function(result) {
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
                    checkLocalStorageCart();
                } else {
                    toastr.error('Gagal mengambil data');
                    checkLocalStorageCart();
                }
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

            var category = $('#select_category').val();
            var vehicle = $('#select_vehicle').val();
            var driver = $('#select_driver').val();

            if (category == null || vehicle == null || driver == null) {
                toastr.error('Kategori, Kendaraan, Sopir tidak boleh kosong');
                return;
            }

            switch (outgoing_type) {
                case 'Barang':                    
                    var product = $('#select_product').val();
                    var qty = $('#input_qty').val();

                    if(product == null || qty == ''){
                        toastr.error('Data tidak boleh kosong');
                        return;
                    }

                    // if category, vehicle, driver different with cart
                    if (cart.length > 0) {
                        var cart_category = cart[0].category;
                        var cart_vehicle = cart[0].vehicle;
                        var cart_driver = cart[0].driver;

                        if (category != cart_category || vehicle != cart_vehicle || driver != cart_driver) {
                            toastr.error('Kategori, Kendaraan, Sopir harus sama dengan data sebelumnya');

                            $('#select_category').val(cart_category).trigger('change');
                            $('#select_vehicle').val(cart_vehicle).trigger('change');
                            $('#select_driver').val(cart_driver).trigger('change');
                            return;
                        }
                    }

                    // check quantity with stock
                    var product_data = inventories.find((item) => item.material_description == product);                    
                    if (product_data.quantity < qty) {
                        toastr.error('Stok tidak mencukupi, stok saat ini Berjumlah ' + product_data.quantity + ' Pcs' );
                        return;
                    }                    
                    
                    cart.push({
                        category: category,
                        vehicle: vehicle,
                        driver: driver,
                        product: product,
                        qty: qty,                        
                        outgoing_type: outgoing_type,
                    });

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

                    additional_cart.push({
                        category: category,
                        vehicle: vehicle,
                        driver: driver,
                        additional: additional,
                        qty: qty,
                        price: price,
                        outgoing_type: outgoing_type,
                    });

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

                let product = inventories.find((inv) => inv.material_description == item.product);
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
            localStorage.removeItem('cart');
            localStorage.removeItem('additional_cart');

            localStorage.removeItem('select_category');
            localStorage.removeItem('select_vehicle');
            localStorage.removeItem('select_driver');

            $('#select_category').val(null).trigger('change');
            $('#select_vehicle').val(null).trigger('change');
            $('#select_driver').val(null).trigger('change');
            renderCart();
            toastr.success('Data berhasil dihapus');
        }

        function submitCart() {
            if (cart.length == 0) {
                toastr.error('Data tidak boleh kosong');
                return;
            }            

            var formData = new FormData();

            formData.append('cart', JSON.stringify(cart));
            formData.append('additional_cart', JSON.stringify(additional_cart));
            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                url: "{{ route('transaction.submitOutgoingData') }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(result) {
                    if (result.status == 200) {                        
                        toastr.success('Data berhasil disimpan');
                        cancelCart();
                        fetchOutgoingData();
                    } else {
                        toastr.error('Gagal menyimpan data');
                    }
                },
                error: function(err) {
                    toastr.error('Gagal menyimpan data');
                }
            });
        }

        $(function() {
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
            // event            

            // 2 event select title value
            $('#select_category').on('select2:select', function(e) {
                var data = e.params.data;                            
                $('#title_category').text(data.text);
            });

            $('#select_vehicle').on('select2:select', function(e) {                
                var data = e.params.data;                
                $('#title_vehicle').text(data.text);

                // Driver                                
                let selected_vehicle = $('#select_vehicle').val();
                // get vehicle_data from vehicle_id
                var find_vehicle = vehicle.find((item) => item.vehicle_code == selected_vehicle);                
                // let find_driver = driver.find((item) => item.driver_code == find_vehicle.driver_code);                

                $('#select_driver').val(find_vehicle.driver_code).trigger('change');
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

            // if refresh save last cart to local storage
            window.onbeforeunload = function() {
                // save Kategori, kendaraan, driver
                var category = $('#select_category').val();
                var vehicle = $('#select_vehicle').val();
                var driver = $('#select_driver').val();

                localStorage.setItem('select_category', category);
                localStorage.setItem('select_vehicle', vehicle);
                localStorage.setItem('select_driver', driver);

                localStorage.setItem('cart', JSON.stringify(cart));
                localStorage.setItem('additional_cart', JSON.stringify(additional_cart));
            }

        });
    </script>
@endsection
