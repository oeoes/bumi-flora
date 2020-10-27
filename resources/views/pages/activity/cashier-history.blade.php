@extends('layouts.master')

@section('page-title', 'Cashier History')
@section('page-description', 'Histori transaksi harian.')

@section('btn-custom')
<div>
    <button class="btn btn-sm text-muted" data-toggle="modal">
        <span class="d-none d-sm-inline mx-1">Cetak Laporan</span>
        <i data-feather="printer"></i>
    </button>
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
                                            <div class="th-inner">Payment Method</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th style="" data-field="itemtype">
                                            <div class="th-inner">Payment Type</div>
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
                                                {{ $item->category }}
                                            </span>
                                        </td>
                                        <td style="">
                                            <span class="item-amount d-none d-sm-block text-sm ">
                                                {{ $item->qty }}
                                            </span>
                                        </td>
                                        <td style="">
                                            <span class="item-amount d-none d-sm-block text-sm ">
                                                {{ $item->payment_method }}
                                            </span>
                                        </td>
                                        <td style="">
                                            <span class="item-amount d-none d-sm-block text-sm ">
                                                {{ $item->payment_type }}
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