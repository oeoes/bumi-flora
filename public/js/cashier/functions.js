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
                    location.reload();
                } else {
                    let jumlah = parseInt($('#jumlah').val())
                    items[i][4] = parseInt(items[i][4]) + jumlah
                    $(`#${id}`).val(items[i][4])
                    // pastikan stock tidak sampe 0 setelah pembelian
                    if (items[i][8] - $(`#${id}`).val() < 0) {
                        alert('Stock tidak boleh kurang dari 0')
                        items[i][4] = parseInt(items[i][8])
                        location.reload();
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
            '<tr id="none"><td class="text-muted" colspan="9" align="center">Belum ada item ditambahkan</td></tr>'
        )
    } else {
        for (let i = 0; i < items.length; i++) {
            // item grosir
            let clr = items[i][8] > 0 ? '' : 'text-danger';
            if (items[i][9] > 0) {
                if (typeof trx_id == 'undefined') {
                    $('#data-item').append(
                        `<tr><td>${items[i][1]} <span class="badge badge-success badge-sm rounded-pill pt-1 pb-1 pl-2 pr-2">Grosir</span</td><td>${items[i][2]}</td><td>${items[i][3]}</td><td><span class="${clr}">${items[i][8]}</span></td><td> <input name="jumlah_item" min="1" style="width: 70px" id="${items[i][0]}" type="number" class="form-control form-control-sm" value="${items[i][4]}" ></td><td><div class="input-group"><input name="discount" id="${items[i][0]}" style="width: 50px" type="number" min="0" class="form-control" placeholder="disc." aria-describedby="inputGroupPrepend" value="${items[i][7]}"><div class="input-group-prepend"><span class="input-group-text" id="inputGroupPrepend">%</span> </div></div></td><td>Rp.<span id="grosir_${items[i][0]}">${parseInt(items[i][6]).toLocaleString()}</span></td><td>Rp. <span id="acc_${items[i][0]}" ></span></td><td><span onclick="remove_item(${i})" class="btn btn-sm btn-outline-danger" style="cursor: pointer"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></span></td></tr>`
                    ).fadeIn(3000, 'ease')
                } else {
                    $('#data-item').append(
                        `<tr><td>${items[i][1]} <span class="badge badge-success badge-sm rounded-pill pt-1 pb-1 pl-2 pr-2">Grosir</span</td><td>${items[i][2]}</td><td>${items[i][3]}</td><td><span class="${clr}">${items[i][8]}</span></td><td> <input name="jumlah_item" min="1" style="width: 70px" id="${items[i][0]}" type="number" class="form-control form-control-sm" value="${items[i][4]}" ></td><td><div class="input-group"><input name="discount" id="${items[i][0]}" style="width: 50px" type="number" min="0" class="form-control" placeholder="disc." aria-describedby="inputGroupPrepend" value="${items[i][7]}"><div class="input-group-prepend"><span class="input-group-text" id="inputGroupPrepend">%</span> </div></div></td><td>Rp.<span id="grosir_${items[i][0]}">${parseInt(items[i][6]).toLocaleString()}</span></td><td>Rp. <span id="acc_${items[i][0]}" ></span></td></tr>`
                    ).fadeIn(3000, 'ease')
                }

            } else { // item non grosir
                if (typeof trx_id == 'undefined') {
                    $('#data-item').append(
                        `<tr><td>${items[i][1]}</td><td>${items[i][2]}</td><td>${items[i][3]}</td><td><span class="${clr}">${items[i][8]}</span></td><td> <input name="jumlah_item" min="1" style="width: 70px" id="${items[i][0]}" type="number" class="form-control form-control-sm" value="${items[i][4]}" ></td><td><div class="input-group"><input name="discount" id="${items[i][0]}" style="width: 50px" type="number" min="0" class="form-control" placeholder="disc." aria-describedby="inputGroupPrepend" value="${items[i][7]}"><div class="input-group-prepend"><span class="input-group-text" id="inputGroupPrepend">%</span> </div></div></td><td>Rp.<span id="grosir_${items[i][0]}">${parseInt(items[i][6]).toLocaleString()}</span></td><td>Rp. <span id="acc_${items[i][0]}" ></span></td><td><span onclick="remove_item(${i})" class="btn btn-sm btn-outline-danger" style="cursor: pointer"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></span></td></tr>`
                    ).fadeIn(3000, 'ease')
                } else {
                    $('#data-item').append(
                        `<tr><td>${items[i][1]}</td><td>${items[i][2]}</td><td>${items[i][3]}</td><td><span class="${clr}">${items[i][8]}</span></td><td> <input name="jumlah_item" min="1" style="width: 70px" id="${items[i][0]}" type="number" class="form-control form-control-sm" value="${items[i][4]}" ></td><td><div class="input-group"><input name="discount" id="${items[i][0]}" style="width: 50px" type="number" min="0" class="form-control" placeholder="disc." aria-describedby="inputGroupPrepend" value="${items[i][7]}"><div class="input-group-prepend"><span class="input-group-text" id="inputGroupPrepend">%</span> </div></div></td><td>Rp.<span id="grosir_${items[i][0]}">${parseInt(items[i][6]).toLocaleString()}</span></td><td>Rp. <span id="acc_${items[i][0]}" ></span></td></tr>`
                    ).fadeIn(3000, 'ease')
                }
            }

        }
    }
}

