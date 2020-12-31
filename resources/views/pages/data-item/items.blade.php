@extends('layouts.master')

@section('page-title', 'Data Item')
@section('page-description', 'Berisi daftar item dan akses untuk melakukan penambahan item.')

@section('btn-custom')
<div>
    <a href="{{ route('items.create') }}" class="btn btn-sm text-muted">
        <span class="d-none d-sm-inline mx-1">Add item</span>
        <i data-feather="plus-circle"></i>
    </a>
    <button class="btn btn-sm text-muted" data-toggle="modal" data-target="#import" data-toggle-class="modal-open-aside">
        <span class="d-none d-sm-inline mx-1">Import</span>
        <i data-feather="download-cloud"></i>
    </button>

    <button class="btn btn-sm text-muted" data-toggle="modal" data-target="#export" data-toggle-class="modal-open-aside">
        <span class="d-none d-sm-inline mx-1">Export</span>
        <i data-feather="upload-cloud"></i>
    </button>

    <button class="btn btn-sm text-muted" data-toggle="modal" data-target="#filter" data-toggle-class="modal-open-aside">
        <span class="d-none d-sm-inline mx-1">Filter</span>
        <i data-feather="sliders"></i>
    </button>

    <button class="btn btn-sm text-muted" data-toggle="modal" data-target="#reset" data-toggle-class="modal-open-aside">
        <span class="d-none d-sm-inline mx-1">Reset Data</span>
        <i data-feather="trash"></i>
    </button>

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
                        <div class="form-group">
                            <label for="dept">Import ke</label>
                            <select id="dept" class="form-control">
                                <option value="utama">Peyimpanan Utama</option>
                                <option value="gudang">Peyimpanan Gudang</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="upload-file" class="btn btn-sm btn-outline-primary rounded-pill" type="button">
                        <span id="spin" class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span>
                        <span id="upload-text">Upload</span>
                    </button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>

    <!-- modal export data item -->
    <div id="export" class="modal fade" da ta-backdrop="true" tyle="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content h-100 no-radius">
                <div class="modal-header ">
                    <div class="modal-title text-sm">Export item</div>
                    <button class="close" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <div class="p-2">
                        <div class="card-title text-muted">Pilih jenis file</div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="md-check">
                                        <input type="radio" name="file_type" checked value="pdf">
                                        <i class="blue"></i>
                                        PDF document (*.pdf)
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="md-check">
                                        <input type="radio" name="file_type" value="xlsx">
                                        <i class="blue"></i>
                                        Excel document (*.xlsx)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="card-title text-muted">Pilih jenis laporan</div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="md-check">
                                        <input type="radio" name="report_type" checked value="main_cost">
                                        <i class="green"></i>
                                        Daftar Item Harga Pokok
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="md-check">
                                        <input type="radio" name="report_type" value="price">
                                        <i class="green"></i>
                                        Daftar Item Harga Jual
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="md-check">
                                        <input type="radio" name="report_type" value="default">
                                        <i class="green"></i>
                                        Daftar Item Tanpa Harga
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="md-check">
                                        <input type="radio" name="report_type" value="complete">
                                        <i class="green"></i>
                                        Daftar Item Lengkap
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="download-file" class="btn btn-sm btn-outline-primary rounded-pill" type="button">
                        <span id="download-text">Download</span>
                    </button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>

    <!-- modal aside filter -->
    <div id="filter" class="modal fade modal-open-aside" data-backdrop="true" data-class="modal-open-aside" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-right w-xl">
            <div class="modal-content h-100 no-radius">
                <div class="modal-header ">
                    <div class="modal-title text-sm">Filter Item</div>
                    <button class="close" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <div class="p-2">
                        <form action="{{ route('items.index') }}" method="get">
                            <div class="form-group">
                                <label for="name">Item name</label>
                                <input class="form-control" type="text" class="form-contro" name="filter[items.name]">
                            </div>
                            <div class="form-group">
                                <label for="name">Barcode</label>
                                <input class="form-control" type="text" class="form-contro" name="filter[items.barcode]">
                            </div>
                            <div class="form-group">
                                <label for="categories">Category</label>
                                <select name="filter[categories.id]" id="" class="form-control">
                                    <option value="">Pilih jenis item</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ strtoupper($category->category) }}</option>
                                    @endforeach
                                </select>
                            </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill pr-4 pl-4">Run</button>
                    </form>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>

    <!-- modal reset data item -->
    <div id="reset" class="modal fade" da ta-backdrop="true" tyle="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content h-100 no-radius">
                <div class="modal-header ">
                    <div class="modal-title text-warning">Warning!!</div>
                    <button class="close" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <div class="p-2">
                        Reset data will erase all of your existing data items. Click "<b>Yes</b>" to proceed.
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-outline-secondary rounded-pill pl-3 pr-3" data-dismiss="modal">Cancle</button>
                    <form action="{{ route('items.reset_data_item') }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill pl-3 pr-3">Yes</button>
                    </form>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>
