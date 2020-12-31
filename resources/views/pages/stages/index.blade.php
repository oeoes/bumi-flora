@extends('layouts.master')

@section('page-title', 'Pending Items')
@section('page-description', 'Berisi daftar item gudang.')


@section('custom-js')
<script src="{{ asset('js/dataTables.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#item-staging').DataTable();
    });

    $('#generate_barcode').click(function() {
        var result = '';
        var characters = '0123456789';
        var charactersLength = characters.length;
        for (var i = 0; i < 10; i++) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }

        $('#barcode').val(result);
    });

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
                                            <button class="btn btn-sm btn-outline-primary rounded-pill pr-4 pl-4" data-toggle="modal" data-target="#complete-item{{ $key }}">Complete
                                                Item</button>
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
                                                    <form action="{{ route('stages.update', ['stage' => $item->id]) }}" method="post">
                                                        @method('PUT')
                                                        @csrf
                                                        <div class="form-group">
                                                            <label class="text-muted" for="main_cost">Harga Pokok *</label>
                                                            <input type="text" name="main_cost" class="form-control" id="main_cost" placeholder="Harga pokok" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="text-muted" for="price">Harga Jual *</label>
                                                            <div class="row">
                                                                <div class="col-8">
                                                                    <input type="text" name="price" class="form-control" id="price" placeholder="Harga jual" required>
                                                                </div>
                                                                <div class="col-4">
                                                                    <div class="input-group">
                                                                        <input type="text" class="form-control" id="price-percentage" placeholder="Persentase" value="0">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text" id="basic-addon1">%</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- <div style="position: relative">
                                    <input type="range" min="0" max="10000" step="1" class="custom-range mt-2" id="price-range" value="0">
                                    <div class="percentage_range"><span id="percentage-value">0</span>%</div>
                                </div> -->
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