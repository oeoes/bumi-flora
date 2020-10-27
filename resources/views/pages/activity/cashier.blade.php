@extends('layouts.master')

@section('page-title', 'Kasir')
@section('page-description', 'Payment corner.')

@section('btn-custom')
<div>
    <button class="btn btn-sm text-muted" data-toggle="modal" data-target="#pending-list" data-toggle-class="modal-open-aside">
        <span class="d-none d-sm-inline mx-1">Daftar Pending</span>
        <i data-feather="shopping-cart"></i>
    </button>

    <!-- modal aside right -->
    <div id="pending-list" class="modal fade modal-open-aside" da
    ta-backdrop="true" data-class="modal-open-aside" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-right w-xl">
            <div class="modal-content h-100 no-radius">
                <div class="modal-header ">
                    <div class="modal-title text-sm">Daftar pending</div>
                    <button class="close" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <div class="p-2">
                        <table class="table my-responsive table-theme v-middle table-hover">
                            <thead>
                                <tr>
                                    <th><span class="text-muted">Keterangan</span></th>
                                    <th><span class="text-muted">Restore</span></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="pending_transaction_list">
                                <!-- generated data item -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>
</div>
@endsection

@section('custom-js')
<script src="{{ asset('js/dataTables.js') }}"></script>
<script src="{{ asset('js/axios.js') }}"></script>
<script src="{{ asset('js/cashier.js') }}"></script>
<script src="{{ asset('js/payment.js') }}"></script>
<script src="{{ asset('js/pending.js') }}"></script>
<script>
    $(document).ready(function () {
        $('#kasir-data-item').DataTable();
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

    .payment_toggle {
        display: none
    }

</style>
@endsection

@section('content')
<div class="page-content page-container" id="page-content">
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="card p-3">
                    <div class="row">
                        <div class="col-6">
                            <div class="h4 pt-4">Keranjang</div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label for="jumlah">Jumlah</label>
                                <input id="jumlah" type="number" class="form-control form-control-sm"
                                    placeholder="jumah item" value="1">
                            </div>
                        </div>
                        <div class="col-4">
                            <label for="jumlah">Kode item</label>
                            <input id="item_code" type="text" class="form-control form-control-sm" autofocus
                                placeholder="kode item">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="kasir" class="table my-responsive table-theme v-middle table-hover">
                            <thead>
                                <tr>
                                    <th><span class="text-muted">Item</span></th>
                                    <th><span class="text-muted">Barcode</span></th>
                                    <th><span class="text-muted">Satuan</span></th>
                                    <th><span class="text-muted">Jumlah</span></th>
                                    <th><span class="text-muted">Harga</span></th>
                                    <th><span class="text-muted">Total</span></th>
                                    <th><span class="text-muted">Action</span></th>
                                </tr>
                            </thead>
                            <tbody id="data-item">
                                <!-- generated data item -->
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-4 offset-md-8">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Satuan diskon</label>
                                            <select id="discount_type" class="form-control form-control-sm">
                                                <option value="nominal">Nominal</option>
                                                <option value="persentase">Persentase</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Besaran</label>
                                            <input min="0" id="discount_value" type="number"
                                                class="form-control form-control-sm" value="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="h2 text-right">Rp. <span id="total_price"></span></div>
                        <div id="cont_discount">
                            <div class="h4 text-right text-success">-<span id="desired_discount_value"></span></div>
                            <div class="h1 text-right">Rp. <span id="final_price"></span></div>
                        </div>

                        <button class="btn btn-sm btn-outline-danger rounded-pill pr-4 pl-4" data-toggle="modal"
                            data-target="#cancle_payment">Reset</button>
                        <button class="btn btn-sm btn-outline-primary rounded-pill pr-4 pl-4" data-toggle="modal"
                            data-target="#payment">Bayar</button>
                        <button class="btn btn-sm btn-outline-secondary rounded-pill pr-4 pl-4" data-toggle="modal"
                            data-target="#pending_payment">Pending</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- modal payment -->
<div class="modal fade" id="payment" tabindex="-1" role="dialog" aria-labelledby="paymentLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentLabel">Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="h1 text-right mb-4">Rp. <span id="bill"></span></div>
                <div class="row">
                    <div class="col-6">
                        <label>Metode Bayar</label>
                    </div>
                    <div class="col-6" id="payment_method_parent">
                        <div class="form-group">
                            <select id="payment_option" class="form-control">
                                <option>Pilih metode pembayaran</option>
                                <option value="tunai">Tunai</option>
                                <option value="ewallet">Ewallet</option>
                                <option value="debit">Debit</option>
                                <option value="transfer">Bank Transfer</option>
                            </select>
                        </div>

                        <!-- untuk nominal -->
                        <div id="nominal_block" class="form-group payment_toggle">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="inputGroupPrepend">Rp</span>
                                </div>
                                <input type="number" min="0" class="form-control" id="nominal"
                                    placeholder="Nominal" aria-describedby="inputGroupPrepend">
                            </div>
                        </div>

                        <!-- untuk ewallet -->
                        <div id="ewallet_block" class="form-group payment_toggle">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="ewallet_method" id="ovo"
                                    value="ovo">
                                <label class="form-check-label" for="ovo">OVO</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="ewallet_method" id="gopay"
                                    value="gopay">
                                <label class="form-check-label" for="gopay">GOPAY</label>
                            </div>
                        </div>

                        <!-- untuk debit -->
                        <div id="debit_block" class="form-group payment_toggle">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="debit_method" id="bca"
                                    value="bca">
                                <label class="form-check-label" for="bca">BCA</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="debit_method" id="mandiri"
                                    value="mandiri">
                                <label class="form-check-label" for="mandiri">Mandiri</label>
                            </div>
                        </div>

                        <!-- untuk bank transfer -->
                        <div id="transfer_block" class="form-group payment_toggle">
                            <input type="text" class="form-control" readonly value="mandiri">
                        </div>

                    </div>

                    <div class="col-6">
                        <label>Uang Kembali</label>
                    </div>
                    <div class="col-6">
                        <div class="h3 text-right"><span id="cashback"></span></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm rounded-pill pr-4 pl-4 btn-outline-secondary" data-dismiss="modal">Tutup</button>
                <button id="save_n_pay" type="button" onClick="store_transaction(this)" class="btn btn-sm rounded-pill pr-4 pl-4 btn-outline-primary" disabled>Simpan & Bayar</button>
            </div>
        </div>
    </div>
</div>

<!-- modal pending payment -->
<div class="modal fade" id="pending_payment" tabindex="-1" role="dialog" aria-labelledby="paymentLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentLabel">Pending transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Transaksi akan dipending?
                <input id="pending_description" type="text" class="form-control" placeholder="Masukan keterangan">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm rounded-pill pr-4 pl-4 btn-outline-secondary"
                    data-dismiss="modal">Batal</button>
                <button id="pending_transaction" type="button"
                    class="btn btn-sm rounded-pill pr-4 pl-4 btn-outline-primary">Ya</button>
            </div>
        </div>
    </div>
</div>

<!-- modal warning restore transaction -->
<div class="modal fade" id="warning_pending_restore" tabindex="-1" role="dialog" aria-labelledby="paymentLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-warning" id="paymentLabel">Warning!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Tidak dapat mengembalikan transaksi pending, masih ada transaksi yang sedang berlangsung.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm rounded-pill pr-4 pl-4 btn-outline-secondary"
                    data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- modal cancle payment -->
<div class="modal fade" id="cancle_payment" tabindex="-1" role="dialog" aria-labelledby="paymentLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentLabel">Batalkan transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Transaksi sekarang akan direset, yakin membuat transaksi baru?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm rounded-pill pr-4 pl-4 btn-outline-secondary"
                    data-dismiss="modal">Batal</button>
                <button id="reset_transaction" type="button"
                    class="btn btn-sm rounded-pill pr-4 pl-4 btn-outline-primary">Ya</button>
            </div>
        </div>
    </div>
</div>

<!-- modal search item -->
<div id="search-item" class="modal fade" data-backdrop="true" aria-modal="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content ">
            <div class="modal-header ">
                <div class="modal-title text-md">Search Item</div>
                <button class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <table id="kasir-data-item" class="table my-responsive table-theme v-middle table-hover">
                    <thead>
                        <tr>
                            <th><span class="text-muted">Item</span></th>
                            <th><span class="text-muted">Barcode</span></th>
                            <th><span class="text-muted">Satuan</span></th>
                            <th><span class="text-muted">Harga</span></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $key => $item)
                        <tr>
                            <td style="">
                                <div class="text-sm">
                                    {{ $item->name }}
                                </div>
                            </td>
                            <td style="">
                                <div class="text-sm">
                                    {{ $item->barcode }}
                                </div>
                            </td>
                            <td style="">
                                <div class="text-sm">
                                    {{ $item->unit }}
                                </div>
                            </td>
                            <td style="">
                                <div class="text-sm">
                                    Rp.{{ number_format($item->price) }}
                                </div>
                            </td>
                            <td>
                                <span onclick="get_id('{{ $item->id }}', '{{ $item->name }}', '{{ $item->barcode }}',
                                    '{{ $item->unit }}', '{{ $item->price }}')" style="cursor: pointer"
                                    class="btn btn-sm rounded-pill pl-1 btn-outline-primary btn-block"><i
                                        data-feather='plus'></i></span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>
@endsection
