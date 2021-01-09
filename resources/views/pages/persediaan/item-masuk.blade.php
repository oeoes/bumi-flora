@extends('layouts.master')

@section('page-title', 'Item Masuk')
@section('page-description', 'Daftar item masuk.')


@section('custom-js')
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#item-masuk').DataTable();
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
                        <div class="fixed-table-header" style="display: none;">
                            <table></table>
                        </div>
                        <div class="fixed-table-body">
                            <table id="item-masuk" class="table my-responsive table-theme v-middle table-hover" style="margin-top: 0px;">
                                <thead style="">
                                    <tr>
                                        <th><span class="text-muted">Nama</span></th>
                                        <th><span class="text-muted">Satuan</span></th>
                                        <th><span class="text-muted">No. Transaksi</span></th>
                                        <th><span class="text-muted">Tanggal</span></th>
                                        <th><span class="text-muted">Deskripsi</span></th>
                                        <th><span class="text-muted">Ke Peny.</span></th>
                                        <th><span class="text-muted">Harga</span></th>
                                        <th><span class="text-muted">Jumlah</span></th>
                                        <th><span class="text-muted">Total</span></th>
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
                                                {{ $item->transaction_no }}
                                            </span>
                                        </td>
                                        <td style="">
                                            <span class="item-amount d-sm-block text-sm">
                                                {{ $item->created_at }}
                                            </span>
                                        </td>
                                        <td style="">
                                            <span class="item-amount d-sm-block text-sm">
                                                {{ $item->description }}
                                            </span>
                                        </td>
                                        <td style="">
                                            <span class="item-amount d-sm-block text-sm">
                                                @if ($item->dept == 'utama')
                                                <span class="badge badge-primary text-uppercase">{{ strtoupper($item->dept) }}</span>
                                                @elseif($item->dept == 'gudang')
                                                <span class="badge badge-secondary text-uppercase">{{ strtoupper($item->dept) }}</span>
                                                @else
                                                <span class="badge badge-success text-uppercase">{{ strtoupper($item->dept) }}</span>
                                                @endif
                                            </span>
                                        </td>
                                        <td style="">
                                            <span class="item-amount d-sm-block text-sm">
                                                Rp.{{ number_format($item->price, 2) }}
                                            </span>
                                        </td>
                                        <td style="">
                                            <span class="item-amount d-sm-block text-sm">
                                                {{ $item->amount_in }}
                                            </span>
                                        </td>
                                        <td style="">
                                            <span class="item-amount d-sm-block text-sm">
                                                Rp.{{ number_format($item->price * $item->amount_in, 2) }}
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