</div>
@endsection

@section('custom-js')
<script src="{{ asset('js/dataTables.js') }}"></script>
<script src="{{ asset('js/data-item-functions.js') }}"></script>
<script src="{{ asset('js/axios.js') }}"></script>
<script>
    // $(document).ready(function() {
    //     $('#data-item').DataTable({
    //         processing: true,
    //         serverSide: true,
    //         ajax: "{{ route('items.ajax', ['published' => 1]) }}",
    //         columns: [{
    //                 data: 'name',
    //                 name: 'name'
    //             },
    //             {
    //                 data: 'barcode',
    //                 name: 'barcode'
    //             },
    //             {
    //                 data: 'main_cost',
    //                 name: 'main_cost'
    //             },
    //             {
    //                 data: 'price',
    //                 name: 'price'
    //             },
    //             {
    //                 data: 'base_unit',
    //                 name: 'base_unit'
    //             },
    //             {
    //                 data: 'base_unit_conversion',
    //                 name: 'base_unit_conversion'
    //             },
    //             {
    //                 data: 'action',
    //                 name: 'action',
    //                 orderable: false,
    //                 searchable: false
    //             },
    //         ]
    //     });
    // });
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
                            <table id="data-item" class="table my-responsive table-theme v-middle table-hover" style="margin-top: 0px;">
                                <thead>
                                    <tr>

                                        <th>
                                            <div class="th-inner">Kode item</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th>
                                            <div class="th-inner">Barcode</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th>
                                            <div class="th-inner">Nama</div>
                                            <div class="fht-cell"></div>
                                        </th>

                                        <th>
                                            <div class="th-inner">Satuan</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th>
                                            <div class="th-inner">Jenis</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th>
                                            <div class="th-inner">Merek</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th>
                                            <div class="th-inner">Harga Pokok</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th>
                                            <div class="th-inner">Harga Jual</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th>
                                            <div class="th-inner">St. Das </div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th>
                                            <div class="th-inner">Konv. St Das </div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th>
                                            <div class="th-inner ">Action</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $key => $item)
                                    <tr>
                                        <td>{{ $item->item_code }}</td>
                                        <td>{{ $item->barcode }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->unit }}</td>
                                        <td>{{ $item->category }}</td>
                                        <td>{{ $item->brand }}</td>
                                        <td>Rp.{{ number_format($item->main_cost) }}</td>
                                        <td>Rp.{{ number_format($item->price) }}</td>
                                        <td>{{ $item->base_unit }}</td>
                                        <td>{{ $item->base_unit_conversion }}</td>
                                        <td>
                                            <a href="{{ route('items.show', ['item' => $item->id]) }}" class="btn btn-sm btn-outline-primary rounded-pill pr-3 pl-3"><i data-feather="eye"></i></a>
                                        </td>
                                    </tr>


                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pull-right">{{ $items }}</div>
                        </div>
                    </div>
                </div>
            </div>


        </div>


        <div class="clearfix"></div>
    </div>
</div>
@endsection