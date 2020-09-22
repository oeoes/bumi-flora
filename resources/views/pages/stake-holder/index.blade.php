@extends('layouts.master')

@section('page-title', 'Stake Holders')
@section('page-description', 'Data stake holders berupa supplier, customer, dan sales.')

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
                                    <form method="post" action="{{ route('stake_holders.store') }}">
                                        @csrf
                                        <div class="form-group">
                                            <label class="text-muted" for="code">Code</label>
                                            <input name="code" type="text" class="form-control" id="code" placeholder="code" required>
                                            <input type="hidden" name="type" value="supplier">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="name">Name</label>
                                            <input name="name" type="text" class="form-control" id="name" placeholder="insert name" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="address">Address</label>
                                            <input name="address" type="text" class="form-control" id="address" placeholder="insert address" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="country">Country</label>
                                            <input name="country" type="text" class="form-control" id="country" placeholder="insert country" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="province">Province</label>
                                            <input name="province" type="text" class="form-control" id="province" placeholder="insert province" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="city">City</label>
                                            <input name="city" type="text" class="form-control" id="city" placeholder="insert city" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="postal_code">Postal code</label>
                                            <input name="postal_code" type="text" class="form-control" id="postal_code" placeholder="insert postal_code" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="phone">Phone</label>
                                            <input name="phone" type="text" class="form-control" id="phone" placeholder="insert phone" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="email">Email</label>
                                            <input name="email" type="email" class="form-control" id="email" placeholder="insert email" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="card_number">Card Number</label>
                                            <input name="card_number" type="text" class="form-control" id="card_number" placeholder="insert card_number" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="owner">Owner</label>
                                            <input name="owner" type="text" class="form-control" id="owner" placeholder="insert owner" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="bank">Bank</label>
                                            <input name="bank" type="text" class="form-control" id="bank" placeholder="insert bank" required>
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
                                                    <span class="badge badge-primary text-uppercase" data-toggle="modal" data-target="#editbrand{{ $key }}">Edit</span>
                                                    <span class="badge badge-danger text-uppercase" data-toggle="modal" data-target="#deletebrand{{ $key }}">Delete</span>
                                                </td>
                                            </tr>
                                            <!-- supplier modal edit -->
                                            <div id="editbrand{{ $key }}" class="modal fade" data-backdrop="true" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog ">
                                                    <div class="modal-content ">
                                                        <div class="modal-header ">
                                                            <div class="modal-title text-md">Edit Supplier</div>
                                                            <button class="close" data-dismiss="modal">×</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="post" action="{{ route('stake_holders.update', ['stake_holder' => $supplier->id]) }}">
                                                                @method('PUT')
                                                                @csrf
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="code">Code</label>
                                                                    <input name="code" type="text" class="form-control" id="code" placeholder="code" value="{{ $supplier->code }}" required>
                                                                    <input type="hidden" name="type" value="supplier">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="name">Name</label>
                                                                    <input name="name" type="text" class="form-control" id="name" placeholder="insert name" value="{{ $supplier->name }}" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="address">Address</label>
                                                                    <input name="address" type="text" class="form-control" id="address" placeholder="insert address" value="{{ $supplier->address }}" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="country">Country</label>
                                                                    <input name="country" type="text" class="form-control" id="country" placeholder="insert country" value="{{ $supplier->country }}" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="province">Province</label>
                                                                    <input name="province" type="text" class="form-control" id="province" placeholder="insert province" value="{{ $supplier->province }}" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="city">City</label>
                                                                    <input name="city" type="text" class="form-control" id="city" placeholder="insert city" value="{{ $supplier->city }}" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="postal_code">Postal code</label>
                                                                    <input name="postal_code" type="text" class="form-control" id="postal_code" placeholder="insert postal_code" value="{{ $supplier->postal_code }}" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="phone">Phone</label>
                                                                    <input name="phone" type="text" class="form-control" id="phone" placeholder="insert phone" value="{{ $supplier->phone }}" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="email">Email</label>
                                                                    <input name="email" type="email" class="form-control" id="email" placeholder="insert email" value="{{ $supplier->email }}" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="card_number">Card Number</label>
                                                                    <input name="card_number" type="text" class="form-control" id="card_number" placeholder="insert card_number" value="{{ $supplier->card_number }}" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="owner">Owner</label>
                                                                    <input name="owner" type="text" class="form-control" id="owner" placeholder="insert owner" value="{{ $supplier->owner }}" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="bank">Bank</label>
                                                                    <input name="bank" type="text" class="form-control" id="bank" placeholder="insert bank" value="{{ $supplier->bank }}" required>
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

                                            <!-- brand modal delete -->
                                            <div id="deletebrand{{ $key }}" class="modal fade" data-backdrop="true" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog ">
                                                    <div class="modal-content ">
                                                        <div class="modal-header ">
                                                            <div class="modal-title text-md">Confirm Deletion</div>
                                                            <button class="close" data-dismiss="modal">×</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="p-2">
                                                                <p>You're about to delete existing brand. Klick "Yes" to proceed.</p>
                                                                <p class="text-danger"><b>Note:</b> Do not delete brand that has a relation to item data.</p>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancle</button>
                                                            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Yes</button>
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
                                <div class="col-md-6">
                                    <form method="post" action="{{ route('units.store') }}">
                                        @csrf
                                        <div class="form-group">
                                            <label class="text-muted" for="exampleInputEmail1">Unit name (satuan)</label>
                                            <input type="text" name="unit" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter unit name">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="exampleInputPassword1">Description</label>
                                            <input type="text" name="description" class="form-control" id="exampleInputPassword1" placeholder="Type a bit of description">
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-primary">Add</button>
                                    </form>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-responsive table-theme v-middle table-hover">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <div class="th-inner">No.</div>
                                                    <div class="fht-cell"></div>
                                                </th>
                                                <th>
                                                    <div class="th-inner">Unit</div>
                                                    <div class="fht-cell"></div>
                                                </th>
                                                <th>
                                                    <div class="th-inner">Description</div>
                                                    <div class="fht-cell"></div>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($units as $key => $unit)
                                            <tr>
                                                <td>
                                                    <div class="text-muted text-sm">
                                                        {{ $key+1 }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-muted text-sm">
                                                        {{ $unit->unit }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-muted text-sm">
                                                        {{ $unit->description }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-primary text-uppercase" data-toggle="modal" data-target="#editunit{{ $key }}">Edit</span>
                                                    <span class="badge badge-danger text-uppercase" data-toggle="modal" data-target="#deleteunit{{ $key }}">Delete</span>
                                                </td>
                                            </tr>
                                            <!-- unit modal edit -->
                                            <div id="editunit{{ $key }}" class="modal fade" data-backdrop="true" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog ">
                                                    <div class="modal-content ">
                                                        <div class="modal-header ">
                                                            <div class="modal-title text-md">Edit Unit</div>
                                                            <button class="close" data-dismiss="modal">×</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="post" action="{{ route('units.update', ['unit' => $unit->id]) }}">
                                                                @method('PUT')
                                                                @csrf
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="exampleInputEmail1">Unit name (satuan)</label>
                                                                    <input type="text" name="unit" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter unit name" value="{{ $unit->unit }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="exampleInputPassword1">Description</label>
                                                                    <input type="text" name="description" class="form-control" id="exampleInputPassword1" placeholder="Type a bit of description" value="{{ $unit->description }}">
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

                                            <!-- unit modal delete -->
                                            <div id="deleteunit{{ $key }}" class="modal fade" data-backdrop="true" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog ">
                                                    <div class="modal-content ">
                                                        <div class="modal-header ">
                                                            <div class="modal-title text-md">Confirm Dletion</div>
                                                            <button class="close" data-dismiss="modal">×</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="p-2">
                                                                <p>You're about to delete existing unit. Klick "Yes" to proceed.</p>
                                                                <p class="text-danger"><b>Note:</b> Do not delete unit that has a relation to item data.</p>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancle</button>
                                                            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Yes</button>
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
                                <div class="col-md-6">
                                    <form method="post" action="{{ route('categories.store') }}">
                                        @csrf
                                        <div class="form-group">
                                            <label class="text-muted" for="exampleInputEmail1">Category</label>
                                            <input type="text" name="category" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter category">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="exampleInputPassword1">Description</label>
                                            <input type="text" name="description" class="form-control" id="exampleInputPassword1" placeholder="Type a bit of description">
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-primary">Add</button>
                                    </form>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-responsive table-theme v-middle table-hover">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <div class="th-inner">No.</div>
                                                    <div class="fht-cell"></div>
                                                </th>
                                                <th>
                                                    <div class="th-inner">Category</div>
                                                    <div class="fht-cell"></div>
                                                </th>
                                                <th>
                                                    <div class="th-inner">Keterangan</div>
                                                    <div class="fht-cell"></div>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($categories as $key => $category)
                                            <tr>
                                                <td>
                                                    <div class="text-muted text-sm">
                                                        {{ $key+1 }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-muted text-sm">
                                                        {{ $category->category }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-muted text-sm">
                                                        {{ $category->description }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-primary text-uppercase" data-toggle="modal" data-target="#editcategory{{ $key }}">Edit</span>
                                                    <span class="badge badge-danger text-uppercase" data-toggle="modal" data-target="#deletecategory{{ $key }}">Delete</span>
                                                </td>
                                            </tr>
                                            <!-- category modal edit -->
                                            <div id="editcategory{{ $key }}" class="modal fade" data-backdrop="true" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog ">
                                                    <div class="modal-content ">
                                                        <div class="modal-header ">
                                                            <div class="modal-title text-md">Edit Category</div>
                                                            <button class="close" data-dismiss="modal">×</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="post" action="{{ route('categories.update', ['category' => $category->id]) }}">
                                                                @method('PUT')
                                                                @csrf
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="exampleInputEmail1">Category</label>
                                                                    <input type="text" name="category" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter category" value="{{ $category->category }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="exampleInputPassword1">Password</label>
                                                                    <input type="text" name="description" class="form-control" id="exampleInputPassword1" placeholder="Type a bit of description" value="{{ $category->description }}">
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

                                            <!-- category modal delete -->
                                            <div id="deletecategory{{ $key }}" class="modal fade" data-backdrop="true" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog ">
                                                    <div class="modal-content ">
                                                        <div class="modal-header ">
                                                            <div class="modal-title text-md">Confirm Dletion</div>
                                                            <button class="close" data-dismiss="modal">×</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="p-2">
                                                                <p>You're about to delete existing category. Klick "Yes" to proceed.</p>
                                                                <p class="text-danger"><b>Note:</b> Do not delete category that has a relation to item data.</p>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancle</button>
                                                            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Yes</button>
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