@extends('layouts.master')

@section('page-title', 'Penyimpanan Gudang')
@section('page-description', 'Daftar item pada penyimpanan gudang.')

@section('btn-custom')
<div>
    <a href="{{ route('items.export-item', ['dept' => 'gudang']) }}" class="btn btn-sm text-muted">
        <span class="d-none d-sm-inline mx-1">Export</span>
        <i data-feather="upload-cloud"></i>
    </a>

    <button class="btn btn-sm text-muted" data-toggle="modal" data-target="#filter" data-toggle-class="modal-open-aside">
        <span class="d-none d-sm-inline mx-1">Filter</span>
        <i data-feather="sliders"></i>
    </button>

    <!-- modal aside filter -->
    <div id="filter" class="modal fade modal-open-aside" data-backdrop="true" data-class="modal-open-aside" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-right w-xl">
            <div class="modal-content h-100 no-radius">
                <div class="modal-header ">
                    <div class="modal-title text-sm">Filter Item</div>
                    <button class="close" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <div class="p-2">
                        <form action="{{ route('storages.gudang') }}" method="get">
                            <div class="form-group">
                                <label for="name">Item name</label>
                                <input class="form-control" type="text" class="form-contro" name="filter[items.name]">
                            </div>
                            <div class="form-group">
                                <label for="name">Barcode</label>
                                <input class="form-control" type="text" class="form-contro" name="filter[items.barcode]">
                            </div>
                            <div class="form-group">
                                <label for="categories">Category</label>
                                <select name="filter[categories.id]" id="" class="form-control">
                                    <option value="">Pilih jenis item</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ strtoupper($category->category) }}</option>
                                    @endforeach
                                </select>
                            </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <input type="submit" class="btn btn-sm btn-primary">
                    </div>
                    </form>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>
