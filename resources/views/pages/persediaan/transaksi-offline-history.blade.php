@extends('layouts.master')

@section('page-title', 'Transaksi Offline')
@section('page-description', 'Histori transaksi offline.')

@section('custom-js')
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#data-item').DataTable();
    });
</script>
@endsection

@section('custom-css')
    <link  href="{{ asset('css/dataTables.css') }}" rel="stylesheet">
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
                                <thead style="">
                                    <tr>
                                        <th style="" data-field="type">
                                            <div class="th-inner">No. Transaksi</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th style="" data-field="itemtype">
                                            <div class="th-inner">Quantity</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th style="" data-field="itemtype">
                                            <div class="th-inner">Payment Method</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th style="" data-field="itemtype">
                                            <div class="th-inner">Payment Type</div>
                                            <div class="fht-cell"></div>
                                        </th>

                                        <th style="" data-field="itemtype">
                                            <div class="th-inner">Customer</div>
                                            <div class="fht-cell"></div>
                                        </th>

                                        <th style="" data-field="itemtype">
                                            <div class="th-inner">Date</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th style="" data-field="itemtype">
                                            <div class="th-inner">Time</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $key => $item)
                                    <tr class=" " data-index="0" data-id="17">
                                        <td style="">
                                            <div class="text-muted text-sm">
                                                <a href="{{ route('records.detail_transaction_history', ['transaction_id' => $item->id, 'dept' => 'utama']) }}">{{ $item->transaction_number }}</a>
                                            </div>
                                        </td>
                                        <td style="">
                                            <span class="item-amount d-none d-sm-block text-sm ">
                                                {{ $item->quantity }}
                                            </span>
                                        </td>
                                        <td style="">
                                            <span class="item-amount d-none d-sm-block text-sm ">
                                                {{ $item->method_name }}
                                            </span>
                                        </td>
                                        <td style="">
                                            <span class="item-amount d-none d-sm-block text-sm ">
                                                {{ $item->type_name }}
                                            </span>
                                        </td>
                                        <td style="">
                                            <span class="item-amount d-none d-sm-block text-sm ">
                                                <?php
                                                echo $item->customer == null ? "Umum" : $item->customer;
                                                ?>
                                            </span>
                                        </td>
                                        <td style="">
                                            <span class="item-amount d-none d-sm-block text-sm ">
                                                {{ \Carbon\Carbon::parse($item->created_at)->format('Y-m-d') }}
                                            </span>
                                        </td>
                                        <td style="">
                                            <span class="item-amount d-none d-sm-block text-sm ">
                                                {{ $item->transaction_time }}
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