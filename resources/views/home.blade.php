@extends('layouts.app')

@section('content-header')
    <div class="container-fluid">        
    </div>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                {{-- <div class="card-header"></div> --}}
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <p>MENU</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <button class="btn btn-lg bg-blue" style="width: 100%;">
                                <i class="fa-solid fa-boxes-packing"></i>
                                <p>Barang Masuk <small>(IN)</small></p>
                            </button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-lg bg-gray" style="width: 100%;">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                <p>Barang Keluar <small>(OUT)</small></p>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
