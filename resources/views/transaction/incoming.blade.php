@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/gijgo/css/gijgo.min.css') }}">
    <style>        
        .index-title {
            color: #333;
            font-size: 
        }

        .nota_header_value{
            font-weight: 600;
        }

        .nota-header{            
            font-weight: 600;
            font-size: 1.1rem;
        }

        #tableNota tr th {
            font-size: 14px;
            text-align: center;
            padding: 5px;
        }
        #tableNota tr td {            
            padding: 5px;
        }        
    </style>
@endsection

@section('nav-title')
    Barang Masuk
@endsection

@section('content-header')    
{{-- <div class="row">
    
</div> --}}
@endsection

@section('content')
    <div class="container">
        <div class="row">            
            <div class="col-8">
                <div class="card">
                    <div class="card-header row nota-header" style="padding: 5px 10px;">
                        <div class="col">Toko : <span class="nota_header_value" id="nota_vendor"></span></div>
                        {{-- <div class="col">Invoice : <span class="nota_header_value" id="nota_invoice"></span></div> --}}
                        <div class="col" align="right">Tanggal : <span class="nota_header_value" id="nota_date"></span></div>
                    </div>

                    <div class="card-body p-0">
                        <table id="tableNota" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 10px;">No</th>
                                    <th>Nama Barang</th>
                                    <th>Kategori</th>
                                    <th style="width: 20px">Banyaknya</th>
                                    <th style="width: 20px">Satuan</th>
                                    <th style="width: 10%">Harga</th>
                                    <th style="width: 10%">Jumlah</th>
                                    <th style="width: 20px">Pembayaran</th>
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
            <div class="col-4">
                {{-- Input form --}}
                <div class="col-12" align="right">        
                    <button class="btn btn-sm btn-primary">
                        <i class="fa fa-history"></i>
                        Riwayat Barang Masuk
                    </button>
                </div>
                <div class="form-group">
                    <label>Toko<span class="text-danger">*</span></label>
                    <select class="form-control select-vendor" id="select_vendor" style="width: 100%;">
                    </select>
                </div>                
                <div class="form-group">
                    <label>Tanggal<span class="text-danger">*</span></label>
                    <input type="text" class="form-control datepicker" id="input_date" value="{{ date('d/m/Y') }}">
                </div>
                <div class="form-group">
                    <label>Pembayaran</label>
                    <select class="form-control select-payment select2" id="select_payment" style="width: 100%;" data-placeholder="Pilih Metode Pembayaran">
                        <option value="KONTAN">Kontan</option>
                        <option value="DO">DO</option>
                    </select>
                </div>                
                <div class="form-group">
                    <label>Barang</label>
                    <div class="row">
                        <div class="col-6">
                            <select class="form-control select-product" id="select_product" style="width: 100%;">
                            </select>
                        </div>
                        <div class="col-6">
                            <select class="form-control" id="select_product_category" data-placeholder="Kategori">                                
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Jumlah</label>
                    <div class="row">
                        <div class="col-4">
                            <input type="number" class="form-control numpad" id="input_qty" placeholder="Jumlah">
                        </div>
                        <div class="col-4">
                            <select class="form-control select2" id="select_uom" data-placeholder="Satuan">
                                <option value="pcs">Pcs</option>
                                <option value="liter">Liter</option>
                                <option value="meter">Meter</option>
                            </select>
                        </div>
                        <div class="col-4">
                            <input type="number" class="form-control numpad" id="input_price" placeholder="Harga Satuan">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button style="width:100%;" class="btn btn-lg btn-success" id="btn_add" onclick="addToCart()"><i class="fa fa-plus"></i> Tambah</button>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-6">
                            <button style="width:100%;" class="btn btn-lg btn-danger" id="btn_cancel" onclick="cancelCart()"><i class="fa fa-times"></i> Batal</button>
                        </div>
                        <div class="col-6">
                            <button style="width:100%;" class="btn btn-lg btn-primary" id="btn_submit" onclick="submitCart()"><i class="fa fa-save"></i> Simpan</button>
                        </div>
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
        var vendor = [];

        $(document).ready(function() {
            fetchInventories();

            $('body').addClass('sidebar-collapse');
            checkLocalStorageCart();            
        });

        function checkLocalStorageCart() {
            var localCart = localStorage.getItem('incoming_cart');
            var localVendor = localStorage.getItem('incoming_vendor');
            var localDate = localStorage.getItem('incoming_date');
            if (localCart) {
                cart = JSON.parse(localCart);
                renderCart();
            }
            if (localVendor) {
                $('#select_vendor').val(localVendor).trigger('change');
                $('#nota_vendor').text(localVendor);
            }
            if (localDate) {
                $('#input_date').val(localDate);
                $('#nota_date').text(localDate);
            }
        }

        function fetchInventories() {
            $.get("{{ route('transaction.fetchIncomingData') }}", function(result) {
                if (result.status == 200) {
                    inventories = result.inventories;
                    vendor = result.vendor;
                    categories = result.categories;

                    renderVendor();
                    renderInventories();
                    renderProductCategory();
                } else {
                    toastr.error('Gagal mengambil data');
                }
            });
        }

        function renderVendor() {
            var vendors = vendor.map((item) => {
                return {
                    id: item.vendor_name,
                    text: item.vendor_name,
                }
            });

            $('.select-vendor').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih Toko',
                allowClear: true,
                tags: true,
                data: vendors,
            });

            // select none 
            $('#select_vendor').val(null).trigger('change');
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

            // select none
            $('#select_product').val(null).trigger('change');
        }

        function renderProductCategory(){            
            var product_category = categories.map((item) => {
                return {
                    id: item.material_category,
                    text: item.material_category,
                }
            });

            $('#select_product_category').select2({
                theme: 'bootstrap4',
                placeholder: 'Pilih Kategori',
                allowClear: true,
                tags: true,
                data: product_category,
            });

            // select none
            $('#select_product_category').val(null).trigger('change');

        }

        function addToCart() {
            var vendor = $('#select_vendor').val();
            var date = $('#input_date').val();
            var product = $('#select_product').val();
            var product_category = $('#select_product_category').val();
            var qty = $('#input_qty').val();
            var uom = $('#select_uom').val();
            var price = $('#input_price').val();
            var payment = $('#select_payment').val();

            // if null all data data tidak boleh kosong
            if(product == null || qty == '' || price == '' || payment == '' || date == '' ) {
                toastr.error('Data tidak boleh kosong');
                return;
            }                        
            if(product == null) {
                toastr.error('Barang tidak boleh kosong');
                return;
            }
            if(product_category == null) {
                toastr.error('Kategori tidak boleh kosong');
                return;
            }
            if(qty == '') {
                toastr.error('Jumlah tidak boleh kosong');
                return;
            }
            if(price == '') {
                toastr.error('Harga tidak boleh kosong');
                return;
            }

            if(payment == '') {
                toastr.error('Pembayaran tidak boleh kosong');
                return;
            }

            if(date == '') {
                toastr.error('Tanggal tidak boleh kosong');
                return;
            }

            // vendor cannot be different with the previous data
            // if (cart.length > 0) {
            //     if (cart[0].vendor != vendor) {
            //         toastr.error('Toko tidak boleh berbeda, toko sebelumnya yang anda pilih adalah ' + cart[0].vendor);
            //         return;
            //     }
            // }

            var cartData = {
                product: product,
                product_category: product_category,
                qty: qty,
                uom: uom,
                price: price,
                payment: payment,
            }

            cart.push(cartData);

            // val none product qty and price
            $('#select_product').val(null).trigger('change');
            $('#input_qty').val('');
            $('#input_price').val('');

            renderCart();

            // focus on select-product
            $('#select_product').focus();
        }

        function renderCart() {
            $('#tableNota tbody').empty();

            cart.forEach((item, index) => {
                var row = '';
                row += '<tr>';
                row += `<td style="text-align:center;">${index + 1}</td>`;
                row += `<td>${item.product}</td>`;
                row += `<td>${item.product_category}</td>`;
                row += `<td style="text-align:center;">${item.qty}</td>`;
                row += `<td style="text-align:center;">${item.uom}</td>`;
                row += `<td style="text-align:right;">${convertToIDR(item.price)}</td>`;

                let total = item.qty * item.price;
                row += `<td style="text-align:right;">${convertToIDR(total)}</td>`;

                row += `<td style="text-align:center;">${item.payment}</td>`;

                row += `<td><button class="btn btn-sm btn-danger" onclick="removeFromCart(${index})"><i class="fa fa-trash"></i></button></td>`;

                $('#tableNota tbody').append(row);
            });

            var total = cart.reduce((acc, item) => {
                return acc + (item.qty * item.price);
            }, 0);

            var footer = '';
            footer += '<tr>';
            footer += '<td colspan="5" style="text-align:right">Total</td>';
            footer += `<td colspan="3" style="font-weight:600;">${convertToIDR(total)}<br>`;

            // let totalTerbilang = numberToWords(total);

            // totalTerbilan remove duplicate space to single space
            // totalTerbilang = totalTerbilang.replace(/\s+/g, ' ');            
            
            // footer += `<span style="font-weight:600; font-size:12px;text-transform:uppercase;">${totalTerbilang}</span>`;
                
            footer += '</td>';
            footer += '</tr>';

            $('#tableNota tfoot').html(footer);
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            renderCart();
        }

        function cancelCart() {
            cart = [];
            renderCart();
        }

        function submitCart() {
            if (cart.length == 0) {
                toastr.error('Data tidak boleh kosong');
                return;
            }            

            if ($('#nota_vendor').text() == '') {
                toastr.error('Toko tidak boleh kosong');
                return;
            }

            if ($('#nota_date').text() == '') {
                toastr.error('Tanggal tidak boleh kosong');
                return;
            }

            let vendor = $('#nota_vendor').text();
            let date = $('#nota_date').text();

            var formData = new FormData();

            formData.append('vendor', vendor);
            formData.append('date', date);
            formData.append('cart', JSON.stringify(cart));            
            formData.append('_token', '{{ csrf_token() }}');
            
            $.ajax({
                url: "{{ route('transaction.submitIncomingData') }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(result) {
                    if (result.status == 200) {
                        localStorage.removeItem('cart');
                        toastr.success('Data berhasil disimpan');
                        cart = [];
                        renderCart();
                    } else {
                        toastr.error(result.message);
                    }
                },
                error: function(err) {
                    toastr.error(err.responseJSON.message);
                }
            });
        }

        $(function() {

            $('.select2').each((_i, e) => {
                var e = $(e);
                e.select2({
                    tags: true,
                    allowClear: true,
                    theme: 'bootstrap4',
                    dropdownParent: e.parent()
                });
            });

            $('.datepicker').datepicker({
                uiLibrary: 'bootstrap4',
                format: 'dd/mm/yyyy',
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

            // event
            $('#select_vendor').on('select2:select', function(e) {
                var data = e.params.data;                
                $('#nota_vendor').text(data.text);
            });

            $('#input_date').on('change', function() {
                // convert to dd/mmmm/yyyy
                let short_month = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                let date = $(this).val().split('/');
                let month = short_month[parseInt(date[1]) - 1];                

                $('#nota_date').text($(this).val());
            });

            $('#select_product').on('select2:select', function(e) {
                var data = e.params.data;
                var product = inventories.find((item) => item.material_description == data.text);
                $('#input_price').val(product.price);
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
                localStorage.setItem('incoming_cart', JSON.stringify(cart));
                if($('#select_vendor').val() != null){
                    localStorage.setItem('incoming_vendor', $('#select_vendor').val());
                }
                if($('#input_date').val() != ''){
                    localStorage.setItem('incoming_date', $('#input_date').val());
                }
            }

        });        
    </script>
@endsection