</div>
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
            <div class="col-md-12">
                <div class="table-responsive">
                    <table id="item-gudang" class="table my-responsive table-theme v-middle table-hover" style="margin-top: 0px;">
                        <thead style="">
                            <tr class="bg-light">
                                <th><span class="text-muted">No.</span></th>
                                <th><span class="text-muted">Nama</span></th>
                                <th><span class="text-muted">Barcode</span></th>
                                <th><span class="text-muted">Jenis</span></th>
                                <th><span class="text-muted">Satuan</span></th>
                                <th><span class="text-muted">Min. Stock</span></th>
                                <th><span class="text-muted">Stock</span></th>
                                @if(auth()->user()->hasAnyRole(['root', 'super_admin']))
                                <th><span class="text-muted">Harga Pokok</span></th>
                                @endif
                                <th><span class="text-muted">Harga Jual</span></th>
                                <th><span class="text-muted">Saldo Awal</span></th>
                                <th><span class="text-muted">Total</span></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $key => $item)
                            <tr class=" " data-index="0" data-id="17">
                                <td style="">
                                    <div class="text-muted text-sm">
                                        {{ $key+1 }}
                                    </div>
                                </td>
                                <td style="">
                                    <div class="text-muted text-sm">
                                        {{ $item->name }}
                                    </div>
                                </td>
                                <td style="">
                                    <div class="text-muted text-sm">
                                        {{ $item->barcode }}
                                    </div>
                                </td>
                                <td style="">
                                    <span class="item-amount d-sm-block text-sm">
                                        {{ $item->category }}
                                    </span>
                                </td>
                                <td style="">
                                    <span class="item-amount d-sm-block text-sm">
                                        {{ $item->unit }}
                                    </span>
                                </td>
                                <td style="">
                                    <span class="item-amount d-sm-block text-sm">
                                        {{ $item->min_stock }}
                                    </span>
                                </td>
                                <td style="">
                                    <span class="item-amount d-sm-block text-sm">
                                        {{ $item->stock }}
                                    </span>
                                </td>
                                @if(auth()->user()->hasAnyRole(['root', 'super_admin']))
                                <td style="">
                                    <span class="item-amount d-sm-block text-sm">
                                        Rp.{{ number_format($item->main_cost, 2) }}
                                    </span>
                                </td>
                                @endif
                                <td style="">
                                    <span class="item-amount d-sm-block text-sm">
                                        Rp.{{ number_format($item->price, 2) }}
                                    </span>
                                </td>
                                <td style="">
                                    <span class="item-amount d-sm-block text-sm">
                                        {{ $item->amount }}
                                    </span>
                                </td>
                                <td style="">
                                    <span class="item-amount d-sm-block text-sm ">
                                        Rp.{{ number_format($item->price * $item->stock, 2) }}
                                    </span>
                                </td>
                                <td style="">
                                    <div class="item-action dropdown">
                                        <span style="cursor: pointer" class="nav-icon" data-toggle="modal" data-target="#options{{$key}}" data-toggle-class="modal-open-aside" data-toggle-class-target=".animate"><i data-feather='more-vertical'></i></span>
                                    </div>
                                </td>
                            </tr>

                            <!-- modal options -->
                            <div id="options{{$key}}" class="modal fade" data-backdrop="true" style="display: none;" aria-hidden="true">
                                <div class="modal-dialog modal-right w-xl" data-class="fade-down">
                                    <div class="modal-content h-100 no-radius">
                                        <div class="modal-header ">
                                            <div class="modal-title text-md">More options</div>
                                            <button class="close" data-dismiss="modal">×</button>
                                        </div>
                                        <div class="modal-body">
                                            <button class="btn btn-sm btn-primary mb-1" data-toggle="modal" data-target="#saldoawal{{$key}}" data-toggle-class="fade-down">Saldo
                                                Awal</button>
                                            <button class="btn btn-sm btn-primary mb-1" data-toggle="modal" data-target="#masuk{{$key}}" data-toggle-class="fade-down">Buat Laporan
                                                Item Masuk</button>
                                            <!-- <button class="btn btn-sm btn-primary mb-1" data-toggle="modal" data-target="#keluar{{$key}}" data-toggle-class="fade-down">Buat Laporan Item Keluar</button> -->
                                            <button class="btn btn-sm btn-primary mb-1" data-toggle="modal" data-target="#transfer{{$key}}" data-toggle-class="fade-down">Transfer
                                                Item</button>
                                            <a href="{{ route('orders.show', ['order' => $item->id]) }}" class="btn btn-sm btn-primary mb-1">Pesanan Pembelian</a>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                            </div>

                            <!-- modal saldo awal -->
                            <div id="saldoawal{{$key}}" class="modal fade" data-backdrop="true" style="display: none;" aria-hidden="true">
                                <div class="modal-dialog animate" data-class="fade-down">
                                    <div class="modal-content ">
                                        <div class="modal-header ">
                                            <div class="modal-title text-md">Masukan Saldo Awal</div>
                                            <button class="close" data-dismiss="modal">×</button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('storages.update', ['storage' => $item->balance_id]) }}" method="post">
                                                @method('PUT')
                                                @csrf
                                                <div class="form-group">
                                                    <label>Jumlah saldo</label>
                                                    <input type="number" name="amount" class="form-control" value="{{ $item->amount }}" min="0">
                                                </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                            </div>

                            <!-- modal item masuk -->
                            <div id="masuk{{$key}}" class="modal fade" data-backdrop="true" style="display: none;" aria-hidden="true">
                                <div class="modal-dialog animate" data-class="fade-up">
                                    <div class="modal-content ">
                                        <div class="modal-header ">
                                            <div class="modal-title text-md">Item Masuk</div>
                                            <button class="close" data-dismiss="modal">×</button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('records.store') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="item_id" value="{{ $item->id }}">
                                                <input type="hidden" name="type" value="in">
                                                <div class="form-group">
                                                    <label>Masuk Ke</label>
                                                    <input type="text" class="form-control" name="dept" readonly value="gudang">
                                                </div>
                                                <div class="form-group">
                                                    <label>Amount</label>
                                                    <input type="number" min="0" class="form-control" name="amount" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Deskripsi</label>
                                                    <textarea class="form-control" name="description" cols="3" rows="3"></textarea>
                                                </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-sm btn-primary">Publish</button>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                            </div>

                            <!-- modal item keluar -->
                            <div id="keluar{{$key}}" class="modal fade" data-backdrop="true" style="display: none;" aria-hidden="true">
                                <div class="modal-dialog animate" data-class="fade-right">
                                    <div class="modal-content ">
                                        <div class="modal-header ">
                                            <div class="modal-title text-md">Item Keluar</div>
                                            <button class="close" data-dismiss="modal">×</button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('records.store') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="item_id" value="{{ $item->id }}">
                                                <input type="hidden" name="type" value="out">
                                                <div class="form-group">
                                                    <label>Transaction Number</label>
                                                    <input type="text" class="form-control" name="transaction_no" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Keluar Dari</label>
                                                    <input type="text" class="form-control" name="dept" readonly value="gudang">
                                                </div>
                                                <div class="form-group">
                                                    <label>Amount</label>
                                                    <input type="number" min="0" class="form-control" name="amount" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Deskripsi</label>
                                                    <textarea class="form-control" name="description" cols="3" rows="3"></textarea>
                                                </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-sm btn-primary">Publish</button>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                            </div>

                            <!-- modal transfer item -->
                            <div id="transfer{{$key}}" class="modal fade" data-backdrop="true" style="display: none;" aria-hidden="true">
                                <div class="modal-dialog animate" data-class="fade-right">
                                    <div class="modal-content ">
                                        <div class="modal-header ">
                                            <div class="modal-title text-md">Transfer Item "{{ $item->name }}"</div>
                                            <button class="close" data-dismiss="modal">×</button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('records.transfer') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="item_id" value="{{ $item->id }}">
                                                <div class="form-group">
                                                    <label>Dari</label>
                                                    <input name="from" type="text" class="form-control" value="gudang" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label>Ke</label>
                                                    <select name="to" class="form-control">
                                                        <option value="utama">Penyimpanan Utama</option>
                                                        <option value="ecommerce">Penyimpanan E-Commerce</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Jumlah Transfer</label>
                                                    <input type="number" min="0" max="{{ $item->stock }}" class="form-control" name="amount" required>
                                                    <small class="text-muted"><span class="text-danger">Maksimum jumlah
                                                            yang akan ditransfer tidak bolah melebihi stock
                                                            tersedia.</span> <br> Stock: <span class="text-info">{{ $item->stock }}</span></small>
                                                </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-sm btn-primary">Publish</button>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                    {{$items}}
                </div>
            </div>


        </div>


        <div class="clearfix"></div>
    </div>
</div>
@endsection