@extends('layouts.master')

@section('page-title', 'View Item')
@section('page-description', 'Halaman untuk menampilkan detail item.')

@section('btn-custom')
<div>
    <a href="{{ route('items.index') }}" class="btn btn-sm text-muted">
        <i data-feather="arrow-left"></i>
        <span class="d-none d-sm-inline mx-1">Back</span>
    </a>
</div>
@endsection

@section('content')
<div class="page-content page-container" id="page-content">
    <div class="padding">
        <div class="row">
            <div class="col-md-6">
                <div class="card sticky p-2"
                    style="z-index: 1; visibility: visible; transform: none; opacity: 1; transition: ease 1s ease 0s;">
                    <img style="max-width: 80%; margin: 0 auto" src="{{ asset('images/default.svg') }}" alt="">
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('items.edit', ['item' => $item->id]) }}" class="btn btn-sm btn-outline-primary rounded-pill pr-4 pl-4"><i
                                data-feather="edit-2"></i></a>
                        <button class="btn btn-sm btn-outline-danger rounded-pill pr-4 pl-4" data-toggle="modal"
                            data-target="#delete-item"><i data-feather="trash"></i></button>

                        <!-- modal delete item -->
                        <div id="delete-item" class="modal fade" da ta-backdrop="true" tyle="display: none;"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content h-100 no-radius">
                                    <div class="modal-header ">
                                        <div class="modal-title text-sm">Delete item confirmation</div>
                                        <button class="close" data-dismiss="modal">×</button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="post" action="{{ route('items.destroy', ['item' => $item->id]) }}">
                                            @method('DELETE')
                                            @csrf
                                            <strong>Are you sure to delete this item?</strong>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-sm btn-outline-secondary rounded-pill pr-4 pl-4" data-dismiss="modal">Cancle</button>
                                        <input type="submit" class="btn btn-sm btn-outline-primary rounded-pill pr-4 pl-4" value="Yes">
                                    </form>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        <div class="h2"><strong>{{ $item->name }}</strong></div>
                        <div class="h5">Rp.{{ number_format($item->price, 2) }}</div>
                        <table class="table table-theme v-middle table-hover">
                            <tr>
                                <th>Dept</th>
                                <td>{{ strtoupper($item->dept) }}</td>
                            </tr>
                            <tr>
                                <th>Item</th>
                                <td>{{ $item->name }}</td>
                            </tr>
                            <tr>
                                <th>Barcode</th>
                                <td>{{ $item->barcode }}</td>
                            </tr>
                            <tr>
                                <th>Merk</th>
                                <td>{{ $item->brand }}</td>
                            </tr>
                            <tr>
                                <th>Kategori</th>
                                <td>{{ $item->category }}</td>
                            </tr>
                            <tr>
                                <th>Satuan</th>
                                <td>{{ $item->unit }}</td>
                            </tr>
                            <tr>
                                <th>Satuan Dasar</th>
                                <td>{{ $item->base_unit }}</td>
                            </tr>
                            <tr>
                                <th>Konversi Satuan Dasar</th>
                                <td>{{ $item->base_unit_conversion }}</td>
                            </tr>
                            <tr>
                                <th>Rak</th>
                                <td>{{ $item->cabinet }}</td>
                            </tr>
                            <tr>
                                <th>Harga pokok</th>
                                <td>Rp.{{ number_format($item->main_cost, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Harga jual</th>
                                <td>Rp.{{ number_format($item->price, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Stok minimum</th>
                                <td>{{ $item->min_stock }}</td>
                            </tr>
                            <tr>
                                <th>Stok</th>
                                <td>{{ $item->stock }}</td>
                            </tr>
                            <tr>
                                <th>Deskripsi</th>
                                <td>{{ $item->description }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
@endsection
