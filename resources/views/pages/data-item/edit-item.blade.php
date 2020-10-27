@extends('layouts.master')

@section('page-title', 'Edit Item')
@section('page-description', 'Halaman untuk melakukan pembaruan item.')

@section('btn-custom')
<div>
    <a href="{{ route('items.index') }}" class="btn btn-md text-muted">
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
                        <strong>Perbarui Informasi Item</strong>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('items.update', ['item' => $item->id]) }}">
                        @method('PUT')
                            @csrf
                            <div class="form-group">
                                <label class="text-muted" for="name">Nama *</label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="item name" required value="{{ $item->name }}">
                            </div>
                            <div class="form-group">
                                <label class="text-muted" for="barcode">Barcode</label>
                                <input type="text" name="barcode" class="form-control" id="barcode" placeholder="insert barcode" value="{{ $item->barcode }}" readOnly>
                            </div>
                            <div class="form-group">
                                <label class="text-muted" for="unit">Satuan *</label>
                                <select name="unit" id="unit" class="form-control" required>
                                    @foreach($units as $unit)
                                    <option <?php if ($item->unit_id == $unit->id) echo "selected" ?> value="{{ $unit->id }}">{{ $unit->unit }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Satuan Dasar *</label>
                                <input name="base_unit" type="text" class="form-control" value="{{ $item->base_unit }}" required>
                            </div>
                            <div class="form-group">
                                <label>Konversi Satuan Dasar *</label>
                                <input name="base_unit_conversion" type="text" class="form-control" value="{{ $item->base_unit_conversion }}" required>
                            </div>
                            <div class="form-group">
                                <label class="text-muted" for="category">Jenis *</label>
                                <select name="category" id="category" class="form-control" required>
                                    @foreach($categories as $category)
                                    <option <?php if ($item->category_id == $category->id) echo "selected" ?> value="{{ $category->id }}">{{ $category->category }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="text-muted" for="brand">Merk *</label>
                                <select name="brand" id="brand" class="form-control" required>
                                    @foreach($brands as $brand)
                                    <option <?php if ($item->brand_id == $brand->id) echo "selected" ?> value="{{ $brand->id }}">{{ $brand->brand }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="text-muted" for="cabinet">Rak</label>
                                <input type="text" name="cabinet" class="form-control" id="cabinet" placeholder="insert location" value="{{ $item->image }}">
                            </div>
                            <div class="form-group">
                                <label class="text-muted" for="main_cost">Harga Pokok *</label>
                                <input type="text" name="main_cost" class="form-control" id="main_cost" placeholder="Harga pokok" required value="{{ $item->main_cost }}">
                            </div>
                            <div class="form-group">
                                <label class="text-muted" for="price">Harga Jual *</label>
                                <input type="text" name="price" class="form-control" id="price" placeholder="Harga jual" required value="{{ $item->price }}">
                            </div>
                            <div class="form-group">
                                <label class="text-muted" for="min_stock">Stok Minimum *</label>
                                <input type="text" name="min_stock" class="form-control" id="min_stock" placeholder="stock minimum" required value="{{ $item->min_stock }}">
                            </div>
                            <div class="form-group">
                                <label class="text-muted" for="description">Deskripsi</label>
                                <textarea class="form-control" name="description" id="description" cols="3" rows="5">{{ $item->description }}</textarea>
                            </div>
                            <div class="text-muted mb-2"><small>* ) important field</small></div>
                            <button type="submit" class="btn btn-primary">Update Item</button>
                        </form>
                    </div>
                </div>
            </div>   
            <div class="col-md-6">
                <div class="sticky" style="z-index: 1; visibility: visible; transform: none; opacity: 1; transition: ease 1s ease 0s;">
                    <img style="max-width: 100%" src="{{ asset('images/edit-item.svg') }}" alt="">
                </div>
            </div>         
        </div>        
        <div class="clearfix"></div>
    </div>
</div>
@endsection