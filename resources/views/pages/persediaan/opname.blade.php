@extends('layouts.master')

@section('page-title', 'Opname')
@section('page-description', 'Opname.')


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
                        <th><span class="text-muted">Jenis</span></th>
                        <th><span class="text-muted">Dept</span></th>
                        <th><span class="text-muted">Rak</span></th>
                        <th><span class="text-muted">Keterangan</span></th>
                        <th><span class="text-muted">Awal</span></th>
                        <th><span class="text-muted">Masuk</span></th>
                        <th><span class="text-muted">Keluar</span></th>
                        <th><span class="text-muted">Buku</span></th>
                        <th><span class="text-muted">Fisik</span></th>
                        <th><span class="text-muted">Selisih</span></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class=" " data-id="1">
                        <td style="">
                            <div class="text-muted text-sm">
                                Samsung Note 20 Ultra
                            </div>
                        </td>
                        <td style="">
                            <div class="text-muted text-sm">
                                PCS
                            </div>
                        </td>
                        <td style="">
                            <span class="item-amount d-sm-block text-sm">
                                Barang
                            </span>
                        </td>
                        <td style="">
                            <span class="item-amount d-sm-block text-sm">
                                Utama
                            </span>
                        </td>
                        <td style="">
                            <span class="item-amount d-sm-block text-sm">
                                A3
                            </span>
                        </td>
                        <td style="">
                            <span class="item-amount d-sm-block text-sm">
                                Restock
                            </span>
                        </td>
                        <td style="">
                            <span class="item-amount d-sm-block text-sm">
                                100
                            </span>
                        </td>
                        <td style="">
                            <span class="item-amount d-sm-block text-sm">
                                345
                            </span>
                        </td>
                        <td style="">
                            <span class="item-amount d-sm-block text-sm">
                                150
                            </span>
                        </td>
                        <td style="">
                            <span class="item-amount d-sm-block text-sm ">
                                145
                            </span>
                        </td>
                        <td style="">
                            <span class="item-amount d-sm-block text-sm">
                                135
                            </span>
                        </td>
                        <td style="">
                            <span class="item-amount d-sm-block text-sm">
                                100
                            </span>
                        </td>
                    </tr>
                    <tr class=" " data-id="1">
                        <td style="">
                            <div class="text-muted text-sm">
                                Samsung Note 20 Ultra
                            </div>
                        </td>
                        <td style="">
                            <div class="text-muted text-sm">
                                PCS
                            </div>
                        </td>
                        <td style="">
                            <span class="item-amount d-sm-block text-sm">
                                Barang
                            </span>
                        </td>
                        <td style="">
                            <span class="item-amount d-sm-block text-sm">
                                Utama
                            </span>
                        </td>
                        <td style="">
                            <span class="item-amount d-sm-block text-sm">
                                A3
                            </span>
                        </td>
                        <td style="">
                            <span class="item-amount d-sm-block text-sm">
                                Restock
                            </span>
                        </td>
                        <td style="">
                            <span class="item-amount d-sm-block text-sm">
                                100
                            </span>
                        </td>
                        <td style="">
                            <span class="item-amount d-sm-block text-sm">
                                345
                            </span>
                        </td>
                        <td style="">
                            <span class="item-amount d-sm-block text-sm">
                                150
                            </span>
                        </td>
                        <td style="">
                            <span class="item-amount d-sm-block text-sm ">
                                145
                            </span>
                        </td>
                        <td style="">
                            <span class="item-amount d-sm-block text-sm">
                                135
                            </span>
                        </td>
                        <td style="">
                            <span class="item-amount d-sm-block text-sm">
                                100
                            </span>
                        </td>
                    </tr>
                    <tr class=" " data-id="1">
                        <td style="">
                            <div class="text-muted text-sm">
                                Samsung Note 20 Ultra
                            </div>
                        </td>
                        <td style="">
                            <div class="text-muted text-sm">
                                PCS
                            </div>
                        </td>
                        <td style="">
                            <span class="item-amount d-sm-block text-sm">
                                Barang
                            </span>
                        </td>
                        <td style="">
                            <span class="item-amount d-sm-block text-sm">
                                Utama
                            </span>
                        </td>
                        <td style="">
                            <span class="item-amount d-sm-block text-sm">
                                A3
                            </span>
                        </td>
                        <td style="">
                            <span class="item-amount d-sm-block text-sm">
                                Restock
                            </span>
                        </td>
                        <td style="">
                            <span class="item-amount d-sm-block text-sm">
                                100
                            </span>
                        </td>
                        <td style="">
                            <span class="item-amount d-sm-block text-sm">
                                345
                            </span>
                        </td>
                        <td style="">
                            <span class="item-amount d-sm-block text-sm">
                                150
                            </span>
                        </td>
                        <td style="">
                            <span class="item-amount d-sm-block text-sm ">
                                145
                            </span>
                        </td>
                        <td style="">
                            <span class="item-amount d-sm-block text-sm">
                                135
                            </span>
                        </td>
                        <td style="">
                            <span class="item-amount d-sm-block text-sm">
                                100
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>        
        <!-- <div class="clearfix"></div> -->
    </div>
</div>
@endsection