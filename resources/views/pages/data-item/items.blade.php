@extends('layouts.master')

@section('page-title', 'Data Item')
@section('page-description', 'Berisi daftar item dan akses untuk melakukan penambahan item.')

@section('btn-custom')
<div>
    <a href="{{ route('items.create') }}" class="btn btn-sm text-muted">
        <span class="d-none d-sm-inline mx-1">Add item</span>
        <i data-feather="plus-circle"></i>
    </a>
    <button class="btn btn-sm text-muted" data-toggle="modal" data-target="#import"
        data-toggle-class="modal-open-aside">
        <span class="d-none d-sm-inline mx-1">Import</span>
        <i data-feather="download-cloud"></i>
    </button>
    <a href="{{ route('items.export-item') }}" class="btn btn-sm text-muted" >
        <span class="d-none d-sm-inline mx-1">Export</span>
        <i data-feather="upload-cloud"></i>
    </a>

    <!-- modal import data item -->
    <div id="import" class="modal fade" da ta-backdrop="true" tyle="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content h-100 no-radius">
                <div class="modal-header ">
                    <div class="modal-title text-sm">Import item</div>
                    <button class="close" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <div class="p-2">
                        <div class="form-group">
                            <label for="dept">Pilih file</label>
                            <input id="import_file_field" type="file" class="form-control form-control-sm">
                            <small id="error-type" class="text-danger"></small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="upload-file" class="btn btn-primary" type="button">
                        <span id="spin" class="spinner-border spinner-border-sm mr-1" role="status"
                            aria-hidden="true"></span>
                        <span id="upload-text">Upload</span>
                    </button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>
</div>
@endsection

@section('custom-js')
<script src="{{ asset('js/dataTables.js') }}"></script>
<script src="{{ asset('js/upload-file.js') }}"></script>
<script src="{{ asset('js/axios.js') }}"></script>
<script>
    $(document).ready(function () {
        $('#data-item').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('items.ajax') }}",
            columns: [{
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'barcode',
                    name: 'barcode'
                },
                {
                    data: 'main_cost',
                    name: 'main_cost'
                },
                {
                    data: 'price',
                    name: 'price'
                },
                {
                    data: 'base_unit',
                    name: 'base_unit'
                },
                {
                    data: 'base_unit_conversion',
                    name: 'base_unit_conversion'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });
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
                            <table id="data-item" class="table my-responsive table-theme v-middle table-hover"
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
                                            <div class="th-inner">Harga Pokok</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th style="">
                                            <div class="th-inner">Harga Jual</div>
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
                                            <div class="th-inner "><span class="d-none d-sm-block"></span></div>
                                            <div class="fht-cell"></div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>

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
