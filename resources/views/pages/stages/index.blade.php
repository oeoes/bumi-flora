@extends('layouts.master')

@section('page-title', 'Pending Items')
@section('page-description', 'Berisi daftar item gudang.')


@section('custom-js')
<script src="{{ asset('js/dataTables.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#item-staging').DataTable();
    });

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

    });


    // complete item
    // price diisi
    $(document).on('keyup', '#complete_price', function() {
        let percentage = ((parseInt($('#complete_price').val()) - parseInt($('#complete_main_cost').val())) / $('#complete_main_cost').val()) * 100;
        $('#complete_price-percentage').val(percentage);
    });

    // percentage price diisi
    $(document).on('keyup', '#complete_price-percentage', function() {
        let price = parseInt($('#complete_main_cost').val()) + (parseInt($('#complete_main_cost').val()) * parseInt($('#complete_price-percentage').val()) / 100);
        $('#complete_price').val(price);
    });
</script>
@endsection

@section('custom-css')
<link href="{{ asset('css/dataTables.css') }}" rel="stylesheet">
<style>
    @media only screen and (max-width: 600px) {
        .my-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
        }
    }
</style>
@endsection

@section('content')
<div class="page-content page-container" id="page-content">
    <div class="padding">

        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="bootstrap-table">
                    <div class="fixed-table-container" style="padding-bottom: 0px;">
                        <div class="fixed-table-body">
                            <table id="item-staging" class="table my-responsive table-theme v-middle table-hover" style="margin-top: 0px;">
                                <thead style="">
                                    <tr>
                                        <th style="">
                                            <div class="th-inner">Nama</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th style="">
                                            <div class="th-inner">Barcode</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th style="">
                                            <div class="th-inner">Satuan</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th style="">
                                            <div class="th-inner">Kategori</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th style="">
                                            <div class="th-inner">Merek</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th style="">
                                            <div class="th-inner">Satuan Dasar </div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th style="">
                                            <div class="th-inner">Konversi Satuan Dasar </div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th style="">
                                            <div class="th-inner">Action</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $key => $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->barcode }}</td>
                                        <td>{{ $item->unit }}</td>
                                        <td>{{ $item->category }}</td>
                                        <td>{{ $item->brand }}</td>
                                        <td>{{ $item->base_unit }}</td>
                                        <td>{{ $item->base_unit_conversion }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary rounded-pill pr-3 pl-3" data-toggle="modal" data-target="#complete-item{{ $key }}"><i data-feather="share"></i></button>
                                            <button class="btn btn-sm btn-outline-info rounded-pill pl-3 pr-3" data-toggle="modal" data-target="#edit-item{{ $key }}"><i data-feather="edit-2"></i></button>
                                            <button class="btn btn-sm btn-outline-danger rounded-pill pl-3 pr-3" data-toggle="modal" data-target="#delete-item{{ $key }}"><i data-feather="trash"></i></button>
                                        </td>
                                    </tr>

                                    <!-- modal complete item -->
                                    <div id="complete-item{{$key}}" class="modal fade" data-backdrop="true" style="display: none;" aria-hidden="true">
                                        <div class="modal-dialog animate" data-class="fade-down">
                                            <div class="modal-content ">
                                                <div class="modal-header ">
                                                    <div class="modal-title text-md">Lengkapi Item</div>
                                                    <button class="close" data-dismiss="modal">×</button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('stages.complete_item', ['stage' => $item->id]) }}" method="post">
                                                        @method('PUT')
                                                        @csrf
                                                        <div class="form-group">
                                                            <label class="text-muted" for="complete_main_cost">Harga Pokok *</label>
                                                            <input type="text" name="main_cost" class="form-control" id="complete_main_cost" placeholder="Harga pokok" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="text-muted" for="complete_price">Harga Jual *</label>
                                                            <div class="row">
                                                                <div class="col-8">
                                                                    <input type="text" name="price" class="form-control" id="complete_price" placeholder="Harga jual" required>
                                                                </div>
                                                                <div class="col-4">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" id="complete_price-percentage" placeholder="Persentase" value="0">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text" id="basic-addon1">%</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                </div>
                                                <div class="modal-footer">
                                                    @can('update')
                                                    <button type="submit" class="btn btn-sm btn-outline-success rounded-pill pr-4 pl-4">Save & Publish</button>
                                                    @endcan
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- modal edit item -->
                                    <div id="edit-item{{$key}}" class="modal fade" data-backdrop="true" style="display: none;" aria-hidden="true">
                                        <div class="modal-dialog animate" data-class="fade-down">
                                            <div class="modal-content ">
                                                <div class="modal-header ">
                                                    <div class="modal-title text-md">Update Item</div>
                                                    <button class="close" data-dismiss="modal">×</button>
                                                </div>
                                                <div class="modal-body">
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

                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-sm btn-outline-secondary rounded-pill pr-4 pl-4" data-dismiss="modal">Cancle</button>
                                                    <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill pr-4 pl-4">Update</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- modal delete item -->
                                    <div id="delete-item{{$key}}" class="modal fade" data-backdrop="true" style="display: none;" aria-hidden="true">
                                        <div class="modal-dialog animate" data-class="fade-down">
                                            <div class="modal-content ">
                                                <div class="modal-header ">
                                                    <div class="modal-title text-md">Delete Item</div>
                                                    <button class="close" data-dismiss="modal">×</button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('stages.destroy', ['stage' => $item->id]) }}" method="post">
                                                        @method('DELETE')
                                                        @csrf
                                                        Hapus Item pada pada staging area?

                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-sm btn-outline-secondary rounded-pill pr-4 pl-4" data-dismiss="modal">Cancle</button>
                                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill pr-4 pl-4">Yes</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="fixed-table-footer" style="display: none;">
                            <table>
                                <tbody>
                                    <tr></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </div>


        <div class="clearfix"></div>
    </div>
</div>
@endsection