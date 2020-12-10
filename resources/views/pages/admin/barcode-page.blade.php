@extends('layouts.master')

@section('page-title', 'Barcode Generator')
@section('page-description', 'Generate barcode for your products.')


@section('custom-js')
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('js/axios.js') }}"></script>
<script src="{{ asset('js/barcode.js') }}"></script>
<script>
    $(document).ready(function () {
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

    /* #barcode_canvas img {
        height: 55px;
        width: 100%;
    } */

</style>
@endsection

@section('content')
<div class="page-content page-container" id="page-content">
    <div class="padding">

        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        Barcode Preview
                        <button id="print" class="btn btn-sm btn-primary rounded-pill pr-3 pl-3" style=" position: absolute; right: 8px;"><i data-feather="printer"></i></button>
                    </div>
                    <div class="card-body" style="height: 300px; overflow: auto">
                        <div class="row" id="barcode_canvas" ></div>
                        <div class="h4 text-muted text-center" style="margin-top: 100px">No item selected.</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        Configure
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Item</label>
                            <div class="row no-gutters">
                                <div class="col-8">
                                    <input id="item_name" type="text" class="form-control" style="border-radius: 4px 0 0 4px!important" placeholder="select item" readonly>
                                    <input id="item_id" type="hidden" class="form-control">
                                    </div>
                                <div class="col-2"><button class="btn btn-primary"
                                        style="border-radius: 0 4px 4px 0!important" data-toggle="modal"
                                        data-target="#pilih-item">Select</button></div>
                            </div>

                            <!-- Modal pilih item -->
                            <div class="modal fade" id="pilih-item" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Select Item</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <table id="data-item"
                                                class="table my-responsive table-theme v-middle table-hover"
                                                style="margin-top: 0px;">
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
                                                        <th></th>
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
                                                        <td>
                                                            <button onclick="select_item('{{ $item->id }}', '{{ $item->name }}')" class="btn btn-sm btn-primary rounded-pill"><i data-feather='check'></i></button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal pilih item -->
                        </div>

                        <div class="form-group">
                            <label for="size">Size</label>
                            <select id="size" class="form-control" id="size">
                                <option value="large">Rol - 106x28mm</option>
                                <option value="medium">Rol - 106x28mm</option>
                                <option value="small">Rol - 33x15mm</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="copy">Copy</label>
                            <input id="copy" type="number" class="form-control" min="1" value="10">
                        </div>

                        <div class="form-group">
                            <button onclick="generate()" id="generate" class="btn btn-sm btn-primary">Generate</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="clearfix"></div>
    </div>
</div>
@endsection
