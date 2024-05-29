@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <style>
        #input_toko_value {
            font-size: 1.2em;
            font-weight: 600;
            text-align: center;
        }

        .index-title {
            color: #333;
            font-size: 
        }
    </style>
@endsection

@section('nav-title')
    Barang Masuk
@endsection

@section('content-header')    
<div class="row">
    <div class="col-12" align="right">        
        <button class="btn btn-sm btn-primary">
            <i class="fa fa-history"></i>
            Riwayat Barang Masuk
        </button>
    </div>
</div>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <h3 id="input_toko_value">
                            Toko
                        </h3>
                    </div>

                    <div class="card-body p-0">
                        <table id="tableNota" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 10px;">No</th>
                                    <th>Nama Barang</th>
                                    <th style="width: 20px">Banyaknya</th>
                                    <th style="width: 50px">Jumlah</th>
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
            <div class="col-6">
                {{-- Input form --}}
                <div class="form-group">
                    <label>Pembayaran</label>
                    <select class="form-control select-payment" id="select_payment" style="width: 100%;">
                        <option value="">-- Pilih Pembayaran --</option>
                        <option value="DO">DO</option>
                        <option value="KONTAN">Kontan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Toko</label>
                    <select class="form-control select-vendor" id="select_vendor" style="width: 100%;">
                    </select>
                </div>
                <div class="form-group">
                    <label>Barang</label>
                    <select class="form-control select-product" id="select_product" style="width: 100%;">
                    </select>
                </div>
                <div class="form-group">
                    <label>Jumlah</label>
                    <div class="row">
                        <div class="col-4">
                            <input type="number" class="form-control numpad" id="input_qty" placeholder="Jumlah">
                        </div>
                        <div class="col-8">
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
            var localCart = localStorage.getItem('cart');
            if (localCart) {
                cart = JSON.parse(localCart);
                if(cart.length > 0) {
                    var vendor = cart[0].vendor;
                    $('#input_toko_value').text(vendor);

                    renderCart();
                }                
            }            
        }

        function fetchInventories() {
            $.get("{{ route('transaction.fetchIncomingData') }}", function(result) {
                if (result.status == 200) {
                    inventories = result.inventories;
                    vendor = result.vendor;

                    renderVendor();
                    renderInventories();
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

        function renderProductCurrentPrice(){

        }

        function addToCart() {
            var vendor = $('#select_vendor').val();
            var product = $('#select_product').val();
            var qty = $('#input_qty').val();
            var price = $('#input_price').val();
            var payment = $('#select_payment').val();

            // if null all data data tidak boleh kosong
            if(vendor == null || product == null || qty == '' || price == '') {
                toastr.error('Data tidak boleh kosong');
                return;
            }

            if(vendor == null) {
                toastr.error('Toko tidak boleh kosong');
                return;
            }
            if(product == null) {
                toastr.error('Barang tidak boleh kosong');
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

            // vendor cannot be different with the previous data
            if (cart.length > 0) {
                if (cart[0].vendor != vendor) {
                    toastr.error('Toko tidak boleh berbeda, toko sebelumnya yang anda pilih adalah ' + cart[0].vendor);
                    return;
                }
            }

            var cartData = {
                vendor: vendor,
                product: product,
                qty: qty,
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
                row += `<td>${index + 1}</td>`;
                row += `<td>${item.product}</td>`;
                row += `<td>${item.qty}</td>`;

                let total = item.qty * item.price;
                row += `<td>${total}</td>`;

                row += `<td>${item.payment}</td>`;

                row += `<td><button class="btn btn-sm btn-danger" onclick="removeFromCart(${index})"><i class="fa fa-trash"></i></button></td>`;

                $('#tableNota tbody').append(row);
            });

            var total = cart.reduce((acc, item) => {
                return acc + (item.qty * item.price);
            }, 0);

            var footer = '';
            footer += '<tr>';
            footer += '<td colspan="3" style="text-align:right">Total <br>(RP)</td>';
            footer += `<td colspan="3">${total}<br>`;

            let totalTerbilang = numberToWords(total);

            // totalTerbilan remove duplicate space to single space
            totalTerbilang = totalTerbilang.replace(/\s+/g, ' ');            
            
            footer += `<span style="font-weight:600; font-size:12px;text-transform:uppercase;">${totalTerbilang}</span>`;
                
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

            var formData = new FormData();

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
            $('#select_vendor').on('select2:select', function(e) {
                var data = e.params.data;                
                $('#input_toko_value').text(data.text);
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
                localStorage.setItem('cart', JSON.stringify(cart));
            }

        });        
    </script>
@endsection
