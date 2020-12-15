@extends('layouts.master')

@section('page-title', 'Data Pendukung')
@section('page-description', 'Data pendukung berupa input data merek, jenis, dan satuan.')

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
                                    <a class="nav-link active" id="merek-tab" data-toggle="tab" href="#merek3" role="tab" aria-controls="merek" aria-selected="true">Merek</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="satuan-tab" data-toggle="tab" href="#satuan3" role="tab" aria-controls="satuan" aria-selected="false">Satuan</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="category-tab" data-toggle="tab" href="#category3" role="tab" aria-controls="category" aria-selected="false">Kategori</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="payment_method-tab" data-toggle="tab" href="#payment_method3" role="tab" aria-controls="payment_method" aria-selected="false">Payment Method</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="payment_type-tab" data-toggle="tab" href="#payment_type3" role="tab" aria-controls="payment_type" aria-selected="false">Payment Type</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="tab-content p-3">
                        <div class="tab-pane fade active show" id="merek3" role="tabpanel" aria-labelledby="merek-tab">
                            <div class="row">
                                <div class="col-md-6">
                                    <form method="post" action="{{ route('brands.store') }}">
                                        @csrf
                                        <div class="form-group">
                                            <label class="text-muted" for="exampleInputEmail1">Brand Name</label>
                                            <input name="brand" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter your brand">
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="exampleInputPassword1">Description</label>
                                            <input type="text" name="description" class="form-control" id="exampleInputPassword1" placeholder="Type a bit of description">
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill pr-4 pl-4">Add</button>
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
                                                    <div class="th-inner">Brand</div>
                                                    <div class="fht-cell"></div>
                                                </th>
                                                <th>
                                                    <div class="th-inner">Description</div>
                                                    <div class="fht-cell"></div>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($brands as $key => $brand)
                                            <tr>
                                                <td>
                                                    <div class="text-muted text-sm">
                                                        {{ $brand->id }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-muted text-sm">
                                                        {{ $brand->brand }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-muted text-sm">
                                                        {{ $brand->description }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-primary text-uppercase" data-toggle="modal" data-target="#editbrand{{ $key }}">Edit</span>
                                                    <span class="badge badge-danger text-uppercase" data-toggle="modal" data-target="#deletebrand{{ $key }}">Delete</span>
                                                </td>
                                            </tr>
                                            <!-- brand modal edit -->
                                            <div id="editbrand{{ $key }}" class="modal fade" data-backdrop="true" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog ">
                                                    <div class="modal-content ">
                                                        <div class="modal-header ">
                                                            <div class="modal-title text-md">Edit Brand</div>
                                                            <button class="close" data-dismiss="modal">×</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="post" action="{{ route('brands.update', ['brand' => $brand->id]) }}">
                                                                @method('PUT')
                                                                @csrf
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="exampleInputEmail1">Brand Name</label>
                                                                    <input name="brand" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter your brand" value="{{ $brand->brand }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="exampleInputPassword1">Description</label>
                                                                    <input type="text" name="description" class="form-control" id="exampleInputPassword1" placeholder="Type a bit of description" value="{{ $brand->description }}">
                                                                </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill pr-4 pl-4" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill pr-4 pl-4">Save Changes</button>
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
                                                        <form method="post"action="{{ route('brands.destroy', ['brand' => $brand->id]) }}">
                                                                @method('DELETE')
                                                                @csrf
                                                            <div class="p-2">
                                                                <p>You're about to delete existing brand. Klick "Yes" to proceed.</p>
                                                                <p class="text-danger"><b>Note:</b> Do not delete brand that has a relation to item data.</p>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill pr-4 pl-4" data-dismiss="modal">Cancle</button>
                                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill pr-4 pl-4" >Yes</button>
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
                        <div class="tab-pane fade" id="satuan3" role="tabpanel" aria-labelledby="satuan-tab">
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
                                        <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill pr-4 pl-4">Add</button>
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
                                                        {{ $unit->id }}
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
                                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill pr-4 pl-4" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill pr-4 pl-4">Save Changes</button>
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
                                                            <form method="post"action="{{ route('units.destroy', ['unit' => $unit->id]) }}">
                                                                @method('DELETE')
                                                                @csrf
                                                            <div class="p-2">
                                                                <p>You're about to delete existing unit. Klick "Yes" to proceed.</p>
                                                                <p class="text-danger"><b>Note:</b> Do not delete unit that has a relation to item data.</p>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill pr-4 pl-4" data-dismiss="modal">Cancle</button>
                                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill pr-4 pl-4" >Yes</button>
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
                        <div class="tab-pane fade" id="category3" role="tabpanel" aria-labelledby="category-tab">
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
                                        <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill pr-4 pl-4">Add</button>
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
                                                        {{ $category->id }}
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
                                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill pr-4 pl-4" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill pr-4 pl-4">Save Changes</button>
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
                                                            <form method="post"action="{{ route('categories.destroy', ['category' => $category->id]) }}">
                                                                @method('DELETE')
                                                                @csrf
                                                            <div class="p-2">
                                                                <p>You're about to delete existing category. Klick "Yes" to proceed.</p>
                                                                <p class="text-danger"><b>Note:</b> Do not delete category that has a relation to item data.</p>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill pr-4 pl-4" data-dismiss="modal">Cancle</button>
                                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill pr-4 pl-4" >Yes</button>
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
                        <div class="tab-pane fade" id="payment_method3" role="tabpanel" aria-labelledby="payment_method-tab">
                            <div class="row">
                                <div class="col-md-6">
                                    <form method="post" action="{{ route('payments.payment-method.store') }}">
                                        @csrf
                                        <div class="form-group">
                                            <label class="text-muted" for="exampleInputEmail1">Payment method</label>
                                            <input type="text" name="method_name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Name of payment method">
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill pr-4 pl-4">Add</button>
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
                                                    <div class="th-inner">Method</div>
                                                    <div class="fht-cell"></div>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($payment_methods as $key => $payment_method)
                                            <tr>
                                                <td>
                                                    <div class="text-muted text-sm">
                                                        {{ $payment_method->id }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-muted text-sm">
                                                        {{ $payment_method->method_name }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-primary text-uppercase" data-toggle="modal" data-target="#editpayment-method{{ $key }}">Edit</span>
                                                    <span class="badge badge-danger text-uppercase" data-toggle="modal" data-target="#deletepayment-method{{ $key }}">Delete</span>
                                                </td>
                                            </tr>
                                            <!-- payment-method modal edit -->
                                            <div id="editpayment-method{{ $key }}" class="modal fade" data-backdrop="true" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog ">
                                                    <div class="modal-content ">
                                                        <div class="modal-header ">
                                                            <div class="modal-title text-md">Edit Payment Method</div>
                                                            <button class="close" data-dismiss="modal">×</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="post" action="{{ route('payments.payment-method.update', ['payment_method_id' => $payment_method->id]) }}">
                                                                @method('PUT')
                                                                @csrf
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="exampleInputEmail1">Payment Method</label>
                                                                    <input type="text" name="method_name" class="form-control" id="exampleInputEmail1" placeholder="Enter Payment method" value="{{ $payment_method->method_name }}">
                                                                </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill pr-4 pl-4" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill pr-4 pl-4">Save Changes</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                            </div>

                                            <!-- peyment modal delete -->
                                            <div id="deletepayment-method{{ $key }}" class="modal fade" data-backdrop="true" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog ">
                                                    <div class="modal-content ">
                                                        <div class="modal-header ">
                                                            <div class="modal-title text-md">Confirm Dletion</div>
                                                            <button class="close" data-dismiss="modal">×</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="post"action="{{ route('payments.payment-method.destroy', ['payment_method_id' => $payment_method->id]) }}">
                                                                @method('DELETE')
                                                                @csrf
                                                            <div class="p-2">
                                                                <p>You're about to delete existing payment method. Klick "Yes" to proceed.</p>
                                                                <p class="text-danger"><b>Note:</b> Do not delete payment method that has a relation to item data.</p>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill pr-4 pl-4" data-dismiss="modal">Cancle</button>
                                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill pr-4 pl-4" >Yes</button>
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
                        <div class="tab-pane fade" id="payment_type3" role="tabpanel" aria-labelledby="payment_type-tab">
                            <div class="row">
                                <div class="col-md-6">
                                    <form method="post" action="{{ route('payments.payment-type.store') }}">
                                        @csrf
                                        <div class="form-group">
                                            <label class="text-muted" for="exampleInputEmail1">Payment method</label>
                                            <select class="form-control" name="payment_method_id" id="" required>
                                            @foreach($payment_methods as $pm)
                                                <option value="{{ $pm->id }}">{{ $pm->method_name }}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="text-muted" for="exampleInputPassword1">Payment type</label>
                                            <input type="text" name="type_name" class="form-control" placeholder="Name of payment type">
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill pr-4 pl-4">Add</button>
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
                                                    <div class="th-inner">Method</div>
                                                    <div class="fht-cell"></div>
                                                </th>
                                                <th>
                                                    <div class="th-inner">Type</div>
                                                    <div class="fht-cell"></div>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($payment_types as $key => $payment_type)
                                            <tr>
                                                <td>
                                                    <div class="text-muted text-sm">
                                                        {{ $key+1 }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-muted text-sm">
                                                        {{ $payment_type->method_name }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-muted text-sm">
                                                        {{ $payment_type->type_name }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-primary text-uppercase" data-toggle="modal" data-target="#editpayment-type{{ $key }}">Edit</span>
                                                    <span class="badge badge-danger text-uppercase" data-toggle="modal" data-target="#deletepayment-type{{ $key }}">Delete</span>
                                                </td>
                                            </tr>
                                            <!-- payment-type modal edit -->
                                            <div id="editpayment-type{{ $key }}" class="modal fade" data-backdrop="true" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog ">
                                                    <div class="modal-content ">
                                                        <div class="modal-header ">
                                                            <div class="modal-title text-md">Edit Payment Type</div>
                                                            <button class="close" data-dismiss="modal">×</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="post" action="{{ route('payments.payment-type.update', ['payment_type_id' => $payment_type->id]) }}">
                                                                @method('PUT')
                                                                @csrf
                                                                <div class="form-group">
                                                                    <label class="text-muted">Payment Method</label>
                                                                    <select name="payment_method_id" class="form-control">
                                                                        @foreach($payment_methods as $pm) 
                                                                            @if($pm->id == $payment_type->payment_method_id)
                                                                                <option value="{{ $pm->id }}" selected>{{$pm->method_name}}</option>
                                                                            @else
                                                                                <option value="{{ $pm->id }}">{{$pm->method_name}}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="text-muted" for="exampleInputPassword1">Payment Type</label>
                                                                    <input type="text" name="type_name" class="form-control" placeholder="Type a bit of description" value="{{ $payment_type->type_name }}">
                                                                </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill pr-4 pl-4" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill pr-4 pl-4">Save Changes</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                            </div>

                                            <!-- category modal delete -->
                                            <div id="deletepayment-type{{ $key }}" class="modal fade" data-backdrop="true" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog ">
                                                    <div class="modal-content ">
                                                        <div class="modal-header ">
                                                            <div class="modal-title text-md">Confirm Dletion</div>
                                                            <button class="close" data-dismiss="modal">×</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="post"action="{{ route('payments.payment-type.destroy', ['payment_type_id' => $payment_type->id]) }}">
                                                                @method('DELETE')
                                                                @csrf
                                                            <div class="p-2">
                                                                <p>You're about to delete existing payment type. Klick "Yes" to proceed.</p>
                                                                <p class="text-danger"><b>Note:</b> Do not delete peyment type that has a relation to item data.</p>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill pr-4 pl-4" data-dismiss="modal">Cancle</button>
                                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill pr-4 pl-4" >Yes</button>
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