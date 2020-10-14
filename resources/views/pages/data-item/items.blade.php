@extends('layouts.master')

@section('page-title', 'Data Item')
@section('page-description', 'Berisi daftar item dan akses untuk melakukan penambahan item.')

@section('btn-custom')
<div>
    <a href="{{ route('items.create') }}" class="btn btn-sm text-muted">
        <span class="d-none d-sm-inline mx-1">Add item</span>
        <i data-feather="plus-circle"></i>
    </a>
    <button class="btn btn-sm text-muted" data-toggle="modal" data-target="#filter" data-toggle-class="modal-open-aside">
        <span class="d-none d-sm-inline mx-1">Filter</span>
        <i data-feather="sliders"></i>
    </button>

    <!-- modal aside right -->
    <div id="filter" class="modal fade modal-open-aside" da
    ta-backdrop="true" data-class="modal-open-aside" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-right w-xl">
            <div class="modal-content h-100 no-radius">
                <div class="modal-header ">
                    <div class="modal-title text-sm">Filter Item</div>
                    <button class="close" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <div class="p-2">
                        <form action="{{ route('items.filter-item') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="dept">Dept</label>
                                <select name="dept" id="dept" class="form-control">
                                    <option value="all">All</option>
                                    <option value="utama">Utama</option>
                                    <option value="gudang">Gudang</option>
                                </select>
                            </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary">Run</button>
                    </form>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>
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
                                            <div class="th-inner">Dept</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th style="" data-field="itemtype">
                                            <div class="th-inner "><span class="d-none d-sm-block">Jenis</span></div>
                                        </th>
                                        <th style="" data-field="5">
                                            <div class="th-inner "></div>
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
                                                @if ($item->dept == 'utama')
                                                <span class="badge badge-primary text-uppercase">{{ strtoupper($item->dept) }}</span>
                                                @else
                                                <span class="badge badge-secondary text-uppercase">{{ strtoupper($item->dept) }}</span>
                                                @endif
                                            </span>
                                        </td>
                                        <td style="">
                                            <span class="item-amount d-none d-sm-block text-sm ">
                                                {{ $item->category }}
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
                                                    <a class="dropdown-item" href="{{ route('items.show', ['item' => $item->id]) }}">
                                                        See detail
                                                    </a>
                                                    <a href="{{ route('items.edit', ['item' => $item->id]) }}" class="dropdown-item edit">
                                                        Edit
                                                    </a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item trash">
                                                        Delete item
                                                    </a>
                                                </div>
                                            </div>
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