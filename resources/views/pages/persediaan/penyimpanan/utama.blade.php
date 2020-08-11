@extends('layouts.master')

@section('page-title', 'Penyimpanan Utama')
@section('page-description', 'Daftar item pada penyimpanan utama.')


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
            <div class="col-sm-12 col-md-12">
                <div class="bootstrap-table">
                    <div class="fixed-table-container" style="padding-bottom: 0px;">
                        <div class="fixed-table-header" style="display: none;">
                            <table></table>
                        </div>
                        <div class="fixed-table-body">
                            <div class="fixed-table-loading" style="top: 41px;">Loading, please wait...</div>
                            <table id="table" class="table my-responsive table-theme v-middle table-hover" data-toolbar="#toolbar"
                                data-search="true" data-search-align="left" data-show-export="true" data-show-columns="true"
                                data-detail-view="false" data-mobile-responsive="true" data-pagination="true"
                                data-page-list="[10, 25, 50, 100, ALL]" style="margin-top: 0px;">
                                <thead style="">
                                    <tr>
                                        <th style="" data-field="type">
                                            <div class="th-inner">Nama</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th style="" data-field="code">
                                            <div class="th-inner">Kode</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th style="" data-field="code">
                                            <div class="th-inner">Jenis</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th style="" data-field="itemtype">
                                            <div class="th-inner">Satuan</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th style="" data-field="itemtype">
                                            <div class="th-inner">Min. Stock</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th style="" data-field="itemtype">
                                            <div class="th-inner">Stock</div>
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
                                            <div class="th-inner">Saldo Awal</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th style="" data-field="task">
                                            <div class="th-inner "><span class="d-sm-block">Total</span></div>
                                            <!-- <div class="fht-cell"></div> -->
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
                                            <div class="text-muted text-sm">
                                                {{ $item->code }}
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
                                            <span class="item-amount d-sm-block text-sm">
                                                {{ $item->amount }}
                                            </span>
                                        </td>
                                        <td style="">
                                            <span class="item-amount d-sm-block text-sm ">
                                                Rp.{{ number_format($item->price * $item->amount, 2) }}
                                            </span>
                                        </td>
                                        <td style="">
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
                                                    <a class="dropdown-item" data-toggle="modal" data-target="#saldoawal{{$key}}" data-toggle-class="fade-down" data-toggle-class-target=".animate">
                                                        Saldo Awal
                                                    </a>
                                                    <a class="dropdown-item edit" data-toggle="modal" data-target="#masuk{{$key}}" data-toggle-class="fade-up" data-toggle-class-target=".animate">
                                                        Buat Laporan Item Masuk
                                                    </a>
                                                    <a class="dropdown-item edit" data-toggle="modal" data-target="#keluar{{$key}}" data-toggle-class="fade-right" data-toggle-class-target=".animate">
                                                        Buat Laporan Item Keluar
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

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
                                                            <label>Transaction Number</label>
                                                            <input type="text" class="form-control" name="transaction_no" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Masuk Ke</label>
                                                            <input type="text" class="form-control" name="dept" readonly value="utama">
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
                                                            <input type="text" class="form-control" name="dept" readonly value="utama">
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