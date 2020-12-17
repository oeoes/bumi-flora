function push_data(id, item, barcode, unit, qty, price, original_price, discount, stock, grosir_min_item, grosir_price, before_grosir_price) {
    let state = true
    let items = []

    // create localstorage/or update
    if (localStorage.getItem("items") !== null) {
        items = JSON.parse(localStorage.getItem('items'))
    }

    // kalau ada item yg sama, gausah disimpen ke localstorage, cukup tambah qty nya aja
    if (items.length > 0) {
        for (let i = 0; i < items.length; i++) {
            if (id == items[i][0]) {
                if (stock - qty < 0) {
                    alert('Stock tidak boleh kurang dari 0')
                } else {
                    items[i][4] = parseInt(items[i][4]) + 1
                    $(`#${id}`).val(items[i][4])
                    // pastikan stock tidak sampe 0 setelah pembelian
                    if (items[i][8] - $(`#${id}`).val() < 0) {
                        alert('Stock tidak boleh kurang dari 0')
                        $(`#${id}`).val(0)
                    }
                    
                    // set kondisi untuk grosir_price
                    if (items[i][9] > 0 && items[i][4] >= items[i][9]) { // min item > 0 dan qty > min item
                        items[i][6] = items[i][10] // originalprice diganti jadi grosir price
                        $(`#grosir_${items[i][0]}`).text(parseInt(items[i][10]).toLocaleString())
                    } else if (items[i][9] > 0 && items[i][4] < items[i][9]) {
                        items[i][6] = items[i][11] // originalprice diganti jadi before_grosir_price
                        $(`#grosir_${items[i][0]}`).text(parseInt(items[i][11]).toLocaleString())
                    }
                }
                state = false
            }
        }
        localStorage.setItem('items', JSON.stringify(items));
    }

    if (state) {
        items.push([id, item, barcode, unit, qty, price, original_price, Number(discount), stock, grosir_min_item, grosir_price, before_grosir_price])
        localStorage.setItem('items', JSON.stringify(items));

        // cetak item ke layar setelah scan barcode
        $('#none').remove()
        $('#data-item tr').remove()
        print_items()
    }

    // print total price everytime data pushed
    print_total_price()
    print_accumulate()

    $('#item_code').val('')
    $('#item_code').focus()
    $('#jumlah').val(1)
}

function print_items() {
    let items = JSON.parse(localStorage.getItem('items'))

    if (items == null) {
        $('#data-item').append(
            '<tr id="none"><td class="text-muted" colspan="7" align="center">Belum ada item ditambahkan</td></tr>'
        )
    } else {
        for (let i = 0; i < items.length; i++) {
            // item grosir
            if (items[i][9] > 0) {
                $('#data-item').append(
                    `<tr><td>${items[i][1]} <span class="badge badge-success badge-sm rounded-pill pt-1 pb-1 pl-2 pr-2">Grosir</span</td><td>${items[i][2]}</td><td>${items[i][3]}</td><td>${items[i][8]}</td><td> <input name="jumlah_item" style="width: 70px" id="${items[i][0]}" type="number" class="form-control form-control-sm" value="${items[i][4]}" ></td><td><div class="input-group"><input name="discount" id="${items[i][0]}" style="width: 30px" type="number" min="0" class="form-control" placeholder="disc." aria-describedby="inputGroupPrepend" value="${items[i][7]}"><div class="input-group-prepend"><span class="input-group-text" id="inputGroupPrepend">%</span> </div></div></td><td>Rp.<span id="grosir_${items[i][0]}">${parseInt(items[i][6]).toLocaleString()}</span></td><td>Rp. <span id="acc_${items[i][0]}" ></span></td><td><span onclick="remove_item(${i})" class="btn btn-sm btn-outline-danger" style="cursor: pointer"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></span></td></tr>`
                ).fadeIn(3000, 'ease')
            } else { // item non grosir
                if(trx_id) {
                    $('#data-item').append(
                    `<tr><td>${items[i][1]}</td><td>${items[i][2]}</td><td>${items[i][3]}</td><td>${items[i][8]}</td><td> <input name="jumlah_item" style="width: 70px" id="${items[i][0]}" type="number" class="form-control form-control-sm" value="${items[i][4]}" ></td><td><div class="input-group"><input name="discount" id="${items[i][0]}" style="width: 30px" type="number" min="0" class="form-control" placeholder="disc." aria-describedby="inputGroupPrepend" value="${items[i][7]}"><div class="input-group-prepend"><span class="input-group-text" id="inputGroupPrepend">%</span> </div></div></td><td>Rp.<span id="grosir_${items[i][0]}">${parseInt(items[i][6]).toLocaleString()}</span></td><td>Rp. <span id="acc_${items[i][0]}" ></span></td></tr>`
                ).fadeIn(3000, 'ease')
                } else {
                    $('#data-item').append(
                    `<tr><td>${items[i][1]}</td><td>${items[i][2]}</td><td>${items[i][3]}</td><td>${items[i][8]}</td><td> <input name="jumlah_item" style="width: 70px" id="${items[i][0]}" type="number" class="form-control form-control-sm" value="${items[i][4]}" ></td><td><div class="input-group"><input name="discount" id="${items[i][0]}" style="width: 30px" type="number" min="0" class="form-control" placeholder="disc." aria-describedby="inputGroupPrepend" value="${items[i][7]}"><div class="input-group-prepend"><span class="input-group-text" id="inputGroupPrepend">%</span> </div></div></td><td>Rp.<span id="grosir_${items[i][0]}">${parseInt(items[i][6]).toLocaleString()}</span></td><td>Rp. <span id="acc_${items[i][0]}" ></span></td></tr>`
                ).fadeIn(3000, 'ease')
                }
            }

        }
    }
}

