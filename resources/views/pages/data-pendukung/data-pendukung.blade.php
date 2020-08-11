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
                                                        {{ $key+1 }}
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
                                                            <div class="modal-title text-md">Confirm Dletion</div>
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