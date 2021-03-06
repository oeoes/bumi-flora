@extends('layouts.master')

@section('page-title', 'Opname')
@section('page-description', 'Opname.')

@section('btn-custom')
<div>
    <button class="btn btn-sm text-muted" data-toggle="modal" data-target="#filter" data-toggle-class="modal-open-aside">
        <span class="d-none d-sm-inline mx-1">Filter</span>
        <i data-feather="sliders"></i>
    </button>

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
                        <form action="">
                            <div class="form-group">
                                <label>Dari</label>
                                <input id="from" type="date" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Hingga</label>
                                <input id="to" type="date" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Jenis</label>
                                <select id="category" class="form-control">
                                    @foreach($categories as $key => $category)
                                    <option value="{{ $category->id }}">{{ $category->category }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Rak</label>
                                <select id="cabinet" class="form-control">
                                    @foreach($cabinet as $key => $cab)
                                    <option value="{{ $cab->cabinet }}">{{ $cab->cabinet }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Storage</label>
                                <select id="dept" class="form-control">
                                    <option value="utama">Utama</option>
                                    <option value="gudang">Gudang</option>
                                </select>
                            </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <div id="run-opname" onclick="filter_opname()" class="btn btn-sm btn-primary">Run</div>
                    </div>
                    </form>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>
</div>
@endsection

@section('custom-css')
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

@section('custom-js')
<script src="{{ asset('js/opname.js') }}"></script>
<script src="{{ asset('js/axios.js') }}"></script>
@endsection

@section('content')
<div class="page-content page-container" id="page-content">
    <div class="padding">
        <div class="row no-gutters">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-theme v-middle table-hover">
                        <thead>
                            <tr>
                                <th><span class="text-muted">Item</span></th>
                                <th><span class="text-muted">Satuan</span></th>
                                <th><span class="text-muted">Jenis</span></th>
                                <th><span class="text-muted">Dept</span></th>
                                <th><span class="text-muted">Rak</span></th>
                                <th><span class="text-muted">Awal</span></th>
                                <th><span class="text-muted">Masuk</span></th>
                                <th><span class="text-muted">Keluar</span></th>
                                <th class="text-center"><span class="text-muted">Buku</span></th>
                                <th class="text-center"><span class="text-muted">Fisik</span></th>
                                <th><span class="text-muted">Selisih</span></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="data-opname">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- <div class="clearfix"></div> -->
    </div>
</div>
@endsection