function store_transaction(e) {
    $(e).text('Simpan & Bayar...')
    $('#payment').modal('toggle');
    $('#printing_receipt').modal('toggle');

    let items = JSON.parse(localStorage.getItem('items'))
    let item_data = [];

    if (items != null) {
        for (let i = 0; i < items.length; i++) {
            let price = 0;
            // kalau grosir dan discount ketriger
            if ((items[i][9] > 0 && items[i][4] >= items[i][9]) && items[i][7] > 0) {
                // harga grosir di potong discount
                price = parseInt(items[i][10]) - ((parseInt(items[i][10]) * parseInt(items[i][7])) / 100);
            } else if (items[i][7] > 0) {
                price = items[i][5];
            } else if (items[i][9] > 0 && items[i][4] >= items[i][9]) {
                price = items[i][10];
            } else {
                price = items[i][6];
            }
            // item_id, qty, item_name, price, satuan, discount item, grosiitem
            item_data.push([items[i][0], items[i][4], items[i][1], price, items[i][3], items[i][7], items[i][9]])
        }
    }

    axios({
        method: 'post',
        url: '/cashier/store',
        responseType: 'arraybuffer',
        data: {
            items: item_data,
            payment_type: localStorage.getItem('payment_type'),
            discount: parseInt(localStorage.getItem('discount')),
            customer_discount: parseInt(localStorage.getItem('customer_discount')),
            customer: $('#customer').val(),
            additional_fee: parseInt($('#additional_fee').val()),
            tax: parseInt(localStorage.getItem('tax')),
            dept: $('#dept').val(),
            nominal: parseInt($('#nominal').val()),
            transaction_id: $('#id-edit-transaction').val() ? $('#id-edit-transaction').val() : ''
        }
    }).then(function (response) {
        // download file
        let blob = new Blob([response.data], {
            type: "application/pdf"
        });
        let link = document.createElement("a");
        link.href = window.URL.createObjectURL(blob);
        link.download = `Transaksi.pdf`;
        document.body.appendChild(link);
        link.click();

        localStorage.removeItem('items')
        localStorage.removeItem('total_price')
        localStorage.removeItem('payment_type')
        localStorage.setItem('discount', 0)
        localStorage.setItem('additional_fee', 0)
        localStorage.setItem('tax', 0)

        $('#data-item tr').remove()
        $('#discount_value').val(0)
        $('#additional_fee').val(0)
        $('#tax').val(0)
        print_items()
        print_accumulate()
        print_total_price();

        // if (response.data.status == true) {
        //     localStorage.removeItem('items')
        //     localStorage.removeItem('total_price')
        //     localStorage.removeItem('payment_type')
        //     localStorage.setItem('discount', 0)
        //     localStorage.setItem('additional_fee', 0)
        //     localStorage.setItem('tax', 0)

        //     $('#data-item tr').remove()
        //     $('#discount_value').val(0)
        //     $('#additional_fee').val(0)
        //     $('#tax').val(0)
        //     print_items()
        //     print_accumulate()
        //     print_total_price();
        // }
    }).catch(function (error) {
        console.log(error.response)
    }).finally(function (e) {
        $('#printing_receipt').modal('toggle');
        location.reload()

        $(e).text('Simpan & Bayar')
        $('#payment').modal('toggle')
        $('#item_code').val('');
        $('#item_code').focus();

        localStorage.removeItem('items')
        localStorage.removeItem('total_price')
        localStorage.removeItem('payment_type')
        localStorage.setItem('discount', 0)
        localStorage.setItem('additional_fee', 0)
        localStorage.setItem('tax', 0)

        $('#data-item tr').remove()
        $('#discount_value').val(0)
        $('#additional_fee').val(0)
        $('#tax').val(0)
        print_items()
        print_accumulate()
        print_total_price()

        // interface payment modal
        $('#save_n_pay').prop('disabled', true)

        $('#nominal_block').addClass('payment_toggle')
        $('#ewallet_block').addClass('payment_toggle')
        $('#debit_block').addClass('payment_toggle')
        $('#transfer_block').addClass('payment_toggle')
        localStorage.setItem('payment_type', '')
    })
}

