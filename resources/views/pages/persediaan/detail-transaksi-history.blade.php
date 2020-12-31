@extends('layouts.master')

@section('page-title', 'Detail Transaksi')
@section('page-description', 'Detail histori transaksi.')

@section('btn-custom')
<div>
    <a href="{{ $base->dept === 'utama' ? route('records.offline_transaction_history') : route('records.online_transaction_history') }}" class="btn btn-sm text-muted">
        <i data-feather="arrow-left"></i>
        <span class="d-none d-sm-inline mx-1">Back</span>
    </a>
</div>
@endsection

@section('custom-js')
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#data-item').DataTable();
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
                            <table class="table table-responsive table-theme v-middle table-hover table-sm mb-4">
                                <tr>
                                    <td>No. Transaksi</td>
                                    <td> : </td>
                                    <td>{{ $base->transaction_number }}</td>
                                </tr>
                                <tr>
                                    <td>Tanggal</td>
                                    <td> : </td>
                                    <td>{{ \Carbon\Carbon::parse($base->created_at)->format('Y-m-d') }}</td>
                                </tr>
                                <tr>
                                    <td>Waktu</td>
                                    <td> : </td>
                                    <td>{{ $base->transaction_time }}</td>
                                </tr>
                                <tr>
                                    <td>Metode Pembayaran</td>
                                    <td> : </td>
                                    <td>{{ $base->method_name }}</td>
                                </tr>
                                <tr>
                                    <td>Jenis Pembayaran</td>
                                    <td> : </td>
                                    <td>{{ $base->type_name }}</td>
                                </tr>
                                <tr>
                                    <td>Pelanggan</td>
                                    <td> : </td>
                                    <td>{{ !$base->customer ? 'Umum' : $base->customer }}</td>
                                </tr>
                                <tr>
                                    <td>Pajak</td>
                                    <td> : </td>
                                    <td>Rp.{{ number_format($base->tax) }}</td>
                                </tr>
                                <tr>
                                    <td>Biaya lain</td>
                                    <td> : </td>
                                    <td>Rp.{{ number_format($base->additional_fee) }}</td>
                                </tr>
                                <tr>
                                    <td><a href="{{ route('records.live_edit_transaction', ['transaction' => $base->id]) }}" class="btn btn-sm btn-outline-success rounded-pill pr-4 pl-4 mt-4">Live Edit</a></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </table>
                            <table id="data-item" class="table my-responsive table-theme v-middle table-hover mt-4" style="margin-top: 0px;">
                                <thead>
                                    <tr>
                                        <th data-field="type">
                                            <div class="th-inner">Nama</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th data-field="itemtype">
                                            <div class="th-inner">Satuan</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th data-field="itemtype">
                                            <div class="th-inner">Harga Jual</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th data-field="itemtype">
                                            <div class="th-inner">Jenis</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th data-field="itemtype">
                                            <div class="th-inner">Quantity</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th data-field="itemtype">
                                            <div class="th-inner">Diskon</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th data-field="itemtype">
                                            <div class="th-inner">Disc. Item</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th data-field="itemtype">
                                            <div class="th-inner">Disc. Customer</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th data-field="itemtype">
                                            <div class="th-inner">Action</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $key => $item)
                                    <tr class=" " data-index="0" data-id="17">
                                        <td>
                                            <div class="text-muted text-sm">
                                                {{ $item->name }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="item-amount d-sm-block text-sm">
                                                {{ strtoupper($item->unit) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="item-amount d-sm-block text-sm">
                                                Rp.{{ number_format($item->price, 2) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="item-amount d-none d-sm-block text-sm ">
                                                {{ strtoupper($item->category) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="item-amount d-none d-sm-block text-sm ">
                                                {{ $item->qty }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="item-amount d-none d-sm-block text-sm ">
                                                {{ $item->discount }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="item-amount d-none d-sm-block text-sm ">
                                                {{ $item->discount_item }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="item-amount d-none d-sm-block text-sm ">
                                                {{ $item->discount_customer }}
                                            </span>
                                        </td>
                                        <td>
                                            <!-- <button class="btn btn-outline-primary btn-sm rounded-pill pr-4 pl-4" data-toggle="modal" data-target="#edit-item{{ $key }}">Edit</button> -->
                                            @can('delete')
                                            <button class="btn btn-outline-danger btn-sm rounded-pill pr-4 pl-4" data-toggle="modal" data-target="#delete-item{{ $key }}">Delete</button>
                                            @endcan
                                        </td>
                                    </tr>
                                    <!-- item modal delete -->
                                    <div id="delete-item{{ $key }}" class="modal fade" data-backdrop="true" aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog ">
                                            <div class="modal-content ">
                                                <div class="modal-header ">
                                                    <div class="modal-title text-md">Confirm Deletion</div>
                                                    <button class="close" data-dismiss="modal">×</button>
                                                </div>
                                                <form method="post" action="{{ route('records.destroy', ['record' => $item->transaction_id]) }}">
                                                    @method('DELETE')
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="p-2">
                                                            <p>You're about to delete existing brand. Klick "Yes" to
                                                                proceed.</p>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill pr-4 pl-4" data-dismiss="modal">Cancle</button>
                                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill pr-4 pl-4">Yes</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <!-- /.modal-content -->
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