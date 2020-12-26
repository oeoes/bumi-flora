@extends('layouts.master')

@section('page-title', 'Daftar Pesanan Pembelian')
@section('page-description', 'Daftar pesanan pembelian.')


@section('custom-js')
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#order').DataTable();
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
        <div class="table-responsive">
            <table id="order" class="table table-theme table-row v-middle">
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
                                    <button type="button" class="btn btn-sm btn-outline-primary">{{ $order->accepted }}</button>
                                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#add-amount{{$key}}"><i data-feather='plus'></i></button>
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
                                        Nama : <h5>{{ $order->name }}</h5>
                                    </div>

                                    <div class="mb-2">
                                        Satuan : <h5>{{ $order->unit }}</h5>
                                    </div>

                                    <div class="mb-2">
                                        Dept : <h5>{{ strtoupper($order->dept) }}</h5>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-sm rounded-pill btn-outline-secondary pill pr-4 pl-4" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- modal tambah barang yang telah diterima -->
                    <div class="modal fade" id="add-amount{{$key}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Catat Barang Masuk</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('orders.accept_item', ['order' => $order->id]) }}" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <label for="amount">Jumlah Item</label>
                                            <input type="number" name="amount" class="form-control" min="1" max="{{ $order->amount - $order->accepted }}" required>
                                            <div class="mt-3 mb-1 text-sm">Jumlah pesanan : <span class="text-info">{{ $order->amount }}</span> item</div>
                                            <div class="mt-1 mb-1 text-sm">Jumlah item masuk : <span class="text-success">{{ $order->accepted }}</span> item</div>
                                            <div class="mt-1 mb-1 text-sm">Sisa : <span class="text-danger">{{ $order->amount - $order->accepted }}</span></div>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-sm rounded-pill btn-outline-secondary pill pr-4 pl-4" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-sm rounded-pill btn-outline-primary pill pr-4 pl-4">Save</button>
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