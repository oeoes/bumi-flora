@extends('layouts.master')

@section('page-title', 'Entity')
@section('page-description', 'Data entity berupa supplier, customer, dan sales.')


@section('custom-css')
<style>
    .badge {
        cursor: pointer!important
    }
</style>
@endsection


@section('content')
<div class="page-content page-container" id="page-content">
    <div class="padding">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="b-b">
                        <div class="nav-active-border b-primary bottom">
                            <ul class="nav" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="supplier-tab" data-toggle="tab" href="#supplier3" role="tab" aria-controls="supplier" aria-selected="true">Supplier</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="customer-tab" data-toggle="tab" href="#customer3" role="tab" aria-controls="customer" aria-selected="false">Customer</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="sales-tab" data-toggle="tab" href="#sales3" role="tab" aria-controls="sales" aria-selected="false">Sales</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="tab-content p-3">
                        <div class="tab-pane fade active show" id="supplier3" role="tabpanel" aria-labelledby="supplier-tab">
                            <div class="row">
                                <div class="col-md-4">
                                    <form method="post" action="{{ route('entities.store') }}">
                                        @csrf
                                        <div class="form-group">
                                            <label class="text-muted" for="name">Name</label>
                                            <input name="name" type="text" class="form-control" id="name" placeholder="insert name">
                                            <input type="hidden" name="type" value="supplier">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="address">Address</label>
                                            <input name="address" type="text" class="form-control" id="address" placeholder="insert address">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="country">Country</label>
                                            <input name="country" type="text" class="form-control" id="country" placeholder="insert country">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="province">Province</label>
                                            <input name="province" type="text" class="form-control" id="province" placeholder="insert province">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="city">City</label>
                                            <input name="city" type="text" class="form-control" id="city" placeholder="insert city">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="postal_code">Postal code</label>
                                            <input name="postal_code" type="text" class="form-control" id="postal_code" placeholder="insert postal_code">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="phone">Phone</label>
                                            <input name="phone" type="text" class="form-control" id="phone" placeholder="insert phone">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="email">Email</label>
                                            <input name="email" type="email" class="form-control" id="email" placeholder="insert email">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="card_number">Card Number</label>
                                            <input name="card_number" type="text" class="form-control" id="card_number" placeholder="insert card_number">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="owner">Owner</label>
                                            <input name="owner" type="text" class="form-control" id="owner" placeholder="insert owner">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="bank">Bank</label>
                                            <input name="bank" type="text" class="form-control" id="bank" placeholder="insert bank">
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-primary">Add</button>
                                    </form>
                                </div>
                                <div class="col-md-8">
                                    <table class="table table-responsive table-theme v-middle table-hover">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <div class="th-inner">No.</div>
                                                    <div class="fht-cell"></div>
                                                </th>
                                                <th>
                                                    <div class="th-inner">Name</div>
                                                    <div class="fht-cell"></div>
                                                </th>
                                                <th>
                                                    <div class="th-inner">Address</div>
                                                    <div class="fht-cell"></div>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($suppliers as $key => $supplier)
                                            <tr>
                                                <td>
                                                    <div class="text-muted text-sm">
                                                        {{ $key+1 }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-muted text-sm">
                                                        {{ $supplier->name }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-muted text-sm">
                                                        {{ $supplier->address }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-primary text-uppercase" data-toggle="modal" data-target="#editsup{{ $key }}">Edit</span>
                                                    <span class="badge badge-danger text-uppercase" data-toggle="modal" data-target="#deletesup{{ $key }}">Delete</span>
                                                </td>
                                            </tr>
                                            <!-- supplier modal edit -->
                                            <div id="editsup{{ $key }}" class="modal fade" data-backdrop="true" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog ">
                                                    <div class="modal-content ">
                                                        <div class="modal-header ">
                                                            <div class="modal-title text-md">Edit Supplier</div>
                                                            <button class="close" data-dismiss="modal">×</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="post" action="{{ route('entities.update', ['entity' => $supplier->id]) }}">
                                                                @method('PUT')
                                                                @csrf
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="name">Name</label>
                                                                    <input name="name" type="text" class="form-control" id="name" placeholder="insert name" value="{{ $supplier->name }}">
                                                                    <input type="hidden" name="type" value="supplier">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="address">Address</label>
                                                                    <input name="address" type="text" class="form-control" id="address" placeholder="insert address" value="{{ $supplier->address }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="country">Country</label>
                                                                    <input name="country" type="text" class="form-control" id="country" placeholder="insert country" value="{{ $supplier->country }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="province">Province</label>
                                                                    <input name="province" type="text" class="form-control" id="province" placeholder="insert province" value="{{ $supplier->province }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="city">City</label>
                                                                    <input name="city" type="text" class="form-control" id="city" placeholder="insert city" value="{{ $supplier->city }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="postal_code">Postal code</label>
                                                                    <input name="postal_code" type="text" class="form-control" id="postal_code" placeholder="insert postal_code" value="{{ $supplier->postal_code }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="phone">Phone</label>
                                                                    <input name="phone" type="text" class="form-control" id="phone" placeholder="insert phone" value="{{ $supplier->phone }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="email">Email</label>
                                                                    <input name="email" type="email" class="form-control" id="email" placeholder="insert email" value="{{ $supplier->email }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="card_number">Card Number</label>
                                                                    <input name="card_number" type="text" class="form-control" id="card_number" placeholder="insert card_number" value="{{ $supplier->card_number }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="owner">Owner</label>
                                                                    <input name="owner" type="text" class="form-control" id="owner" placeholder="insert owner" value="{{ $supplier->owner }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="bank">Bank</label>
                                                                    <input name="bank" type="text" class="form-control" id="bank" placeholder="insert bank" value="{{ $supplier->bank }}">
                                                                </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                            </div>

                                            <!-- supplier modal delete -->
                                            <div id="deletesup{{ $key }}" class="modal fade" data-backdrop="true" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog ">
                                                    <div class="modal-content ">
                                                        <div class="modal-header ">
                                                            <div class="modal-title text-md">Confirm Deletion</div>
                                                            <button class="close" data-dismiss="modal">×</button>
                                                        </div>
                                                        <div class="modal-body">
                                                        <form action="{{ route('entities.destroy', ['entity' => $supplier->id]) }}" method="post">
                                                            @method('DELETE')
                                                            @csrf
                                                            <div class="p-2">
                                                                <p>You're about to delete supplier data. Klick "Yes" to proceed.</p>
                                                                <p class="text-danger"><b>Note:</b> Do not delete brand that has a relation to item data.</p>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill pr-4 pl-4" data-dismiss="modal">Cancle</button>
                                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill pr-4 pl-4">Yes</button>
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
                            </div>
                        </div>
                        <div class="tab-pane fade" id="customer3" role="tabpanel" aria-labelledby="customer-tab">
                            <div class="row">
                                <div class="col-md-4">
                                    <form method="post" action="{{ route('entities.store') }}">
                                        @csrf
                                        <div class="form-group">
                                            <label class="text-muted" for="name">Name</label>
                                            <input name="name" type="text" class="form-control" id="name" placeholder="insert name">
                                            <input type="hidden" name="type" value="customer">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="address">Address</label>
                                            <input name="address" type="text" class="form-control" id="address" placeholder="insert address">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="country">Country</label>
                                            <input name="country" type="text" class="form-control" id="country" placeholder="insert country">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="province">Province</label>
                                            <input name="province" type="text" class="form-control" id="province" placeholder="insert province">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="city">City</label>
                                            <input name="city" type="text" class="form-control" id="city" placeholder="insert city">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="postal_code">Postal code</label>
                                            <input name="postal_code" type="text" class="form-control" id="postal_code" placeholder="insert postal_code">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="phone">Phone</label>
                                            <input name="phone" type="text" class="form-control" id="phone" placeholder="insert phone">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="email">Email</label>
                                            <input name="email" type="email" class="form-control" id="email" placeholder="insert email">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="card_number">Card Number</label>
                                            <input name="card_number" type="text" class="form-control" id="card_number" placeholder="insert card_number">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="owner">Owner</label>
                                            <input name="owner" type="text" class="form-control" id="owner" placeholder="insert owner">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="bank">Bank</label>
                                            <input name="bank" type="text" class="form-control" id="bank" placeholder="insert bank">
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-primary">Add</button>
                                    </form>
                                </div>
                                <div class="col-md-8">
                                    <table class="table table-responsive table-theme v-middle table-hover">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <div class="th-inner">No.</div>
                                                    <div class="fht-cell"></div>
                                                </th>
                                                <th>
                                                    <div class="th-inner">Name</div>
                                                    <div class="fht-cell"></div>
                                                </th>
                                                <th>
                                                    <div class="th-inner">Address</div>
                                                    <div class="fht-cell"></div>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($customers as $key => $customer)
                                            <tr>
                                                <td>
                                                    <div class="text-muted text-sm">
                                                        {{ $key+1 }}
                                                    </div>
                                                </td>
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
                                                    <span class="badge badge-primary text-uppercase" data-toggle="modal" data-target="#editcust{{ $key }}">Edit</span>
                                                    <span class="badge badge-danger text-uppercase" data-toggle="modal" data-target="#deletecust{{ $key }}">Delete</span>
                                                </td>
                                            </tr>
                                            <!-- customer modal edit -->
                                            <div id="editcust{{ $key }}" class="modal fade" data-backdrop="true" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog ">
                                                    <div class="modal-content ">
                                                        <div class="modal-header ">
                                                            <div class="modal-title text-md">Edit Customer</div>
                                                            <button class="close" data-dismiss="modal">×</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="post" action="{{ route('entities.update', ['entity' => $customer->id]) }}">
                                                                @method('PUT')
                                                                @csrf
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="name">Name</label>
                                                                    <input name="name" type="text" class="form-control" id="name" placeholder="insert name" value="{{ $customer->name }}">
                                                                    <input type="hidden" name="type" value="customer">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="address">Address</label>
                                                                    <input name="address" type="text" class="form-control" id="address" placeholder="insert address" value="{{ $customer->address }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="country">Country</label>
                                                                    <input name="country" type="text" class="form-control" id="country" placeholder="insert country" value="{{ $customer->country }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="province">Province</label>
                                                                    <input name="province" type="text" class="form-control" id="province" placeholder="insert province" value="{{ $customer->province }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="city">City</label>
                                                                    <input name="city" type="text" class="form-control" id="city" placeholder="insert city" value="{{ $customer->city }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="postal_code">Postal code</label>
                                                                    <input name="postal_code" type="text" class="form-control" id="postal_code" placeholder="insert postal_code" value="{{ $customer->postal_code }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="phone">Phone</label>
                                                                    <input name="phone" type="text" class="form-control" id="phone" placeholder="insert phone" value="{{ $customer->phone }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="email">Email</label>
                                                                    <input name="email" type="email" class="form-control" id="email" placeholder="insert email" value="{{ $customer->email }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="card_number">Card Number</label>
                                                                    <input name="card_number" type="text" class="form-control" id="card_number" placeholder="insert card_number" value="{{ $customer->card_number }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="owner">Owner</label>
                                                                    <input name="owner" type="text" class="form-control" id="owner" placeholder="insert owner" value="{{ $customer->owner }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="bank">Bank</label>
                                                                    <input name="bank" type="text" class="form-control" id="bank" placeholder="insert bank" value="{{ $customer->bank }}">
                                                                </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                            </div>

                                            <!-- customer modal delete -->
                                            <div id="deletecust{{ $key }}" class="modal fade" data-backdrop="true" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog ">
                                                    <div class="modal-content ">
                                                        <div class="modal-header ">
                                                            <div class="modal-title text-md">Confirm Deletion</div>
                                                            <button class="close" data-dismiss="modal">×</button>
                                                        </div>
                                                        <div class="modal-body">
                                                        <form action="{{ route('entities.destroy', ['entity' => $customer->id]) }}" method="post">
                                                            @method('DELETE')
                                                            @csrf
                                                            <div class="p-2">
                                                                <p>You're about to delete customer data. Klick "Yes" to proceed.</p>
                                                                <p class="text-danger"><b>Note:</b> Do not delete brand that has a relation to item data.</p>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill pr-4 pl-4" data-dismiss="modal">Cancle</button>
                                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill pr-4 pl-4">Yes</button>
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
                            </div>
                        </div>
                        <div class="tab-pane fade" id="sales3" role="tabpanel" aria-labelledby="sales-tab">
                            <div class="row">
                                <div class="col-md-4">
                                    <form method="post" action="{{ route('entities.store') }}">
                                        @csrf
                                        <div class="form-group">
                                            <label class="text-muted" for="name">Name</label>
                                            <input name="name" type="text" class="form-control" id="name" placeholder="insert name">
                                            <input type="hidden" name="type" value="sales">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="address">Address</label>
                                            <input name="address" type="text" class="form-control" id="address" placeholder="insert address">
                                            
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="country">Country</label>
                                            <input name="country" type="text" class="form-control" id="country" placeholder="insert country">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="province">Province</label>
                                            <input name="province" type="text" class="form-control" id="province" placeholder="insert province">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="city">City</label>
                                            <input name="city" type="text" class="form-control" id="city" placeholder="insert city">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="postal_code">Postal code</label>
                                            <input name="postal_code" type="text" class="form-control" id="postal_code" placeholder="insert postal_code">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="phone">Phone</label>
                                            <input name="phone" type="text" class="form-control" id="phone" placeholder="insert phone">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="email">Email</label>
                                            <input name="email" type="email" class="form-control" id="email" placeholder="insert email">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="card_number">Card Number</label>
                                            <input name="card_number" type="text" class="form-control" id="card_number" placeholder="insert card_number">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="owner">Owner</label>
                                            <input name="owner" type="text" class="form-control" id="owner" placeholder="insert owner">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="bank">Bank</label>
                                            <input name="bank" type="text" class="form-control" id="bank" placeholder="insert bank">
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-primary">Add</button>
                                    </form>
                                </div>
                                <div class="col-md-8">
                                    <table class="table table-responsive table-theme v-middle table-hover">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <div class="th-inner">No.</div>
                                                    <div class="fht-cell"></div>
                                                </th>
                                                <th>
                                                    <div class="th-inner">Name</div>
                                                    <div class="fht-cell"></div>
                                                </th>
                                                <th>
                                                    <div class="th-inner">Address</div>
                                                    <div class="fht-cell"></div>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($sales as $key => $sale)
                                            <tr>
                                                <td>
                                                    <div class="text-muted text-sm">
                                                        {{ $key+1 }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-muted text-sm">
                                                        {{ $sale->name }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-muted text-sm">
                                                        {{ $sale->address }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-primary text-uppercase" data-toggle="modal" data-target="#editsales{{ $key }}">Edit</span>
                                                    <span class="badge badge-danger text-uppercase" data-toggle="modal" data-target="#deletesales{{ $key }}">Delete</span>
                                                </td>
                                            </tr>
                                            <!-- sales modal edit -->
                                            <div id="editsales{{ $key }}" class="modal fade" data-backdrop="true" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog ">
                                                    <div class="modal-content ">
                                                        <div class="modal-header ">
                                                            <div class="modal-title text-md">Edit Sales</div>
                                                            <button class="close" data-dismiss="modal">×</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="post" action="{{ route('entities.update', ['entity' => $sale->id]) }}">
                                                                @method('PUT')
                                                                @csrf
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="name">Name</label>
                                                                    <input name="name" type="text" class="form-control" id="name" placeholder="insert name" value="{{ $sale->name }}">
                                                                    <input type="hidden" name="type" value="sales">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="address">Address</label>
                                                                    <input name="address" type="text" class="form-control" id="address" placeholder="insert address" value="{{ $sale->address }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="country">Country</label>
                                                                    <input name="country" type="text" class="form-control" id="country" placeholder="insert country" value="{{ $sale->country }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="province">Province</label>
                                                                    <input name="province" type="text" class="form-control" id="province" placeholder="insert province" value="{{ $sale->province }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="city">City</label>
                                                                    <input name="city" type="text" class="form-control" id="city" placeholder="insert city" value="{{ $sale->city }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="postal_code">Postal code</label>
                                                                    <input name="postal_code" type="text" class="form-control" id="postal_code" placeholder="insert postal_code" value="{{ $sale->postal_code }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="phone">Phone</label>
                                                                    <input name="phone" type="text" class="form-control" id="phone" placeholder="insert phone" value="{{ $sale->phone }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="email">Email</label>
                                                                    <input name="email" type="email" class="form-control" id="email" placeholder="insert email" value="{{ $sale->email }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="card_number">Card Number</label>
                                                                    <input name="card_number" type="text" class="form-control" id="card_number" placeholder="insert card_number" value="{{ $sale->card_number }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="owner">Owner</label>
                                                                    <input name="owner" type="text" class="form-control" id="owner" placeholder="insert owner" value="{{ $sale->owner }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="bank">Bank</label>
                                                                    <input name="bank" type="text" class="form-control" id="bank" placeholder="insert bank" value="{{ $sale->bank }}">
                                                                </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                            </div>

                                            <!-- sales modal delete -->
                                            <div id="deletesales{{ $key }}" class="modal fade" data-backdrop="true" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog ">
                                                    <div class="modal-content ">
                                                        <div class="modal-header ">
                                                            <div class="modal-title text-md">Confirm Deletion</div>
                                                            <button class="close" data-dismiss="modal">×</button>
                                                        </div>
                                                        <div class="modal-body">
                                                        <form action="{{ route('entities.destroy', ['entity' => $sale->id]) }}" method="post">
                                                            @method('DELETE')
                                                            @csrf
                                                            <div class="p-2">
                                                                <p>You're about to delete sales data. Klick "Yes" to proceed.</p>
                                                                <p class="text-danger"><b>Note:</b> Do not delete brand that has a relation to item data.</p>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill pr-4 pl-4" data-dismiss="modal">Cancle</button>
                                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill pr-4 pl-4">Yes</button>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="clearfix"></div>
    </div>
</div>
@endsection