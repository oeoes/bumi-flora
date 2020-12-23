@extends('layouts.master')

@section('page-title', 'Pending Items')
@section('page-description', 'Berisi daftar item gudang.')


@section('custom-js')
<script src="{{ asset('js/dataTables.js') }}"></script>
<script>
    $(document).ready(function () {
        $('#item-staging').DataTable();
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
                            <table id="item-staging" class="table my-responsive table-theme v-middle table-hover"
                                style="margin-top: 0px;">
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
                                            <button class="btn btn-sm btn-outline-primary rounded-pill pr-4 pl-4"
                                                data-toggle="modal" data-target="#complete-item{{ $key }}">Complete
                                                Item</button>
                                        </td>
                                    </tr>

                                    <!-- modal complete item -->
                                    <div id="complete-item{{$key}}" class="modal fade" data-backdrop="true"
                                        style="display: none;" aria-hidden="true">
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
                                                            <label>Harga Pokok</label>
                                                            <input type="number" name="main_cost" class="form-control" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Harga Jual</label>
                                                            <input type="number" name="price" class="form-control" required>
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
