@extends('layouts.master')

@section('page-title', 'Grosir')
@section('page-description', 'Tambah item grosir')


@section('custom-js')
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('js/axios.js') }}"></script>
<script src="{{ asset('js/grosir.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#data-item-grosir').DataTable();
    });

    $(document).ready(function() {
        $('#data-item-search').DataTable();
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
                <div class="table-responsive">
                    <table id="data-item-grosir" class="table table-theme v-middle table-hover">
                        <thead>
                            <tr>
                                <th><span class="text-muted">Item</span></th>
                                <th><span class="text-muted">Satuan</span></th>
                                <th><span class="text-muted">Harga Jual</span></th>
                                <th><span class="text-muted">Minimum Item</span></th>
                                <th><span class="text-muted">Harga Grosir</span></th>
                                <th><span class="text-muted">Action</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($grosirs as $key => $grosir)
                            <tr>
                                <td>
                                    <div class="text-muted text-sm">{{ $grosir->name }}</div>
                                </td>
                                <td>
                                    <div class="text-muted text-sm">{{ $grosir->unit }}</div>
                                </td>
                                <td>
                                    <div class="text-muted text-sm">{{ $grosir->price }}</div>
                                </td>
                                <td>
                                    <div class="text-muted text-sm">{{ $grosir->minimum_item }}</div>
                                </td>
                                <td>
                                    <div class="text-muted text-sm">{{ $grosir->grosir_price }}</div>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary rounded-pill pr-4 pl-4" data-toggle="modal" data-target="#edit-grosir{{$key}}">Edit</button>
                                    <button class="btn btn-sm btn-outline-danger rounded-pill pr-4 pl-4" data-toggle="modal" data-target="#delete-grosir{{$key}}">Delete</button>
                                </td>
                            </tr>

                            <!-- modal delete grosir -->
                            <div class="modal fade" id="delete-grosir{{$key}}" tabindex="-1" role="dialog" aria-labelledby="paymentLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="paymentLabel">Delete Data Grosir</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="post" action="{{ route('grosirs.destroy', ['grosir' => $grosir->id]) }}">
                                                @method('DELETE')
                                                @csrf
                                                Anda yakin untuk menghapus Grosir item?

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm rounded-pill pr-4 pl-4 btn-outline-secondary" data-dismiss="modal">Batal</button>
                                            <input type="submit" class="btn btn-sm rounded-pill pr-4 pl-4 btn-outline-primary" value="Ya">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- modal edit grosir -->
                            <div class="modal fade" id="edit-grosir{{$key}}" tabindex="-1" role="dialog" aria-labelledby="paymentLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="paymentLabel">Perbarui Data Grosir</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="post" action="{{ route('grosirs.update', ['grosir' => $grosir->id]) }}">
                                                @method('PUT')
                                                @csrf
                                                <div class="form-group">
                                                    <label>Item</label>
                                                    <input type="text" class="form-control" value="{{ $grosir->name }}" readonly>
                                                </div>

                                                <div class="form-group">
                                                    <label>Minimum Item</label>
                                                    <input name="minimum_item" type="text" class="form-control" value="{{ $grosir->minimum_item }}">
                                                </div>

                                                <div class="form-group">
                                                    <label>Harga Grosir</label>
                                                    <input name="price" type="text" class="form-control" value="{{ $grosir->grosir_price }}">
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
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        Create grosir item
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Item</label>
                            <div class="row no-gutters">
                                <div class="col-8">
                                    <input id="item_name" type="text" class="form-control" style="border-radius: 4px 0 0 4px!important" placeholder="select item" readonly>
                                    <input id="item_id" type="hidden" class="form-control">
                                </div>
                                <div class="col-2"><button class="btn btn-primary" style="border-radius: 0 4px 4px 0!important" data-toggle="modal" data-target="#pilih-item">Select</button></div>
                            </div>

                            <!-- Modal pilih item -->
                            <div class="modal fade" id="pilih-item" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Select Item</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <table id="data-item-search" class="table my-responsive table-theme v-middle table-hover" style="margin-top: 0px;">
                                                <thead>
                                                    <tr>
                                                        <th data-field="type">
                                                            <div class="th-inner">Nama</div>
                                                            <div class="fht-cell"></div>
                                                        </th>
                                                        <th data-field="itemtype">
                                                            <div class="th-inner">Satuan</div>
                                                            <div class="fht-cell"></div>
                                                        </th>
                                                        <th data-field="itemtype">
                                                            <div class="th-inner">Harga Pokok</div>
                                                            <div class="fht-cell"></div>
                                                        </th>
                                                        <th data-field="itemtype">
                                                            <div class="th-inner">Harga Jual</div>
                                                            <div class="fht-cell"></div>
                                                        </th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($items as $key => $item)
                                                    <tr class=" " data-index="0" data-id="17">
                                                        <td>
                                                            <div class="text-muted text-sm">
                                                                {{ $item->name }}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="item-amount d-sm-block text-sm">
                                                                {{ $item->unit }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span class="item-amount d-sm-block text-sm">
                                                                Rp.{{ number_format($item->main_cost, 2) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span class="item-amount d-sm-block text-sm">
                                                                Rp.{{ number_format($item->price, 2) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <button onclick="select_item('{{ $item->id }}', '{{ $item->name }}', '{{ $item->price }}')" class="btn btn-sm btn-primary rounded-pill"><i data-feather='check'></i></button>
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
                            <label for="price">Price</label>
                            <input type="text" class="form-control" id="item_price" readonly>
                        </div>

                        <div class="form-group">
                            <label for="minimum_item">Minimum item</label>
                            <input id="minimum_item" type="number" class="form-control" min="1" value="0">
                        </div>

                        <div class="form-group">
                            <label for="new_price">New Price</label>
                            <input id="new_price" type="number" class="form-control" min="1" value="0">
                        </div>

                        <div class="form-group">
                            <button onclick="store_grosir()" id="store_grosir" class="btn btn-sm btn-primary">Add</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="clearfix"></div>
    </div>
</div>
@endsection