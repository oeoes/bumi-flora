@extends('layouts.master')

@section('page-title', 'Transaksi Offline')
@section('page-description', 'Histori transaksi offline.')

@section('custom-css')
<link href="{{ asset('css/dataTables.css') }}" rel="stylesheet">
@endsection

@section('custom-js')
<script src="{{ asset('libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('js/dataTables.js') }}"></script>
<script src="{{ asset('js/axios.js') }}"></script>
<script>
    $(document).ready(function() {
        // data table
        $('#history-offline').DataTable();
    });
</script>
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

@section('content')
<div class="page-content page-container" id="page-content">
    <div class="padding">

        <div class="row">
            <div class="col-md-12 mb-5">
                <form action="{{ route('records.offline_transaction_history') }}" method="get">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Dari</label>
                            <input name="from" type="date" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Hingga</label>
                            <input name="to" type="date" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-outline-primary btn-sm rounded-pill pr-4 pl-4 mt-4">View</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-sm-12 col-md-12">
                <div class="bootstrap-table">
                    <div class="fixed-table-container" style="padding-bottom: 0px;">
                        <div class="fixed-table-body">
                            <table id="history-offline" class="table my-responsive table-theme v-middle table-hover" style="margin-top: 0px;">
                                <thead>
                                    <tr>
                                        <th data-field="type">
                                            <div class="th-inner">No. Transaksi</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th data-field="itemtype">
                                            <div class="th-inner">Quantity</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th data-field="itemtype">
                                            <div class="th-inner">Total</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th data-field="itemtype">
                                            <div class="th-inner">Payment Method</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th data-field="itemtype">
                                            <div class="th-inner">Payment Type</div>
                                            <div class="fht-cell"></div>
                                        </th>

                                        <th data-field="itemtype">
                                            <div class="th-inner">Customer</div>
                                            <div class="fht-cell"></div>
                                        </th>

                                        <th data-field="itemtype">
                                            <div class="th-inner">Date</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th data-field="itemtype">
                                            <div class="th-inner">Time</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th data-field="itemtype">
                                            <div class="th-inner">Kasir</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="offline-data">
                                    @foreach($items as $key => $item)
                                    <tr class=" " data-index="0" data-id="17">
                                        <td>
                                            <div class="text-muted text-sm">
                                                <a href="{{ route('records.detail_transaction_history', ['transaction_id' => $item->id, 'dept' => 'utama']) }}">{{ $item->transaction_number }}</a>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="item-amount d-none d-sm-block text-sm ">
                                                {{ $item->quantity }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="item-amount d-none d-sm-block text-sm ">
                                                Rp.{{ number_format((float) ($item->total + $item->tax + $item->additional_fee)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="item-amount d-none d-sm-block text-sm ">
                                                {{ $item->method_name }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="item-amount d-none d-sm-block text-sm ">
                                                {{ $item->type_name }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="item-amount d-none d-sm-block text-sm ">
                                                {{ $item->customer == NULL ? 'Umum' : $item->customer}}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="item-amount d-none d-sm-block text-sm ">
                                                {{ $item->created_at }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="item-amount d-none d-sm-block text-sm ">
                                                {{ $item->transaction_time }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="item-amount d-none d-sm-block text-sm ">
                                                {{ $item->cashier }}
                                            </span>
                                        </td>
                                    </tr>
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