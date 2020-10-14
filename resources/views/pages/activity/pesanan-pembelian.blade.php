@extends('layouts.master')

@section('page-title', 'Buat Pesanan Pembelian')
@section('page-description', 'Halaman membuat pesanan pembelian.')

@section('btn-custom')
<div>
    <button onclick="window.history.back()" class="btn btn-sm text-muted">
        <i data-feather="arrow-left"></i>
        <span class="d-none d-sm-inline mx-1">Back</span>
    </button>
</div>
@endsection

@section('content')
<div class="page-content page-container" id="page-content">
    <div class="padding">
        <div class="row">            
            <div class="col-md-6">
                <div class="card p-4 sticky" style="z-index: 1; visibility: visible; transform: none; opacity: 1; transition: ease 1s ease 0s;">
                    <form method="post" action="{{ route('orders.store') }}">
                        @csrf
                        <div class="form-group">
                            <label class="text-muted">Produk</label>
                            <input type="text" class="form-control" placeholder="item name" disabled value="{{ $item->name }}">
                            <input type="hidden" name="item_id" class="form-control" placeholder="item name" value="{{ $item->id }}">
                        </div>
                        <div class="form-group">
                            <label class="text-muted" for="stake_holder">Supplier</label>
                            <select name="stake_holder_id" id="stake_holder" class="form-control" required>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="text-muted" for="amount">Jumlah Pesan *</label>
                            <input type="number" name="amount" class="form-control" id="amount" placeholder="jumlah pesan" required>
                        </div>
                        <div class="form-group">
                            <label class="text-muted" for="shipping_date">Tanggal Kirim *</label>
                            <input type="date" name="shipping_date" class="form-control" id="shipping_date" required>
                        </div>
                        <div class="form-group">
                            <label class="text-muted" for="description">Deskripsi</label>
                            <textarea class="form-control" name="description" id="description" cols="3" rows="5"></textarea>
                        </div>
                        <div class="text-muted mb-2"><small>* ) important field</small></div>
                        <button type="submit" class="btn btn-primary">Add Item</button>
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <img style="max-width: 100%" src="{{ $item->image }}" alt="">
                        <div class="h2"><strong>{{ $item->name }}</strong></div>
                        <div class="h5">Rp.{{ number_format($item->price, 2) }}</div>
                        <table class="table table-theme v-middle table-hover table-responsive">
                            <tr>
                                <th>Item</th>
                                <td>{{ $item->name }}</td>
                            </tr>
                            <tr>
                                <th>Code</th>
                                <td>{{ $item->code }}</td>
                            </tr>
                            <tr>
                                <th>Type</th>
                                <td>{{ strtoupper($item->type) }}</td>
                            </tr>
                            <tr>
                                <th>Category</th>
                                <td>{{ $item->category }}</td>
                            </tr>
                            <tr>
                                <th>Unit type</th>
                                <td>{{ $item->unit }}</td>
                            </tr>
                            <tr>
                                <th>Cabinet</th>
                                <td>{{ $item->cabinet }}</td>
                            </tr>
                            <tr>
                                <th>Sale status</th>
                                @if($item->sale_status == 1)
                                <td>Masih Dijual</td>
                                @else
                                <td>Tidak Dijual</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Cost</th>
                                <td>Rp.{{ number_format($item->main_cost, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Price</th>
                                <td>Rp.{{ number_format($item->price, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Minimum stock</th>
                                <td>{{ $item->min_stock }}</td>
                            </tr>
                            <tr>
                                <th>Description</th>
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