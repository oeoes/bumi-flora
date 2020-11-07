@extends('layouts.master')

@section('page-title', 'Discount Customer')
@section('page-description', 'Manage discount for customers.')


@section('custom-js')
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('js/axios.js') }}"></script>
<script src="{{ asset('js/discount-customer.js') }}"></script>
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

    #barcode_canvas img {
        height: 55px;
        width: 100%;
    }

</style>
@endsection

@section('content')
<div class="page-content page-container" id="page-content">
    <div class="padding">

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Discount List

                    </div>
                    <div class="card-body">
                        <table class="table table-theme v-middle table-hover">
                            <thead>
                                <th>Customer</th>
                                <th>Promo Name</th>
                                <th>Discount</th>
                                <th>Status</th>
                            </thead>
                            <tbody>
                                @foreach($discounts as $key => $discount)
                                <tr>
                                    <td>{{ $discount->name }}</td>
                                    <td>{{ $discount->promo_name }}</td>
                                    <td>{{ $discount->value }}%</td>
                                    @if($discount->status == 1)
                                    <td><small class="text-success">Active</small></td>
                                    @else
                                    <td><small class="text-secondary">Off</small></td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Create Discount
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Promo name</label>
                            <input id="promo_name" type="text" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Customer</label>
                            <div class="row no-gutters">
                                <div class="col-10">
                                    <input id="customer_name" type="text" class="form-control"
                                        style="border-radius: 4px 0 0 4px!important" placeholder="select customer"
                                        readonly>
                                    <input id="customer_id" type="hidden" class="form-control">
                                </div>
                                <div class="col-2"><button class="btn btn-primary"
                                        style="border-radius: 0 4px 4px 0!important" data-toggle="modal"
                                        data-target="#pilih-customer">Browse</button></div>
                            </div>

                            <!-- Modal pilih customer -->
                            <div class="modal fade" id="pilih-customer" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Select Customer</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <table id="data-item"
                                                class="table my-responsive table-theme v-middle table-hover"
                                                style="margin-top: 0px;">
                                                <thead style="">
                                                    <tr>
                                                        <th data-field="type">
                                                            <div class="th-inner">Nama</div>
                                                            <div class="fht-cell"></div>
                                                        </th>
                                                        <th data-field="itemtype">
                                                            <div class="th-inner">Alamat</div>
                                                            <div class="fht-cell"></div>
                                                        </th>
                                                        <th data-field="itemtype">
                                                            <div class="th-inner">HP</div>
                                                            <div class="fht-cell"></div>
                                                        </th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($customers as $key => $customer)
                                                        <tr>
                                                            <td>
                                                                <div class="text-muted text-sm">
                                                                    {{ $customer->name }}
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="text-muted text-sm">
                                                                    {{ $customer->address }}
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="text-muted text-sm">
                                                                    {{ $customer->phone }}
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <button onclick="get_customer('{{ $customer->name }}', '{{ $customer->id }}')" class="btn btn-sm btn-primary rounded-pill">Select</button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end modal pilih cestumer -->
                        </div>

                        <div class="form-group">
                            <label>Value</label>
                            <input id="value" type="number" min="0" class="form-control" >
                        </div>

                        <button id="create-discount" class="btn btn-sm btn-primary rounded-pill">Create</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="clearfix"></div>
    </div>
</div>
@endsection
