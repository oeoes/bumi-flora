@extends('layouts.master')

@section('page-title', 'Daftar Pesanan Pembelian')
@section('page-description', 'Daftar pesanan pembelian.')


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
        <div class="table-responsive">
            <table id="datatable" class="table table-theme table-row v-middle" data-plugin="dataTable">
                <thead>
                    <tr>
                        <th><span class="text-muted">Item</span></th>
                        <th><span class="text-muted">Satuan</span></th>
                        <th><span class="text-muted">Dept</span></th>
                        <th><span class="text-muted">Supplier</span></th>
                        <th><span class="text-muted">Alamat Supplier</span></th>
                        <th><span class="text-muted">Tgl Pemesanan</span></th>
                        <th><span class="text-muted">Tgl Kirim</span></th>
                        <th><span class="text-muted">Jumlah pesanan</span></th>
                        <th><span class="text-muted">Harga</span></th>
                        <th><span class="text-muted">Total</span></th>
                        <th><span class="text-muted">Keterangan</span></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $key => $order)
                        <tr class=" " data-id="1">
                            <td style="">
                                <div class="text-muted text-sm">
                                    {{ $order->name }}
                                </div>
                            </td>
                            <td style="">
                                <div class="text-muted text-sm">
                                    {{ $order->unit }}
                                </div>
                            </td>
                            <td style="">
                                <span class="item-amount d-sm-block text-sm">
                                    {{ $order->dept }}
                                </span>
                            </td>
                            <td style="">
                                <span class="item-amount d-sm-block text-sm">
                                    {{ $order->supplier_name }}
                                </span>
                            </td>
                            <td style="">
                                <span class="item-amount d-sm-block text-sm">
                                    {{ $order->address }}
                                </span>
                            </td>
                            <td style="">
                                <span class="item-amount d-sm-block text-sm">
                                    {{ $order->created_at }}
                                </span>
                            </td>
                            <td style="">
                                <span class="item-amount d-sm-block text-sm">
                                    {{ $order->shipping_date }}
                                </span>
                            </td>
                            <td style="">
                                <span class="item-amount d-sm-block text-sm">
                                    {{ $order->amount }}
                                </span>
                            </td>
                            <td style="">
                                <span class="item-amount d-sm-block text-sm">
                                    Rp.{{ number_format($order->price, 2) }}
                                </span>
                            </td>
                            <td style="">
                                <span class="item-amount d-sm-block text-sm ">
                                    Rp.{{ number_format($order->price * $order->amount, 2) }}
                                </span>
                            </td>
                            <td style="">
                                <span class="item-amount d-sm-block text-sm">
                                    {{ $order->description }}
                                </span>
                            </td>
                            <td>
                            </td>
                        </tr>
                        @endforeach
                </tbody>
            </table>
        </div>        
        <!-- <div class="clearfix"></div> -->
    </div>
</div>
@endsection