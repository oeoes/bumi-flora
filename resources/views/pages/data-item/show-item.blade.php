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
                <div class="card sticky" style="z-index: 1; visibility: visible; transform: none; opacity: 1; transition: ease 1s ease 0s;">
                    <img style="max-width: 100%" src="{{ $item->image }}" alt="">
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="h2"><strong>{{ $item->name }}</strong></div>
                        <div class="h5">Rp.{{ number_format($item->price, 2) }}</div>
                        <table class="table table-theme v-middle table-hover">
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