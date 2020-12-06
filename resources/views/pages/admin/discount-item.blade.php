@extends('layouts.master')

@section('page-title', 'Discount Item')
@section('page-description', 'Manage discount for items.')


@section('custom-js')
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('js/axios.js') }}"></script>
<script src="{{ asset('js/discount-item.js') }}"></script>
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

    .disc-toggle {
        display: none;
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
                                <th>Promo name</th>
                                <th>Jenis promo</th>
                                <th>Value</th>
                                <th>Status</th>
                                <th>Jadwal</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                @foreach($discounts as $key => $discount)
                                <tr>
                                    <td>{{ $discount->promo_name }}</td>
                                    @if($discount->promo_item_type == 'category')
                                    <td><span
                                            class="badge badge-primary text-uppercase">{{ $discount->promo_item_type }}</span>
                                        : {{ strtoupper($discount->category) }}</td>
                                    @else
                                    <td><span
                                            class="badge badge-success text-uppercase">{{ $discount->promo_item_type }}</span>
                                        : {{ ucwords($discount->name) }}</td>
                                    @endif
                                    <td>{{ $discount->value }}%</td>
                                    @if($discount->status == 1)
                                    <td><small class="text-success">Active</small></td>
                                    @else
                                    <td><small class="text-danger">Off</small></td>
                                    @endif
                                    <td>
                                        <button class="btn btn-sm btn-outline-info rounded-pill pr-4 pl-4"
                                            data-toggle="modal" data-target="#aturjadwal{{$key}}">Atur</button>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary rounded-pill pr-4 pl-4"
                                            data-toggle="modal" data-target="#editdiscount{{$key}}">Edit</button>
                                        <button class="btn btn-sm btn-outline-danger rounded-pill pr-4 pl-4"
                                            data-toggle="modal" data-target="#deletediscount{{$key}}">Delete</button>
                                    </td>
                                </tr>

                                <!-- modal delete discount -->
                                <div class="modal fade" id="deletediscount{{$key}}" tabindex="-1" role="dialog"
                                    aria-labelledby="paymentLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="paymentLabel">Delete Discount</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="post"
                                                    action="{{ route('discounts.delete_discount_item', ['discount_item_id' => $discount->discount_item_id]) }}">
                                                    @method('DELETE')
                                                    @csrf
                                                    Anda yakin untuk menghapus diskon pelanggan?

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button"
                                                    class="btn btn-sm rounded-pill pr-4 pl-4 btn-outline-secondary"
                                                    data-dismiss="modal">Batal</button>
                                                <input type="submit"
                                                    class="btn btn-sm rounded-pill pr-4 pl-4 btn-outline-primary"
                                                    value="Ya">
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- modal atur jadwal discount -->
                                <div class="modal fade" id="aturjadwal{{$key}}" tabindex="-1" role="dialog"
                                    aria-labelledby="paymentLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="paymentLabel">Atur Jadwal Aktif Discount
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="post" action="{{ route('discounts.occurences', ['discount_id' => $discount->discount_item_id]) }}">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-4">
                                                            <div class="custom-control custom-checkbox mb-2">
                                                                <input <?php if(in_array('monday', unserialize($discount->occurences))) echo "checked" ?> class="custom-control-input"
                                                                    name="discount_active_at[]" type="checkbox"
                                                                    value="monday" id="monday{{$key}}">
                                                                <label class="custom-control-label" for="monday{{$key}}">
                                                                    Senin
                                                                </label>
                                                            </div>
                                                            <div class="custom-control custom-checkbox mb-2">
                                                                <input <?php if(in_array('tuesday', unserialize($discount->occurences))) echo "checked" ?> class="custom-control-input"
                                                                    name="discount_active_at[]" type="checkbox"
                                                                    value="tuesday" id="tuesday{{$key}}">
                                                                <label class="custom-control-label" for="tuesday{{$key}}">
                                                                    Selasa
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="col-4">
                                                            <div class="custom-control custom-checkbox mb-2">
                                                                <input <?php if(in_array('wednesday', unserialize($discount->occurences))) echo "checked" ?> class="custom-control-input"
                                                                    name="discount_active_at[]" type="checkbox"
                                                                    value="wednesday" id="wednesday{{$key}}">
                                                                <label class="custom-control-label" for="wednesday{{$key}}">
                                                                    Rabu
                                                                </label>
                                                            </div>
                                                            <div class="custom-control custom-checkbox mb-2">
                                                                <input <?php if(in_array('thursday', unserialize($discount->occurences))) echo "checked" ?> class="custom-control-input"
                                                                    name="discount_active_at[]" type="checkbox"
                                                                    value="thursday" id="thursday{{$key}}">
                                                                <label class="custom-control-label" for="thursday{{$key}}">
                                                                    Kamis
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="col-4">
                                                            <div class="custom-control custom-checkbox mb-2">
                                                                <input <?php if(in_array('friday', unserialize($discount->occurences))) echo "checked" ?> class="custom-control-input"
                                                                    name="discount_active_at[]" type="checkbox"
                                                                    value="friday" id="friday{{$key}}">
                                                                <label class="custom-control-label" for="friday{{$key}}">
                                                                    Jum'at
                                                                </label>
                                                            </div>
                                                            <div class="custom-control custom-checkbox mb-2">
                                                                <input <?php if(in_array('saturday', unserialize($discount->occurences))) echo "checked" ?> class="custom-control-input"
                                                                    name="discount_active_at[]" type="checkbox"
                                                                    value="saturday" id="saturday{{$key}}">
                                                                <label class="custom-control-label" for="saturday{{$key}}">
                                                                    Sabtu
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="col-4">
                                                            <div class="custom-control custom-checkbox mb-2">
                                                                <input <?php if(in_array('sunday', unserialize($discount->occurences))) echo "checked" ?> class="custom-control-input"
                                                                    name="discount_active_at[]" type="checkbox"
                                                                    value="sunday" id="sunday{{$key}}">
                                                                <label class="custom-control-label" for="sunday{{$key}}">
                                                                    Minggu
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button"
                                                    class="btn btn-sm rounded-pill pr-4 pl-4 btn-outline-secondary"
                                                    data-dismiss="modal">Batal</button>
                                                <input type="submit"
                                                    class="btn btn-sm rounded-pill pr-4 pl-4 btn-outline-primary"
                                                    value="Simpan">
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
                                                <form method="post"
                                                    action="{{ route('discounts.update_discount_item', ['discount_item_id' => $discount->discount_item_id]) }}">
                                                    @method('PUT')
                                                    @csrf
                                                    <div class="form-group">
                                                        <label>Promo name</label>
                                                        <input name="promo_name" type="text" class="form-control"
                                                            value="{{ $discount->promo_name }}">
                                                    </div>

                                                    @if($discount->promo_item_type == 'item')
                                                    <div class="form-group">
                                                        <label>Item</label>
                                                        <select name="item_id" class="form-control">
                                                            @foreach($items as $item)
                                                            @if($discount->item_id == $item->id)
                                                            <option selected value="{{ $item->id }}">
                                                                {{ ucwords($item->name) }}</option>
                                                            @else
                                                            <option value="{{ $item->id }}">
                                                                {{ ucwords($item->name) }}</option>
                                                            @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @else
                                                    <div class="form-group">
                                                        <label>Category</label>
                                                        <select name="category_id" class="form-control">
                                                            @foreach($categories as $category)
                                                            @if($discount->category_id == $category->id)
                                                            <option selected value="{{ $category->id }}">
                                                                {{ strtoupper($category->category) }}</option>
                                                            @else
                                                            <option value="{{ $category->id }}">
                                                                {{ strtoupper($category->category) }}</option>
                                                            @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @endif

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
                                                <button type="button"
                                                    class="btn btn-sm rounded-pill pr-4 pl-4 btn-outline-secondary"
                                                    data-dismiss="modal">Batal</button>
                                                <input type="submit"
                                                    class="btn btn-sm rounded-pill pr-4 pl-4 btn-outline-primary"
                                                    value="Perbarui">
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
                            <input name="promo_name" id="promo_name" type="text" class="form-control form-control-sm">
                        </div>
                        <div class="form-group">
                            <label>Jenis promo</label>
                            <select id="promo_item_type" class="form-control">
                                <option>Pilih jenis promo</option>
                                <option value="item">Per item</option>
                                <option value="category">Per category</option>
                            </select>
                        </div>

                        <div class="form-group disc-toggle" id="category-block">
                            <label>Category</label>
                            <select id="category" class="form-control">
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group disc-toggle" id="item-block">
                            <label>Item</label>
                            <div class="row no-gutters">
                                <div class="col-10">
                                    <input id="item_name" type="text" class="form-control"
                                        style="border-radius: 4px 0 0 4px!important" placeholder="select item" readonly>
                                    <input id="item_id" type="hidden" class="form-control">
                                </div>
                                <div class="col-2"><button class="btn btn-primary"
                                        style="border-radius: 0 4px 4px 0!important" data-toggle="modal"
                                        data-target="#pilih-item">Browse</button></div>
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
                                                            <button
                                                                onclick="select_item('{{ $item->id }}', '{{ $item->name }}')"
                                                                class="btn btn-sm btn-primary rounded-pill"><i
                                                                    data-feather='check'></i></button>
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
                            <label>Value</label>
                            <input id="value" type="number" class="form-control">
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
