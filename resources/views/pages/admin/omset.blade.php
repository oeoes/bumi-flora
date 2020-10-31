@extends('layouts.master')

@section('page-title', 'Omset')
@section('page-description', 'Perhitungan Laba.')


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
<script src="{{ asset('js/axios.js') }}"></script>
<script src="{{ asset('js/omset.js') }}"></script>
@endsection

@section('content')
<div class="page-content page-container" id="page-content">
    <div class="padding">
        <div class="row no-gutters">
            <div class="col-md-12">
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <label>Dari</label>
                        <input id="transaction_date_from" type="date" class="form-control">
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label>Hingga</label>
                        <input id="transaction_date_to" type="date" class="form-control">
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group mt-2">
                        <button id="omset-run-sort" class="btn btn-sm btn-primary rounded-pill mt-4 pl-3 pr-3">View</button>
                    </div>
                </div>
            </div>
                <div class="table-responsive">
                    <table class="table table-theme v-middle table-hover">
                        <thead>
                            <tr>
                                <th><span class="text-muted">Item</span></th>
                                <th><span class="text-muted">Satuan</span></th>
                                <th><span class="text-muted">Jenis</span></th>
                                <th><span class="text-muted">Harga Pokok</span></th>
                                <th><span class="text-muted">Harga Jual</span></th>
                                <th><span class="text-muted">Kuantitas</span></th>
                                <th><span class="text-muted">Diskon</span></th>
                                <th><span class="text-muted">Pendapatan</span></th>
                                <th><span class="text-muted">Laba</span></th>
                            </tr>
                        </thead>
                        <tbody id="data-omset">
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- <div class="clearfix"></div> -->
    </div>
</div>
@endsection
