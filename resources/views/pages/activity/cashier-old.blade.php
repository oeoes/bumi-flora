@extends('layouts.master')

@section('page-title', 'Kasir')
@section('page-description', 'Pembayaran untuk pembelian pelanggan.')


@section('custom-js')
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('#kasir').DataTable();
        $('#cashback').text('Rp.0')

        print_items();
        print_total();
        print_actual_price();
        print_cashback();

        $("input[name='payment_method']").change(function () {
            if ($(this).val() != 'cash') {
                $('#tunai').prop('disabled', true)
                $('#cashback').text('-')
            }else{
                $('#tunai').prop('disabled', false)
                $('#cashback').text('Rp.0')
            }
            
        });

        
        
    });

    function print_cashback () {
        let total = 0
        let items = JSON.parse(localStorage.getItem('items'))

        if (items != null) {
        for (let i = 0; i < items.length; i++) { total=total + (parseInt(items[i][3]) * parseInt(items[i][2])) } }

        $('#tunai').keyup(function () {
            let cashback = $('#tunai').val() - (total - $('#discount').val())
            if($('#tunai').val() != '') {
                $('#cashback').text('Rp.'+parseInt(cashback).toLocaleString())
                if(cashback < 0) {$('#cashback').addClass('text-danger')} else {$('#cashback').removeClass('text-danger')}
            }else {
                $('#cashback').text('Rp.0')
            }
            
        })
    }

    function print_actual_price () {
        let total = 0
        let items = JSON.parse(localStorage.getItem('items'))

        if (items != null) {
        for (let i = 0; i < items.length; i++) { total=total + (parseInt(items[i][3]) * parseInt(items[i][2])) } }


        $('#discount').keyup(function (){
            if($('#discount').val() != '') {
                $('#actual_price').text('Rp.'+parseInt(total).toLocaleString())
            }else {
                $('#actual_price').text('')
            }
            print_total();

            if($('#discount').val() == '') {
                 $('#actual_price').text('')
            }
        })
    }

    function print_items() {
        let items = JSON.parse(localStorage.getItem('items'))

        if (items == null) {
            $('#data-item').append(
                '<tr id="none"><td class="text-muted" colspan="4" align="center">Belum ada item ditambahkan</td></tr>'
                )
        } else {
            for (let i = 0; i < items.length; i++) {
                $('#data-item').append(
                    '<tr style="border-bottom: 1px dashed rgb(173, 173, 173)"><td><span onclick="remove_item(\'' +
                    items[i][0] + '\')" class="badge badge-danger" style="cursor: pointer">x</span></td><td>' +
                    items[i][1] + '</td><td><span class="badge badge-light" style="cursor: pointer"></span>' +
                    items[i][2] + '</td><td>Rp.' + parseInt(items[i][3] * items[i][2]).toLocaleString() +
                    '</td><td><span onclick="decrease_item(\'' + items[i][0] +
                    '\')" class="badge badge-primary mr-1" style="cursor: pointer; width: 20px">-</span><span onclick="add_item(\'' +
                    items[i][0] +
                    '\')" class="badge badge-primary mr-1" style="cursor: pointer; width: 20px">+</span></td></tr>')

            }
        }
    }

    function print_total() {
        let total = 0
        let items = JSON.parse(localStorage.getItem('items'))

        if (items != null) {
            for (let i = 0; i < items.length; i++) {
                total = total + (parseInt(items[i][3]) * parseInt(items[i][2]))
            }
        }

        if($('#discount').val() == '') {
            $('#total_bill').text(parseInt(total).toLocaleString())
            $('#actual_price').text('')
        } else {
            $('#total_bill').text( (parseInt(total) - $('#discount').val()).toLocaleString() )
            $('#actual_price').text('Rp.'+parseInt(total).toLocaleString())
        }

        $('#total_price').text(parseInt(total).toLocaleString())
    }

    function remove_item(id) {
        let items = JSON.parse(localStorage.getItem('items'))

        $(document).on('click', 'tr td span', function () {
            $(this).closest('tr').remove()

        })

        for (let i = 0; i < items.length; i++) {
            if (id == items[i][0]) {
                items.splice(i, 1)
            }
        }

        if (items.length < 1) {
            localStorage.removeItem('items');
            $('#data-item').append(
                '<tr id="none"><td class="text-muted" colspan="5" align="center">Belum ada item ditambahkan</td></tr>'
                )
        } else {
            localStorage.setItem('items', JSON.stringify(items));
        }

        print_total();

    }

    function get_id(id, item, qty, price) {
        $('#none').remove()
        let state = true
        let items = []

        // kalau local storage ada isinya, array items diisi sama data dilocalstorage
        if (localStorage.getItem("items") !== null) {
            items = JSON.parse(localStorage.getItem('items'))
        }

        // kalau ada item yg sama, gausah disimpen ke localstorage, cukup tambah qty nya aja
        if (items.length > 0) {
            for (let i = 0; i < items.length; i++) {
                if (id == items[i][0]) {
                    state = false
                    amount = items[i][2] + 1

                    items[i][2] = amount
                }
            }
            localStorage.setItem('items', JSON.stringify(items));

            // clear dulu element nya untuk update Qty
            $('#data-item tr').remove()
            // baru print lagi
            print_items();
        }

        if (state) {
            items.push([id, item, qty, price])
            localStorage.setItem('items', JSON.stringify(items));

            $('#data-item').append(
                '<tr style="border-bottom: 1px dashed rgb(173, 173, 173)"><td><span onclick="remove_item(\'' + id +
                '\')" class="badge badge-danger" style="cursor: pointer">x</span></td><td>' + item + '</td><td>' +
                qty + '</td><td>Rp.' + parseInt(price * qty).toLocaleString() +
                '</td><td><span onclick="decrease_item(\'' + id +
                '\')" class="badge badge-primary mr-1" style="cursor: pointer; width: 20px">-</span><span onclick="add_item(\'' +
                id + '\')" class="badge badge-primary mr-1" style="cursor: pointer; width: 20px">+</span></td></tr>'
                )
        }

        print_total();
    }

    function add_item(id) {
        items = JSON.parse(localStorage.getItem('items'))

        for (let i = 0; i < items.length; i++) {
            if (id == items[i][0]) {
                amount = items[i][2] + 1
                items[i][2] = amount
                break
            }
        }
        localStorage.setItem('items', JSON.stringify(items));

        // clear dulu element nya untuk update Qty
        $('#data-item tr').remove()
        // baru print lagi
        print_items();
        print_total();
    }

    function decrease_item(id) {
        items = JSON.parse(localStorage.getItem('items'))

        for (let i = 0; i < items.length; i++) {
            if (id == items[i][0]) {
                if (items[i][2] - 1 == 0) {
                    alert('Minimum item tidak boleh 0')
                } else {
                    amount = items[i][2] - 1
                    items[i][2] = amount
                }
            }
        }
        localStorage.setItem('items', JSON.stringify(items));

        // clear dulu element nya untuk update Qty
        $('#data-item tr').remove()
        // baru print lagi
        print_items();
        print_total();
    }

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