function print_total_price() {
    let total = 0 + parseInt(localStorage.getItem('additional_fee')) + parseInt(localStorage.getItem('tax'))
    let total_price = total
    let items = JSON.parse(localStorage.getItem('items'))

    if (items != null) {
        for (let i = 0; i < items.length; i++) {
            let pr = parseInt(items[i][4]) * parseInt(items[i][6])
            total = total + (pr - (pr * parseInt(items[i][7]) / 100))
        }
    }

    // perhitungan total price setalh dipotong discount
    if ($('#discount_type').val() == 'nominal') {
        total_price = total - $('#discount_value').val()
        $('#desired_discount_value').text(parseInt($('#discount_value').val()).toLocaleString())
    } else {
        total_price = total - ((total * $('#discount_value').val()) / 100)
        $('#desired_discount_value').text($('#discount_value').val() + '%')
    }
    localStorage.setItem('total_price', total_price);

    $('#total_price').text(total.toLocaleString())
    $('#bill').text(total_price.toLocaleString())
    $('#final_price').text(parseInt(total_price).toLocaleString())
}


function get_id(id, item, barcode, unit, price, original_price, discount, stock, minimum_item, grosir_price) {
    if (id != null) {
        let amount = stock - $('#jumlah').val() < 0 ? 0 : $('#jumlah').val()
        push_data(id, item, barcode, unit, amount, price, original_price, discount, stock, minimum_item, grosir_price, original_price)
    }

    // tutup modal sama clear input search
    $('#kasir-data-item_filter input').val('')
    $('#search-item').modal('hide')

    // location.reload()
}

function print_accumulate() {
    let items = JSON.parse(localStorage.getItem('items'))

    if (items != null) {
        for (let i = 0; i < items.length; i++) {
            if (items[i][9] > 0 && items[i][4] > items[i][9]) { // kalau grosir true dan qty lebih dari grosir
                let pr = parseInt(items[i][10] * items[i][4]) // grosir price * quantity 
                $('#acc_' + items[i][0]).text(parseInt(pr - (pr * items[i][7] / 100)).toLocaleString())
            } else {
                let pr = parseInt(items[i][6] * items[i][4]) // original_price * quantity 
                $('#acc_' + items[i][0]).text(parseInt(pr - (pr * items[i][7] / 100)).toLocaleString())
            }
        }
    }

}

function remove_item(index) {
    let items = JSON.parse(localStorage.getItem('items'))
    items.splice(index, 1)

    localStorage.setItem('items', JSON.stringify(items))

    $('#data-item tr').remove()
    print_items()
    print_total_price()
    print_accumulate()
    $('#item_code').focus()
}


