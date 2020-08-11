@extends('layouts.master')

@section('page-title', 'Stock Opname')
@section('page-description', 'Stock opname.')


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
                                        <th style="" data-field="itemtype">
                                            <div class="th-inner">Satuan</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th style="" data-field="itemtype">
                                            <div class="th-inner">Dept</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th style="" data-field="itemtype">
                                            <div class="th-inner">Harga Jual</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th style="" data-field="itemtype">
                                            <div class="th-inner "><span class="d-sm-block">Jumlah</span></div>
                                            <!-- <div class="fht-cell"></div> -->
                                        </th>
                                        <th style="" data-field="task">
                                            <div class="th-inner "><span class="d-sm-block">Total</span></div>
                                            <!-- <div class="fht-cell"></div> -->
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
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