function print_total_price() {
    let items = JSON.parse(localStorage.getItem('items'));
    let tax = JSON.parse(localStorage.getItem('tax'));
    let discount = JSON.parse(localStorage.getItem('discount'));
    let cust_discount = JSON.parse(localStorage.getItem('customer_discount'));
    let additional_fee = JSON.parse(localStorage.getItem('additional_fee'));
    let total_price = 0;
    let total = 0;

    if (items != null) {
        for (let i = 0; i < items.length; i++) {
            let pr = parseInt(items[i][4]) * parseInt(items[i][6])
            total += (pr - (pr * parseInt(items[i][7]) / 100))
        }
    }
    total_price = (total + tax + additional_fee) - (discount + cust_discount)
    localStorage.setItem('total_price', total_price);

    $('#total_price').text(parseInt(total + tax).toLocaleString())
    $('#bill').text(parseInt(total_price).toLocaleString())
    $('#final_price').text(parseInt(total_price - additional_fee).toLocaleString())
    $('#additional_fee_text').text(!additional_fee ? 0 : additional_fee);
}


function get_id(id, item, barcode, unit, price, original_price, discount, stock, minimum_item, grosir_price) {
    if (id != null) {
        let jumlah = $('#jumlah').val();
        let amount = stock - jumlah < 0 ? 1 : jumlah

        if (amount < jumlah) alert('jumlah item melebihi stock');

        if (minimum_item > 0 && amount >= minimum_item) { // min item > 0 dan qty > min item
            // originalprice diganti jadi grosir price
            push_data(id, item, barcode, unit, amount, price, grosir_price, discount, stock, minimum_item, grosir_price, original_price)
        } else {
            push_data(id, item, barcode, unit, amount, price, original_price, discount, stock, minimum_item, grosir_price, original_price)
        }
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
            if (parseInt(items[i][9]) > 0 && parseInt(items[i][4]) > parseInt(items[i][9])) { // kalau grosir true dan qty lebih dari grosir
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

function cashier_retrieve_data() {
    let tax = JSON.parse(localStorage.getItem('tax'));
    let additional_fee = JSON.parse(localStorage.getItem('additional_fee'));
    let items = JSON.parse(localStorage.getItem('items'))
    let total = 0
    let total_discount_item = 0;

    if (items != null) {
        for (let i = 0; i < items.length; i++) {
            let pr = parseInt(items[i][4]) * parseInt(items[i][6])
            total = total + (pr - (pr * parseInt(items[i][7]) / 100))
        }
    }

    let discount = JSON.parse(localStorage.getItem('discount'));
    let discount_percentage = 100 * parseInt(discount) / total;
    localStorage.setItem('discount', discount);

    // convert to nomiinal and write to discount value nominal field
    $('#discount_value_nominal').val(discount)
    $('#discount_value_percentage').val(discount_percentage.toFixed(2))

    // print value to screen by id desired_discount_value
    $('#desired_discount_value').text(parseInt(discount).toLocaleString())

    // write tax value percentage to nominal value
    let tax_nominal_val = (parseInt(tax) * 100 / total)
    $('#tax_nominal').val(tax)
    $('#tax_percentage').val(tax_nominal_val.toFixed(2))
}