@extends('layouts.master')

@section('page-title', 'Transaksi Online')
@section('page-description', 'Histori transaksi online.')


@section('custom-js')
<script src="{{ asset('libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('js/axios.js') }}"></script>
<script>
    $(document).ready(function () {
        let dept = 'ecommerce'
        $('#view-transaction').prop('disabled', true)

        // view data transaction
        axios.get(`/app/records/item/transaction/history/${dept}`)
            .then(function (response) {
                if (!response.data.status) {
                    $('#online-data').append(`
                    <tr class=" " data-index="0" data-id="17">
                        <td colspan="7" align="center">Data transaksi tidak ditemukan</td></tr
                    </tr>
                `)
                } else {
                    response.data.data.forEach(data => {
                        $('#online-data').append(`
                    <tr class=" " data-index="0" data-id="17">
                        <td style="">
                            <div class="text-muted text-sm">
                                <a
                                    href="/app/records/item/transaction/detail/${data.id}/${dept}">${data.transaction_number}</a>
                            </div>
                        </td>
                        <td style="">
                            <span class="item-amount d-none d-sm-block text-sm ">
                                ${data.quantity}
                            </span>
                        </td>
                        <td style="">
                            <span class="item-amount d-none d-sm-block text-sm ">
                                ${data.method_name}
                            </span>
                        </td>
                        <td style="">
                            <span class="item-amount d-none d-sm-block text-sm ">
                                ${data.type_name}
                            </span>
                        </td>
                        <td style="">
                            <span class="item-amount d-none d-sm-block text-sm ">
                                ${data.customer == null ? "Umum" : data.customer}
                            </span>
                        </td>
                        <td style="">
                            <span class="item-amount d-none d-sm-block text-sm ">
                                ${data.created_at}
                            </span>
                        </td>
                        <td style="">
                            <span class="item-amount d-none d-sm-block text-sm ">
                                ${data.transaction_time}
                            </span>
                        </td>
                    </tr>
                    `)
                    })
                }

            })

        $(document).on('change', '#from', function () {
            if (!$('#to').val()) {
                $('#view-transaction').prop('disabled', true)
            } else {
                $('#view-transaction').prop('disabled', false)
            }
        })

        $(document).on('change', '#to', function () {
            if (!$('#from').val()) {
                $('#view-transaction').prop('disabled', true)
            } else {
                $('#view-transaction').prop('disabled', false)
            }
        })


        $(document).on('click', '#view-transaction', function () {
            $('#online-data').children().remove()

            axios.get(
                    `/app/records/item/transaction/filter/${dept}/${$('#from').val()}/${$('#to').val()}`
                )
                .then(function (response) {

                    if (!response.data.status) {
                        $('#online-data').append(`
                            <tr class=" " data-index="0" data-id="17">
                                <td colspan="7" align="center">Data transaksi tidak ditemukan</td>
                            </tr </tr> 
                        `)
                    } else {
                        response.data.data.forEach(data => {
                            $('#online-data').append(`
                                <tr class=" " data-index="0" data-id="17">
                                    <td style="">
                                        <div class="text-muted text-sm">
                                            <a
                                                href="/app/records/item/transaction/detail/${data.id}/${dept}">${data.transaction_number}</a>
                                        </div>
                                    </td>
                                    <td style="">
                                        <span class="item-amount d-none d-sm-block text-sm ">
                                            ${data.quantity}
                                        </span>
                                    </td>
                                    <td style="">
                                        <span class="item-amount d-none d-sm-block text-sm ">
                                            ${data.method_name}
                                        </span>
                                    </td>
                                    <td style="">
                                        <span class="item-amount d-none d-sm-block text-sm ">
                                            ${data.type_name}
                                        </span>
                                    </td>
                                    <td style="">
                                        <span class="item-amount d-none d-sm-block text-sm ">
                                            ${data.customer == null ? "Umum" : data.customer}
                                        </span>
                                    </td>
                                    <td style="">
                                        <span class="item-amount d-none d-sm-block text-sm ">
                                            ${data.created_at}
                                        </span>
                                    </td>
                                    <td style="">
                                        <span class="item-amount d-none d-sm-block text-sm ">
                                            ${data.transaction_time}
                                        </span>
                                    </td>
                                </tr>
                            `)
                        })
                    }
                })
        })
    });

</script>
@endsection

@section('custom-css')
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
            <div class="col-md-12 mb-5">
                <div class="row">
                    <div class="col-md-3">
                        <label>Dari</label>
                        <input id="from" type="date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>Hingga</label>
                        <input id="to" type="date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <button id="view-transaction"
                            class="btn btn-outline-primary btn-sm rounded-pill pr-4 pl-4 mt-4">View</button>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-md-12">
                <div class="bootstrap-table">
                    <div class="fixed-table-container" style="padding-bottom: 0px;">
                        <div class="fixed-table-body">
                            <table class="table my-responsive table-theme v-middle table-hover"
                                style="margin-top: 0px;">
                                <thead style="">
                                    <tr>
                                        <th style="" data-field="type">
                                            <div class="th-inner">No. Transaksi</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th style="" data-field="itemtype">
                                            <div class="th-inner">Quantity</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th style="" data-field="itemtype">
                                            <div class="th-inner">Payment Method</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th style="" data-field="itemtype">
                                            <div class="th-inner">Payment Type</div>
                                            <div class="fht-cell"></div>
                                        </th>

                                        <th style="" data-field="itemtype">
                                            <div class="th-inner">Customer</div>
                                            <div class="fht-cell"></div>
                                        </th>

                                        <th style="" data-field="itemtype">
                                            <div class="th-inner">Date</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                        <th style="" data-field="itemtype">
                                            <div class="th-inner">Time</div>
                                            <div class="fht-cell"></div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="online-data">

                                </tbody>
                            </table>
                        </div>
                        <div class="fixed-table-footer" style="display: none;">
                            <table>
                                <tbody>
                                    <tr></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </div>


        <div class="clearfix"></div>
    </div>
</div>
@endsection
