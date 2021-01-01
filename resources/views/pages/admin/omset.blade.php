@extends('layouts.master')

@section('page-title', 'Omset')
@section('page-description', 'Perhitungan Laba.')


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

    .hide-el {
        display: none;
    }
</style>
@endsection

@section('custom-js')
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('js/axios.js') }}"></script>
<script src="{{ asset('js/omset.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#data-item').DataTable();
    });
</script>
@endsection

@section('content')
<div class="page-content page-container" id="page-content">
    <div class="padding">
        <div class="row no-gutters">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-2">
                        <div class="form-group">
                            <label>Dari</label>
                            <input id="transaction_date_from" type="date" class="form-control">
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <label>Hingga</label>
                            <input id="transaction_date_to" type="date" class="form-control">
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <label>Penyimpanan</label>
                            <select id="dept" class="form-control">
                                <option value="utama">Utama</option>
                                <option value="ecommerce">E-Commerce</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <label>Filter Berdasarkan</label>
                            <select id="omset_type" class="form-control">
                                <option value="all">All</option>
                                <option value="item">Item</option>
                                <option value="category">Category</option>
                            </select>
                        </div>
                    </div>
                    <div id="category-block" class="col-2 hide-el">
                        <div class="form-group">
                            <label>Category</label>
                            <select id="category" class="form-control">
                                <option value="default">Pilih category</option>
                                @foreach($categories as $key => $category)
                                <option value="{{ $category->id }}">{{ strtoupper($category->category) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="item-block" class="col-3 hide-el">
                        <div class="form-group">
                            <label>Item</label>
                            <div class="row no-gutters">
                                <div class="col-10">
                                    <input id="item_name" type="text" class="form-control" style="border-radius: 4px 0 0 4px!important" placeholder="select item" readonly>
                                    <input id="item_id" type="hidden" class="form-control">
                                </div>
                                <div class="col-2"><button class="btn btn-primary" style="border-radius: 0 4px 4px 0!important" data-toggle="modal" data-target="#pilih-item">Select</button></div>
                            </div>
                        </div>

                        <!-- Modal pilih item -->
                        <div class="modal fade" id="pilih-item" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Select Item</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <table id="data-item" class="table my-responsive table-theme v-middle table-hover" style="margin-top: 0px;">
                                            <thead style="">
                                                <tr>
                                                    <th style="" data-field="type">
                                                        <div class="th-inner">Nama</div>
                                                        <div class="fht-cell"></div>
                                                    </th>
                                                    <th style="" data-field="itemtype">
                                                        <div class="th-inner">Satuan</div>
                                                        <div class="fht-cell"></div>
                                                    </th>
                                                    <th style="" data-field="itemtype">
                                                        <div class="th-inner">Harga Pokok</div>
                                                        <div class="fht-cell"></div>
                                                    </th>
                                                    <th style="" data-field="itemtype">
                                                        <div class="th-inner">Harga Jual</div>
                                                        <div class="fht-cell"></div>
                                                    </th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($items as $key => $item)
                                                <tr class=" " data-index="0" data-id="17">
                                                    <td style="">
                                                        <div class="text-muted text-sm">
                                                            {{ $item->name }}
                                                        </div>
                                                    </td>
                                                    <td style="">
                                                        <span class="item-amount d-sm-block text-sm">
                                                            {{ $item->unit }}
                                                        </span>
                                                    </td>
                                                    <td style="">
                                                        <span class="item-amount d-sm-block text-sm">
                                                            Rp.{{ number_format((float)$item->main_cost, 2) }}
                                                        </span>
                                                    </td>
                                                    <td style="">
                                                        <span class="item-amount d-sm-block text-sm">
                                                            Rp.{{ number_format((float)$item->price, 2) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button onclick="select_item('{{ $item->id }}', '{{ $item->name }}')" class="btn btn-sm btn-primary rounded-pill"><i data-feather='check'></i></button>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end modal pilih item -->


                    </div>
                    <div class="col-2">
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
                                <th><span class="text-muted">Dis. Item</span></th>
                                <th><span class="text-muted">Dis. Customer</span></th>
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