@extends('layouts.master')

@section('page-title', 'History Pesanan Pembelian')
@section('page-description', 'History pesanan pembelian.')


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
                        <th><span class="text-muted">Supplier</span></th>
                        <th><span class="text-muted">Tgl Pemesanan</span></th>
                        <th><span class="text-muted">Tgl Kirim</span></th>
                        <th><span class="text-muted">Jumlah pesanan</span></th>
                        <th><span class="text-muted">Jumlah Diterima</span></th>
                        <th><span class="text-muted">Harga</span></th>
                        <th><span class="text-muted">Total</span></th>
                        <th><span class="text-muted">Keterangan</span></th>
                        <th><span class="text-muted">Status</span></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $key => $order)
                        <tr class=" " data-id="1">
                            <td>
                                <div class="text-muted text-sm">
                                    <button class="btn btn-sm btn-light rounded-pill p-2 m-2" data-toggle="modal" data-target="#item{{$key}}"><i data-feather='search'></i></button>
                                </div>
                            </td>
                            <td>
                                <span class="item-amount d-sm-block text-sm">
                                    <button class="btn btn-sm btn-light rounded-pill p-2 m-2" data-toggle="modal" data-target="#sup-home{{$key}}"><i data-feather='search'></i></button>
                                </span>                                
                            </td>
                            <td>
                                <span class="item-amount d-sm-block text-sm">
                                    {{ $order->created_at }}
                                </span>
                            </td>
                            <td>
                                <span class="item-amount d-sm-block text-sm">
                                    {{ $order->shipping_date }}
                                </span>
                            </td>
                            <td>
                                <span class="item-amount d-sm-block text-sm">
                                    {{ $order->amount }}
                                </span>
                            </td>
                            <td>
                                <span class="item-amount d-sm-block text-sm">
                                    <div class="btn-group">
                                        <span class="text-success">{{ $order->accepted }}</span>
                                    </div>
                                </span>
                            </td>
                            <td>
                                <span class="item-amount d-sm-block text-sm">
                                    Rp.{{ number_format($order->main_cost, 2) }}
                                </span>
                            </td>
                            <td>
                                <span class="item-amount d-sm-block text-sm ">
                                    Rp.{{ number_format($order->main_cost * $order->amount, 2) }}
                                </span>
                            </td>
                            <td>
                                <span class="item-amount d-sm-block text-sm">
                                    {{ $order->description }}
                                </span>
                            </td>
                            <td>
                                <span class="item-amount d-sm-block text-sm">
                                    @if($order->status == 0)
                                    <span class="badge badge-warning text-uppercase">Ongoing</span>
                                    @else
                                    <span class="badge badge-success text-uppercase">Completed</span>
                                    @endif
                                </span>
                            </td>
                            <td>
                                <div class="item-action dropdown">
                                    <a href="#" data-toggle="dropdown" class="text-muted">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-more-vertical">
                                            <circle cx="12" cy="12" r="1"></circle>
                                            <circle cx="12" cy="5" r="1"></circle>
                                            <circle cx="12" cy="19" r="1"></circle>
                                        </svg>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right bg-black" role="menu">
                                        <a class="dropdown-item" data-toggle="modal" data-target="#return{{$key}}">
                                            Return Item
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal info supplier -->
                        <div class="modal fade" id="sup-home{{$key}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Info Supplier</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-2">
                                        <i data-feather='user' class="mr-3"></i>
                                        {{ $order->supplier_name }}
                                    </div>

                                    <div class="mb-2">
                                        <i data-feather='home' class="mr-3"></i>
                                        {{ $order->address }}
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-sm rounded-pill btn-outline-secondary pr-4 pl-4" data-dismiss="modal">Close</button>
                                </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Detail item -->
                        <div class="modal fade" id="item{{$key}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Detail item</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-2">
                                        Nama :  <h5>{{ $order->name }}</h5>
                                    </div>

                                    <div class="mb-2">
                                        Satuan :  <h5>{{ $order->unit }}</h5>
                                    </div>

                                    <div class="mb-2">
                                        Dept :  <h5>{{ strtoupper($order->dept) }}</h5>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-sm rounded-pill btn-outline-secondary pr-4 pl-4" data-dismiss="modal">Close</button>
                                </div>
                                </div>
                            </div>
                        </div>


                        <!-- Modal return item -->
                        <div class="modal fade" id="return{{$key}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Return Barang</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('orders.return_item', ['order' => $order->id]) }}" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <label for="amount">Jumlah Item <br> <span class="text-danger"><small style="font-size: 80%">Jumlah return barang tidak boleh melebihi jumlah barang.</small></span></label>
                                            <input type="text" name="amount" class="form-control" required>
                                            <div class="mt-3 mb-1 text-sm">Jumlah Barang : <span class="text-success">{{ $order->amount }}</span> item</div>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-sm rounded-pill btn-outline-secondary pr-4 pl-4">Close</button>
                                    <button type="submit" class="btn btn-sm rounded-pill btn-outline-primary pr-4 pl-4">Save</button>
                                    </form>
                                </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                </tbody>
            </table>
        </div>        
        <!-- <div class="clearfix"></div> -->
    </div>
</div>
@endsection