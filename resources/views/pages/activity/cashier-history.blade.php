@extends('layouts.master')

@section('page-title', 'Cashier History')
@section('page-description', 'Histori transaksi harian.')

@section('btn-custom')
<div>
    <!-- <a href="{{ route('cashier.print_history') }}" class="btn btn-sm text-muted">
        <span class="d-none d-sm-inline mx-1">Cetak Laporan</span>
        <i data-feather="printer"></i>
    </a> -->
    <button class="btn btn-sm text-muted" data-toggle="modal" data-target="#cetak-laporan">
        <span class="d-none d-sm-inline mx-1">Cetak Laporan</span>
        <i data-feather="printer"></i>
    </button>
</div>

<!-- sales modal delete -->
<div id="cetak-laporan" class="modal fade" data-backdrop="true" aria-hidden="true" style="display: none;">
    <div class="modal-dialog ">
        <div class="modal-content ">
            <div class="modal-header ">
                <div class="modal-title text-md">Preview Transaction History</div>
                <button class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <div class="row no-gutters">
                    <div class="col-6">
                        <div>LAPORAN PENJUALAN</div>
                        <hr style="width: 100%; border-style: dashed; border-color: black">
                        <div>PERIODE</div>
                        <div>USER</div>
                        <div>STORAGE</div>
                        <hr style="width: 100%; border-style: dashed; border-color: black">
                        <div>JML TRANSAKSI</div>
                        <div>TOT. POTONGAN</div>
                        <div>TOT. PAJAK</div>
                        <div>TOT. BIAYA</div>
                        <div>TOTAL</div>
                        <div>BAYAR TUNAI</div>
                        <div>BAYAR DEBIT</div>
                        <div>BAYAR TRANSFER</div>
                        <div>BAYAR E-WALLET</div>
                        <div>BAYAR KREDIT</div>
                        <hr style="width: 100%; border-style: dashed; border-color: black">
                        <div>JAM CETAK</div>
                    </div>
                    <div class="col-1 text-center">
                        <br>
                        <hr style="width: 100%; border-style: dashed; border-color: black">
                        <div>:</div>
                        <div>:</div>
                        <div>:</div>
                        <hr style="width: 100%; border-style: dashed; border-color: black">
                        <div>:</div>
                        <div>:</div>
                        <div>:</div>
                        <div>:</div>
                        <div>:</div>
                        <div>:</div>
                        <div>:</div>
                        <div>:</div>
                        <div>:</div>
                        <div>:</div>
                        <hr style="width: 100%; border-style: dashed; border-color: black">
                        <div>:</div>
                    </div>
                    <div class="col-5">
                        <br>
                        <hr style="width: 100%; border-style: dashed; border-color: black">
                        <div>{{\Carbon\Carbon::now()->format('Y-m-d') }} s/d {{ \Carbon\Carbon::now()->addDay()->format('Y-m-d') }}</div>
                        <div>{{ auth()->user()->name }}</div>
                        <div>UTAMA</div>
                        <hr style="width: 100%; border-style: dashed; border-color: black">
                        <!-- [trans, pot, pajak, biaya, total, tunai, debit, transfer, ewallet, kredit] -->
                        <div>{{ $data[0] }}</div>
                        <div>Rp. {{ number_format($data[1]) }}</div>
                        <div>Rp. {{ number_format($data[2]) }}</div>
                        <div>Rp. {{ number_format($data[3]) }}</div>
                        <div>Rp. {{ number_format(($data[4] + $data[2] + $data[3]) - $data[1]) }}</div>
                        <div>Rp. {{ number_format(($data[5] + $data[2] + $data[3]) - $data[1]) }}</div>
                        <div>Rp. {{ number_format($data[6]) }}</div>
                        <div>Rp. {{ number_format($data[7]) }}</div>
                        <div>Rp. {{ number_format($data[8]) }}</div>
                        <div>Rp. {{ number_format($data[9]) }}</div>
                        <hr style="width: 100%; border-style: dashed; border-color: black">
                        <div>{{ \Carbon\Carbon::now()->format('Y-m-d H:s:i') }}</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill pr-4 pl-4" data-dismiss="modal">Close</button>
                <a href="{{ route('cashier.print_history') }}" class="btn btn-sm btn-outline-success rounded-pill pr-4 pl-4">Print</a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>
@endsection

@section('custom-js')
<script src="{{ asset('js/dataTables.js') }}"></script>
<script>
    $(document).ready(function() {
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
                            <table id="data-item" class="table my-responsive table-theme v-middle table-hover" style="margin-top: 0px;">
                                <thead>
                                    <tr>
                                        <th data-field="type">
                                            <div class="th-inner">No. Transaksi</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th data-field="type">
                                            <div class="th-inner">Nama</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th data-field="itemtype">
                                            <div class="th-inner">Satuan</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th data-field="itemtype">
                                            <div class="th-inner">Harga Jual</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th data-field="itemtype">
                                            <div class="th-inner">Jenis</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th data-field="itemtype">
                                            <div class="th-inner">Quantity</div>
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
                                            <div class="th-inner">Date</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th data-field="itemtype">
                                            <div class="th-inner">Time</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $key => $item)
                                    <tr class=" " data-index="0" data-id="17">
                                        <td>
                                            <div class="text-muted text-sm">
                                                {{ $item->transaction_number }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-muted text-sm">
                                                {{ $item->name }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="item-amount d-sm-block text-sm">
                                                {{ strtoupper($item->unit) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="item-amount d-sm-block text-sm">
                                                Rp.{{ number_format($item->price, 2) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="item-amount d-none d-sm-block text-sm ">
                                                {{ strtoupper($item->category) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="item-amount d-none d-sm-block text-sm ">
                                                {{ $item->qty }}
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
                                                {{ \Carbon\Carbon::parse($item->created_at)->format('Y-m-d') }}
                                            </span>
                                        </td>
                                        <td>
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