$(document).ready(function () {
    // insert value ke kolom biaya lain    
    localStorage.getItem('additional_fee') == null ? localStorage.setItem('additional_fee', 0) : $('#additional_fee').val(JSON.parse(localStorage.getItem('additional_fee')))
    // insert value ke kolom pajak
    localStorage.getItem('tax') == null ? localStorage.setItem('tax', 0) : $('#tax').val(JSON.parse(localStorage.getItem('tax')))

    // print item yg ada di localstorage
    print_items()
    print_total_price()
    print_accumulate()

    // create discount localstorage
    localStorage.setItem('discount', 0)
    localStorage.setItem('customer_discount', 0)


    // autofocus ke field jumlah dan kode item
    $(document).on('keypress', 'input', (function (e) {
        if (e.which == 13) {
            if ($('#item_code').is(':focus')) {
                $('#jumlah').focus().select()
            } else if ($('#jumlah').is(':focus')) {
                $('#item_code').focus().select()
            }
        }
    }))

    // tekan tombol / untuk melakukan pembayaran
    $(document).on('keypress', 'html', (function (e) {
        if (e.which == 44) {
            $('#cancle_payment').modal('toggle');
            return false
        }else if (e.which == 46) {
            $('#payment').modal('toggle');
            return false
        } else if (e.which == 47) {
            $('#pending_payment').modal('toggle');
            return false
        }
        else if (e.which == 43) {
            $('#item_code').select().focus();
            return false
        }

    }))

    // scan barcode
    $(document).on('input', '#item_code', (function () {
        if ($('#item_code').val().length > 1) {
            axios.get('/cashier/check', {
                    params: {
                        code: $('#item_code').val(),
                        dept: $('#dept').val()
                    }
                })
                .then(function (response) {
                    if (response.data.status == true) {
                        let item = response.data.data
                        let amount = item.stock - $('#jumlah').val() < 0 ? 0 : $('#jumlah').val()
                        if (item.stock > 0) {
                            push_data(item.id, item.name, item.barcode, item.unit, amount, item.price, item.original_price, item.discount, item.stock, item.minimum_item, item.grosir_price, item.original_price)
                        } else {
                            alert('Maaf, stock item sedang kosong.')
                            $('#item_code').select().focus();
                        }
                    } else {
                        $('#search-item').modal('show')
                        $('#kasir-data-item_filter input').focus().val($('#item_code').val())
                    }
                })
        }
    }));

    // field discount diupdate
    $(document).on('keyup', "#data-item input[name='discount']", (function () {
        let items = JSON.parse(localStorage.getItem('items'))

        if (items != null) {
            for (let i = 0; i < items.length; i++) {
                if (items[i][0] == $(this).attr('id')) {
                    items[i][7] = $(this).val() // increase or decrease qty
                }
            }
        }

        localStorage.setItem('items', JSON.stringify(items));

        print_accumulate()
        print_total_price()
    }))

    // field jumlah diupdate
    $(document).on('keyup', "#data-item input[name='jumlah_item']", (function () {
        let items = JSON.parse(localStorage.getItem('items'))

        if (items != null) {
            for (let i = 0; i < items.length; i++) {
                if (items[i][0] == $(this).attr('id')) {
                    if (items[i][8] - $(this).val() < 0) {
                        alert('Stock tidak boleh kurang dari 0')
                        $(this).val(0)
                    } else {
                        items[i][4] = $(this).val() // increase or decrease qty

                        // set kondisi untuk grosir_price
                        if (items[i][9] > 0 && items[i][4] >= items[i][9]) { // min item > 0 dan qty > min item
                            items[i][6] = items[i][10] // originalprice diganti jadi grosir price
                            $(`#grosir_${items[i][0]}`).text(parseInt(items[i][10]).toLocaleString())
                        } else if(items[i][9] > 0 && items[i][4] < items[i][9]) {
                            items[i][6] = items[i][11] // originalprice diganti jadi before_grosir_price
                            $(`#grosir_${items[i][0]}`).text(parseInt(items[i][11]).toLocaleString())
                        }
                    }
                }
            }
        }

        localStorage.setItem('items', JSON.stringify(items));

        print_accumulate()
        print_total_price()
    }))

    // show/hide text for discount
    if ($('#discount_value').val() > 0) {
        $('#cont_discount').css('display', 'block')
    } else {
        $('#cont_discount').css('display', 'none')
    }

    // perhitungan discount
    $(document).on('keyup', '#discount_value', (function () {
        // reset discount customer
        $('#customer').val('umum');
        localStorage.setItem('customer_discount', 0)
        $('#discount-info').text('')
        // show/hide text for discount
        if ($('#discount_value').val() > 0) {
            $('#cont_discount').css('display', 'block')
        } else {
            $('#cont_discount').css('display', 'none')
        }

        let total = 0
        let items = JSON.parse(localStorage.getItem('items'))
        let total_price = 0

        if (items != null) {
            for (let i = 0; i < items.length; i++) {
                total = total + (items[i][4] * items[i][5])
            }
        }
        

        // define discount type
        if ($('#discount_type').val() == 'nominal') {
            total_price = total - $('#discount_value').val()
            $('#desired_discount_value').text(parseInt($('#discount_value').val()).toLocaleString())

            // store discount value to localStorage
            if ($('#discount_value').val() == '') {
                localStorage.setItem('discount', 0)
            } else {
                localStorage.setItem('discount', $('#discount_value').val())
            }

        } else {
            total_price = total - ((total * $('#discount_value').val()) / 100)
            $('#desired_discount_value').text($('#discount_value').val() + '%')

            // store discount value to localStorage
            localStorage.setItem('discount', (total * $('#discount_value').val()) / 100)
        }
        localStorage.setItem('total_price', total_price);

        $('#final_price').text(parseInt(total_price).toLocaleString())
        $('#bill').text(total_price.toLocaleString())
    }));

    // reset transaksi
    $(document).on('click', '#reset_transaction', (function () {
        localStorage.removeItem('items')
        localStorage.removeItem('total_price')
        $('#cancle_payment').modal('toggle')

        location.reload()
    }))

    // biaya lainya
    $(document).on('keyup', '#additional_fee', function () {
        let add_fee = 0
        let additional_fee = JSON.parse(localStorage.getItem('additional_fee'))
        let total_price = JSON.parse(localStorage.getItem('total_price'))

        if ($('#additional_fee').val() > 0) {
            add_fee = add_fee + parseInt($('#additional_fee').val())

            localStorage.setItem('additional_fee', add_fee)

            print_total_price()

        } else {
            total_price = total_price - additional_fee
            localStorage.setItem('total_price', total_price)
            localStorage.setItem('additional_fee', 0)
            print_total_price()
        }
    })

    // pajak
    $(document).on('keyup', '#tax', function () {
        let tax_val = 0
        let tax = JSON.parse(localStorage.getItem('tax'))
        let total_price = JSON.parse(localStorage.getItem('total_price'))

        if ($('#tax').val() > 0) {
            tax_val = tax_val + (total_price * parseInt($('#tax').val()) / 100)

            localStorage.setItem('tax', tax_val)

            print_total_price()

        } else {
            total_price = total_price - tax
            localStorage.setItem('total_price', total_price)
            localStorage.setItem('tax', 0)
            print_total_price()
        }
    })

    // get customer discount
    $(document).on('change', '#customer', function () {
        let current_total_price = JSON.parse(localStorage.getItem('total_price'))
        let current_discount = JSON.parse(localStorage.getItem('discount'))
        let customer_discount = JSON.parse(localStorage.getItem('customer_discount'))

        if ($('#customer').val() != 'umum') {
            axios.get(`/app/discounts/customer/${$('#customer').val()}`)
                .then(function (response) {
                    let disc = response.data.status ? current_total_price * (response.data.data[0].value / 100) : ''

                    if (response.data.status == true) {
                        $('#cont_discount').css('display', 'block')

                        $('#discount-info').text(`Discount available: -${response.data.data[0].value}%`)
                        localStorage.setItem('customer_discount', disc)

                        current_total_price = current_total_price - disc

                        localStorage.setItem('total_price', current_total_price);
                        localStorage.setItem('discount', (current_discount + disc));

                    } else {
                        current_total_price = current_total_price + customer_discount

                        localStorage.setItem('total_price', current_total_price);
                        localStorage.setItem('discount', current_discount - customer_discount)
                        localStorage.setItem('customer_discount', 0)
                        $('#discount-info').text('')

                        JSON.parse(localStorage.getItem('discount')) > 0 ? '' : $('#cont_discount').css('display', 'none')

                        
                    }
                }).finally(function () {
                    $('#final_price').text(parseInt(current_total_price).toLocaleString())
                    $('#bill').text(current_total_price.toLocaleString())
                })
        }
    })

});
