@extends('layouts.master')

@section('page-title', 'Staging Item Update')
@section('page-description', 'Update item on staging area.')

@section('custom-js')
<script>
    $(document).ready(function() {
        $('#price-range').prop('disabled', true);


        $('#generate_barcode').click(function() {
            var result = '';
            var characters = '0123456789';
            var charactersLength = characters.length;
            for (var i = 0; i < 10; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }

            $('#barcode').val(result);
            axios.get(`/app/items/availability/${result}`).then(function(response) {
                let data = response.data
                if (data.status === true) {
                    $('#availability').text('Kode barcode bisa digunakan')
                    $('#availability').removeClass('text-danger')
                    $('#availability').addClass('text-success')
                } else {
                    $('#availability').html(`Kode barcode sudah digunakan <br> code: ${data.data.code} <br> name: ${data.data.name} `)
                    $('#availability').removeClass('text-success')
                    $('#availability').addClass('text-danger')
                }
            })
        });

        $('#barcode').keyup(function() {
            axios.get(`/app/items/availability/${$('#barcode').val()}`).then(function(response) {
                let data = response.data
                if (data.status === true) {
                    $('#availability').text('Kode barcode bisa digunakan')
                    $('#availability').removeClass('text-danger')
                    $('#availability').addClass('text-success')
                } else {
                    $('#availability').html(`Kode barcode sudah digunakan <br> code: ${data.data.code} <br> name: ${data.data.name} `)
                    $('#availability').removeClass('text-success')
                    $('#availability').addClass('text-danger')
                }
            })
        })

        // price diisi
        $(document).on('keyup', '#price', function() {
            let percentage = ((parseInt($('#price').val()) - parseInt($('#main_cost').val())) / $('#main_cost').val()) * 100;
            $('#price-percentage').val(percentage);
        });

        // percentage price diisi
        $(document).on('keyup', '#price-percentage', function() {
            let price = parseInt($('#main_cost').val()) + (parseInt($('#main_cost').val()) * parseInt($('#price-percentage').val()) / 100);
            $('#price').val(price);
        });

        // make sure main cost harus ada isinya
        $(document).on('keyup', '#main_cost', function() {
            if ($('#main_cost').val() == '') {
                $('#price').prop('readonly', true);
                $('#price-percentage').prop('readonly', true);
            } else {
                $('#price').prop('readonly', false);
                $('#price-perecentage').prop('readonly', false);
            }
        })


        $(document).on('input', '#price-range', function() {
            let price = parseInt($('#main_cost').val()) + (parseInt($('#main_cost').val()) * parseInt($('#price-range').val()) / 100);
            console.log(price);
            $('#price').val(price);
            $('#percentage-value').text($('#price-range').val())

            if ($('#price-range').val() > 5) {
                $('.percentage_range').addClass('text-success');
            } else {
                $('.percentage_range').removeClass('text-success');
            }
            if ($('#price-range').val() < 90) {
                $('#percentage-value').css('paddingLeft', $('#price-range').val() + '%')
            } else {
                $('#percentage-value').css('paddingLeft', ($('#price-range').val() - 10) + '%')
            }

        })
    })
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
                        <strong>Edit item</strong>

                    </div>
                    <div class="card-body">
                        <form action="{{ route('stages.update', ['stage' => $item->id]) }}" method="post">
                            @method('PUT')
                            @csrf
                            <div class="form-group">
                                <label class="text-muted" for="name">Nama *</label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="item name" required value="{{ $item->name }}">
                            </div>
                            <div class="form-group">
                                <label class="text-muted" for="barcode">Barcode *</label>
                                <div class="form-row">
                                    <div class="col-10">
                                        <input type="text" name="barcode" class="form-control" id="barcode" placeholder="insert barcode" required value="{{ $item->barcode }}">
                                    </div>
                                    <div class="col-2">
                                        <span style="cursor: pointer" id="generate_barcode" class="btn btn-outline-primary btn-block"><i data-feather='rotate-cw'></i></span>
                                    </div>
                                </div>
                                <small id="availability"></small>
                            </div>
                            <div class="form-group">
                                <label class="text-muted" for="unit">Satuan *</label>
                                <select name="unit" id="unit" class="form-control" required>
                                    @foreach($units as $unit)
                                    @if($item->unit_id == $unit->id)
                                    <option value="{{ $unit->id }}" selected>{{ strtoupper($unit->unit) }}</option>
                                    @else
                                    <option value="{{ $unit->id }}">{{ strtoupper($unit->unit) }}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Satuan Dasar *</label>
                                <input name="base_unit" type="text" class="form-control" required value="{{ $item->base_unit }}">
                            </div>
                            <div class="form-group">
                                <label>Konversi Satuan Dasar *</label>
                                <input name="base_unit_conversion" type="text" class="form-control" required value="{{ $item->base_unit_conversion }}">
                            </div>
                            <div class="form-group">
                                <label class="text-muted" for="category">Jenis *</label>
                                <select name="category" id="category" class="form-control" required>
                                    @foreach($categories as $category)
                                    @if($item->category_id == $category->id)
                                    <option value="{{ $category->id }}" selected>{{ strtoupper($category->category) }}</option>
                                    @else
                                    <option value="{{ $category->id }}">{{ strtoupper($category->category) }}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="text-muted" for="brand">Merk *</label>
                                <select name="brand" id="brand" class="form-control" required>
                                    @foreach($brands as $brand)
                                    @if($item->brand_id == $brand->id)
                                    <option value="{{ $brand->id }}" selected>{{ $brand->brand }}</option>
                                    @else
                                    <option value="{{ $brand->id }}">{{ $brand->brand }}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="text-muted" for="cabinet">Rak</label>
                                <input type="text" name="cabinet" class="form-control" id="cabinet" placeholder="insert location" value="{{ $item->cabinet }}">
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
                    <img style="max-width: 100%" src="{{ asset('images/add-item.svg') }}" alt="">
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
@endsection