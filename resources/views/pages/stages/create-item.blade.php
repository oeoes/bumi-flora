@extends('layouts.master')

@section('page-title', 'Staging Area')
@section('page-description', 'Add item to staging area.')

@section('custom-js')
    <script>
        $('#generate_barcode').click(function(){
            var result           = '';
            var characters       = '0123456789';
            var charactersLength = characters.length;
            for ( var i = 0; i < 11; i++ ) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }

            $('#barcode').val(result);
        });
    </script>
@endsection

@section('btn-custom')
<div>
    <a href="{{ route('stages.index') }}" class="btn btn-sm text-muted">
        <i data-feather="arrow-left"></i>
        <span class="d-none d-sm-inline mx-1">Back</span>
    </a>
</div>
@endsection

@section('content')
<div class="page-content page-container" id="page-content">
    <div class="padding">
        <div class="row">            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <strong>Tambah item</strong>
                        
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('stages.store') }}">
                            @csrf
                            <div class="form-group">
                                <label class="text-muted" for="name">Nama *</label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="item name" required>
                            </div>
                            <div class="form-group">
                                <label class="text-muted" for="barcode">Barcode *</label>
                                <div class="form-row">
                                    <div class="col-10">
                                        <input type="text" name="barcode" class="form-control" id="barcode" placeholder="insert barcode" required>
                                    </div>
                                    <div class="col-2">
                                        <span style="cursor: pointer" id="generate_barcode" class="btn btn-outline-primary btn-block"><i data-feather='rotate-cw'></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="text-muted" for="unit">Satuan *</label>
                                <select name="unit" id="unit" class="form-control" required>
                                    @foreach($units as $unit)
                                    <option value="{{ $unit->id }}">{{ strtoupper($unit->unit) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Satuan Dasar *</label>
                                <input name="base_unit" type="text" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Konversi Satuan Dasar *</label>
                                <input name="base_unit_conversion" type="text" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label class="text-muted" for="category">Jenis *</label>
                                <select name="category" id="category" class="form-control" required>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ strtoupper($category->category) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="text-muted" for="brand">Merk *</label>
                                <select name="brand" id="brand" class="form-control" required>
                                    @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->brand }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="text-muted" for="cabinet">Rak</label>
                                <input type="text" name="cabinet" class="form-control" id="cabinet" placeholder="insert location">
                            </div>
                            <div class="form-group">
                                <label class="text-muted" for="min_stock">Stok Minimum *</label>
                                <input type="text" name="min_stock" class="form-control" id="min_stock" placeholder="stock minimum" required>
                            </div>
                            <div class="form-group">
                                <label class="text-muted" for="description">Deskripsi</label>
                                <textarea class="form-control" name="description" id="description" cols="3" rows="5"></textarea>
                            </div>
                            <div class="text-muted mb-2"><small>* ) important field</small></div>
                            <button type="submit" class="btn btn-primary">Add Item</button>
                        </form>
                    </div>
                </div>
            </div> 
            <div class="col-md-6">
                <div class="sticky" style="z-index: 1; visibility: visible; transform: none; opacity: 1; transition: ease 1s ease 0s;">
                    <img style="max-width: 100%" src="{{ asset('images/add-item.svg') }}" alt="">
                </div>
            </div>           
        </div>        
        <div class="clearfix"></div>
    </div>
</div>
@endsection