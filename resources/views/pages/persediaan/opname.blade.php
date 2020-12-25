@extends('layouts.master')

@section('page-title', 'Opname')
@section('page-description', 'Opname.')


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
            <div class="col-md-9">
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
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        Filter
                    </div>
                    <div class="card-body">
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
                            <div id="run-opname" onclick="filter_opname()" class="btn btn-sm btn-primary">Run</div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="clearfix"></div> -->
    </div>
</div>
@endsection