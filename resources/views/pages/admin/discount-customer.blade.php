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
                                <th>Action</th>
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
                                    <td><small class="text-danger">Off</small></td>
                                    @endif
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary rounded-pill pr-4 pl-4" data-toggle="modal" data-target="#editdiscount{{$key}}">Edit</button>
                                        <button class="btn btn-sm btn-outline-danger rounded-pill pr-4 pl-4" data-toggle="modal" data-target="#deletediscount{{$key}}">Delete</button>
                                    </td>
                                </tr>

                                <!-- modal delete discount -->
                                <div class="modal fade" id="deletediscount{{$key}}" tabindex="-1" role="dialog"
                                    aria-labelledby="paymentLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="paymentLabel">Perbarui Discount</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                           <div class="modal-body">
                                               <form method="post" action="{{ route('discounts.delete_discount_customer', ['discount_customer_id' => $discount->discount_customer_id]) }}">
                                                   @method('DELETE')
                                                   @csrf
                                                   Anda yakin untuk menghapus diskon pelanggan?
                                               
                                           </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-sm rounded-pill pr-4 pl-4 btn-outline-secondary" data-dismiss="modal">Batal</button>
                                                <input type="submit" class="btn btn-sm rounded-pill pr-4 pl-4 btn-outline-primary" value="Ya">
                                            </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- modal edit discount -->
                                <div class="modal fade" id="editdiscount{{$key}}" tabindex="-1" role="dialog"
                                    aria-labelledby="paymentLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="paymentLabel">Perbarui Discount</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                           <div class="modal-body">
                                               <form method="post" action="{{ route('discounts.update_discount_customer', ['discount_customer_id' => $discount->discount_customer_id]) }}">
                                                   @method('PUT')
                                                   @csrf
                                               <div class="form-group">
                                                   <label>Promo name</label>
                                                   <input name="promo_name" type="text"
                                                       class="form-control" value="{{ $discount->promo_name }}">
                                               </div>

                                               <div class="form-group">
                                                   <label>Customer</label>
                                                   <select name="stake_holder_id" class="form-control">
                                                    @foreach($customers as $key => $customer) 
                                                        @if($customer->id == $discount->id)  
                                                        <option selected value="{{ $customer->id }}">{{ $customer->name }}</option>
                                                        @else
                                                        <option value="{{ $customer->id }}">{{ $customer->name }}
                                                        </option>
                                                        @endif
                                                    @endforeach
                                                   </select>
                                               </div>

                                               <div class="form-group">
                                                   <label>Value</label>
                                                   <input name="value" type="number" min="0" class="form-control"
                                                       value="{{ $discount->value }}">
                                               </div>

                                               <div class="form-group">
                                                   <label>Status</label>
                                                   <select name="status" class="form-control" id="">
                                                       @if($discount->status == 1)
                                                       <option selected value="1">Active</option>
                                                       <option value="0">Off</option>
                                                       @else
                                                       <option value="1">Active</option>
                                                       <option selected value="0">Off</option>
                                                       @endif
                                                   </select>
                                               </div>
                                           </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-sm rounded-pill pr-4 pl-4 btn-outline-secondary" data-dismiss="modal">Batal</button>
                                                <input type="submit" class="btn btn-sm rounded-pill pr-4 pl-4 btn-outline-primary" value="Perbarui">
                                            </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                                                    @if(!$customer->value)
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
                                                            <button
                                                                onclick="get_customer('{{ $customer->name }}', '{{ $customer->id }}')"
                                                                class="btn btn-sm btn-primary rounded-pill">Select</button>
                                                        </td>
                                                    </tr>
                                                    @endif
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
                            <input id="value" type="number" min="0" class="form-control">
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