$(document).ready(function () {

    /** Enable payment method cash on startup */
    $("#payment").on('shown.bs.modal', function () {
        $(this).find('#nominal').select().focus();
    });
    $("#pending_payment").on('shown.bs.modal', function () {
        $(this).find('#pending_description').select().focus();
    });
    $("#cancle_payment").on('shown.bs.modal', function () {
        $(this).find('#reset_transaction').select().focus();
    });

    // tekan enter untuk simpan dan bayar
    $(document).on('keypress', '#payment', (function (e) {
        if (e.which == 13) {
            if ($('#payment_option').val() == 1) {
                if (localStorage.getItem('total_price') > 0 && parseInt($('#nominal').val()) < localStorage.getItem('total_price')) {
                    alert('Total bayar kurang dari total tagihan.')
                } else if (parseInt($('#nominal').val()) >= localStorage.getItem('total_price')) {
                    store_transaction()
                    $('#save_n_pay').text('Simpan & Bayar...')
                } else {
                    alert('Masukan nominal pembayaran')
                }
            } else {
                if (localStorage.getItem('payment_type') == 'null' || localStorage.getItem('payment_type') == 1) {
                    alert('Pilih metode pembayaran.')
                } else {
                    if (localStorage.getItem('total_price') < 1) {
                        alert('No Transaction.')
                    } else {
                        store_transaction()
                        $('#save_n_pay').text('Simpan & Bayar...')
                    }

                }
            }
        }
    }))

    $('#payment_types').children().remove()
    // kosongin field nominal
    $('#nominal').val('')
    // disable button pay
    $('#save_n_pay').prop('disabled', true)

    $('#payment_types').append(
        `<div class="input-group"> 
            <div class="input-group-prepend">
                <span class="input-group-text" id="inputGroupPrepend">Rp</span> 
            </div>
            <input type="number" min="0" class="form-control" id="nominal" placeholder="Nominal" aria-describedby="inputGroupPrepend">
        </div>`
    )

    /**  */
    let payment_types = [];

    axios.get(`/app/payments/${$('#payment_option').val()}`)
        .then((response) => {
            response.data.data.forEach((element) => {
                payment_types.push([element.payment_type_id, element.type_name])
            })
        })

    $('#nominal').keyup(function () {
        let total_price = localStorage.getItem('total_price')
        let result = $('#nominal').val() - total_price
        $('#cashback').text(result.toLocaleString())

        if (result < 0) {
            $('#cashback').addClass('text-danger')
            $('#cashback').removeClass('text-success')
            $('#save_n_pay').prop('disabled', true)
        } else {
            $('#cashback').addClass('text-success')
            $('#cashback').removeClass('text-danger')
            // enable button pay
            $('#save_n_pay').prop('disabled', false)
        }

        // payment type in localstorage
        localStorage.setItem('payment_type', payment_types[0][0])
    })
    /** sampe sini */

    $(document).on('change', '#payment_option', (function () {
        localStorage.setItem('payment_type', null); // set payment type to null
        let method = '';
        let types = [];

        axios.get(`/app/payments/${$('#payment_option').val()}`)
            .then((response) => {
                response.data.data.forEach((element) => {
                    method = element.method_name.toLowerCase()
                    types.push([element.payment_type_id, element.type_name])
                })
                if (method == 'cash' || method == 'tunai') {
                    $('#payment_types').children().remove()
                    // kosongin field nominal
                    $('#nominal').val('')
                    // disable button pay
                    $('#save_n_pay').prop('disabled', true)

                    $('#payment_types').append(
                        `<div class="input-group"> 
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroupPrepend">Rp</span> 
                            </div>
                            <input type="number" min="0" class="form-control" id="nominal" placeholder="Nominal" aria-describedby="inputGroupPrepend">
                        </div>`
                    )

                    $('#nominal').keyup(function () {
                        let total_price = localStorage.getItem('total_price')
                        let result = $('#nominal').val() - total_price
                        $('#cashback').text(result.toLocaleString())

                        if (result < 0) {
                            $('#cashback').addClass('text-danger')
                            $('#cashback').removeClass('text-success')
                            $('#save_n_pay').prop('disabled', true)
                        } else {
                            $('#cashback').addClass('text-success')
                            $('#cashback').removeClass('text-danger')
                            // enable button pay
                            $('#save_n_pay').prop('disabled', false)
                        }

                        // payment type in localstorage
                        localStorage.setItem('payment_type', types[0][0])
                    })
                } else {
                    $('#payment_types').children().remove()
                    $('#save_n_pay').prop('disabled', true)
                    $('#cashback').text('-')

                    types.forEach((element) => {
                        $('#payment_types').append(
                            `<div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="payment_type" id="${element[1].toLowerCase().split("_")}"
                                value="${element[0]}">
                            <label class="form-check-label" for="${element[1].toLowerCase().split("_")}">${element[1]}</label>
                        </div>`
                        )
                    })

                    $("input[name='payment_type']").change(function () {
                        localStorage.setItem('payment_type', $(this).val())

                        // enable button pay
                        $('#save_n_pay').prop('disabled', false)
                    })
                }
            })
            .finally(function () {
                //  
            })
    }))
})
