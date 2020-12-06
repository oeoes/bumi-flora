@extends('layouts.master')

@section('page-title', 'Detail Transaksi')
@section('page-description', 'Detail histori transaksi.')

@section('btn-custom')
<div>
    <button class="btn btn-sm text-muted" data-toggle="modal">
        <span class="d-none d-sm-inline mx-1">Print ulang receipt</span>
        <i data-feather="printer"></i>
    </button>
</div>
@endsection

@section('custom-js')
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
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
                            </table>
                            <table id="data-item" class="table my-responsive table-theme v-middle table-hover mt-4"
                                style="margin-top: 0px;">
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
                                        <th style="" data-field="itemtype">
                                            <div class="th-inner">Jenis</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th style="" data-field="itemtype">
                                            <div class="th-inner">Quantity</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th style="" data-field="itemtype">
                                            <div class="th-inner">Diskon</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th style="" data-field="itemtype">
                                            <div class="th-inner">Biaya Lain</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th style="" data-field="itemtype">
                                            <div class="th-inner">Pajak</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th style="" data-field="itemtype">
                                            <div class="th-inner">Action</div>
                                            <div class="fht-cell"></div>
                                        </th>
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
                                                {{ strtoupper($item->unit) }}
                                            </span>
                                        </td>
                                        <td style="">
                                            <span class="item-amount d-sm-block text-sm">
                                                Rp.{{ number_format($item->main_cost, 2) }}
                                            </span>
                                        </td>
                                        <td style="">
                                            <span class="item-amount d-sm-block text-sm">
                                                Rp.{{ number_format($item->price, 2) }}
                                            </span>
                                        </td>
                                        <td style="">
                                            <span class="item-amount d-none d-sm-block text-sm ">
                                                {{ strtoupper($item->category) }}
                                            </span>
                                        </td>
                                        <td style="">
                                            <span class="item-amount d-none d-sm-block text-sm ">
                                                {{ $item->qty }}
                                            </span>
                                        </td>
                                        <td style="">
                                            <span class="item-amount d-none d-sm-block text-sm ">
                                                {{ $item->discount }}
                                            </span>
                                        </td>
                                        <td style="">
                                            <span class="item-amount d-none d-sm-block text-sm ">
                                                {{ $item->additional_fee }}
                                            </span>
                                        </td>
                                        <td style="">
                                            <span class="item-amount d-none d-sm-block text-sm ">
                                                {{ $item->tax }}
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-outline-primary btn-sm rounded-pill pr-4 pl-4" data-toggle="modal"
                                                data-target="#edit-item{{ $key }}">Edit</button>
                                            <button class="btn btn-outline-danger btn-sm rounded-pill pr-4 pl-4"
                                                data-toggle="modal"
                                                data-target="#delete-item{{ $key }}">Delete</button>
                                        </td>
                                    </tr>

                                    <!-- item modal edit -->
                                    <div id="edit-item{{ $key }}" class="modal fade" data-backdrop="true"
                                        aria-hidden="true" style="display: none;">
                                        <div class="modal-dialog ">
                                            <div class="modal-content ">
                                                <div class="modal-header ">
                                                    <div class="modal-title text-md">Edit Item</div>
                                                    <button class="close" data-dismiss="modal">×</button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="post"
                                                        action="{{ route('records.update', ['record' => $item->transaction_id]) }}">
                                                        @method('PUT')
                                                        @csrf
                                                        <div class="form-group">
                                                            <label>Quantity</label>
                                                            <input name="qty" type="text" class="form-control" value="{{ $item->qty }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Discount (Rp)</label>
                                                            <input name="discount" type="text" class="form-control" value="{{ $item->discount }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Biaya Lain</label>
                                                            <input name="additional_fee" type="text" class="form-control" value="{{ $item->additional_fee }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Pajak</label>
                                                            <input name="tax" type="text" class="form-control" value="{{ $item->tax }}">
                                                        </div>                                                        
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button"
                                                        class="btn btn-outline-secondary btn-sm rounded-pill pr-4 pl-4"
                                                        data-dismiss="modal">Close</button>
                                                    <button type="submit"
                                                        class="btn btn-outline-primary btn-sm rounded-pill pr-4 pl-4">Save
                                                        Changes</button>
                                                    </form>
                                                </div>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                    </div>

                                    <!-- item modal delete -->
                                    <div id="delete-item{{ $key }}" class="modal fade" data-backdrop="true"
                                        aria-hidden="true" style="display: none;">
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
                                                        <button type="button"
                                                            class="btn btn-outline-secondary btn-sm rounded-pill pr-4 pl-4"
                                                            data-dismiss="modal">Cancle</button>
                                                        <button type="submit"
                                                            class="btn btn-outline-danger btn-sm rounded-pill pr-4 pl-4">Yes</button>
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