</style>
@endsection

@section('content')
<div class="page-content page-container" id="page-content">
    <div class="padding">
        <div class="row">
            <div class="col-md-7">
                <div class="table-responsive">
                    <table id="kasir" class="table my-responsive table-theme v-middle table-hover">
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
                                    <span
                                        onclick="get_id('{{ $item->id }}', '{{ $item->name }}', 1, '{{ $item->price }}')"
                                        style="cursor: pointer"
                                        class="btn btn-sm rounded-pill pl-1 btn-outline-primary btn-block"><i
                                            data-feather='shopping-cart'></i></span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-5">
                <div class="card mt-3">
                    <div class="card-header">
                        <div class="h3">Payment</div>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless table-responsive">
                            <thead>
                                <tr>
                                    <th class="text-left text-sm"><span>#</span></th>
                                    <th class="text-left text-sm"><span>Item</span></th>
                                    <th class="text-right text-sm"><span>Qty</span></th>
                                    <th class="text-right text-sm"><span>Price</span></th>
                                </tr>
                            </thead>
                            <tbody id="data-item">
                                <!-- data generated by js will be here -->
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <div class="row mb-3">
                            <div class="col-3">
                                <div class="h6">Total</div>
                            </div>
                            <div class="col-9">
                                <div class="h2 text-right">Rp.<span id="total_price"></span></div>
                            </div>
                        </div>

                        <div class="row mb-3">

                            <div class="col-3">
                                <div class="h6">Method</div>
                            </div>
                            <div class="col-9 text-right">
                                <form>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="payment_method"
                                            value="cash" checked>
                                        <label class="form-check-label" for="cash">Cash</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="payment_method"
                                            value="debit">
                                        <label class="form-check-label" for="debit">Debit</label>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-7">
                                <div class="h6">Discount</div>
                            </div>
                            <div class="col-5">
                                <input id="discount" type="number" class="form-control form-control-sm">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-3">
                                <div class="h6">Total Bill</div>
                            </div>
                            <div class="col-9">
                                <div class="h3 text-right"> <strike><small><span id="actual_price"></span></small></strike>
                                    Rp.<span id="total_bill">0</span></div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-7">
                                <div class="h6 text-left">Tunai</div>
                            </div>
                            <div class="col-5">
                                <input id="tunai" type="text" class="form-control form-control-sm">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-3">
                                <div class="h6">Cashback</div>
                            </div>
                            <div class="col-9">
                                <div class="h4 text-right" id="cashback"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
