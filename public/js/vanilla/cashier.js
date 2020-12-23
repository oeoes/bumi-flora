'use strict';

let jumlahField = document.querySelector('#jumlah');
let itemCodeField = document.querySelector('#item_code');
let dataItemContainer = document.querySelector('#data-item');
let totalPriceField = document.querySelector('#total_price');
let BillField = document.querySelector('#bill');
let finalPriceField = document.querySelector('#final_price');
let storageDept = document.querySelector('#dept');

const push_data = (id, item, barcode, unit, qty, price, original_price, discount, stock, grosir_min_item, grosir_price, before_grosir_price) => {
    let state = true;
    let items = [];

    // create localstorage/or update
    if (localStorage.getItem("items") !== null) {
        items = JSON.parse(localStorage.getItem('items'));
    }

    // kalau ada item yg sama, gausah disimpen ke localstorage, cukup tambah qty nya aja
    if (items.length > 0) {
        for (let i = 0; i < items.length; i++) {
            if (id == items[i][0]) {
                if (stock - qty < 0) {
                    alert('Stock tidak boleh kurang dari 0');
                } else {
                    items[i][4] = parseInt(items[i][4]) + 1;
                    document.querySelector(`#${id}`).value = items[i][4];

                    // pastikan stock tidak sampe 0 setelah pembelian
                    if (items[i][8] - $(`#${id}`).val() < 0) {
                        alert('Stock tidak boleh kurang dari 0');
                        document.querySelector(`#${id}`).value = 0;
                    }
                    
                    // set kondisi untuk grosir_price
                    if (items[i][9] > 0 && items[i][4] >= items[i][9]) { // min item > 0 dan qty > min item

                        items[i][6] = items[i][10]; // originalprice diganti jadi grosir price

                        document.querySelector(`#grosir_${items[i][0]}`).textContent = parseInt(items[i][10]).toLocaleString();

                    } else if (items[i][9] > 0 && items[i][4] < items[i][9]) {
                        items[i][6] = items[i][11]; // originalprice diganti jadi before_grosir_price
                        document.querySelector(`#grosir_${items[i][0]}`).textContent = parseInt(items[i][11]).toLocaleString();
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

    itemCodeField.value = '';
    itemCodeField.focus();
    jumlahField.value = 1;
}

const print_items = () => {
    let items = JSON.parse(localStorage.getItem('items'));

    if (items == null) {
        let element = '<tr id="none"><td class="text-muted" colspan="7" align="center">Belum ada item ditambahkan</td></tr>';
        dataItemContainer.appendChild(element);
    } else {
        for (let i = 0; i < items.length; i++) {
            // item grosir
            if (items[i][9] > 0) {
                let element_grosir = `<tr><td>${items[i][1]} <span class="badge badge-success badge-sm rounded-pill pt-1 pb-1 pl-2 pr-2">Grosir</span</td><td>${items[i][2]}</td><td>${items[i][3]}</td><td>${items[i][8]}</td><td> <input name="jumlah_item" min="0" style="width: 70px" id="${items[i][0]}" type="number" class="form-control form-control-sm" value="${items[i][4]}" ></td><td><div class="input-group"><input name="discount" id="${items[i][0]}" style="width: 30px" type="number" min="0" class="form-control" placeholder="disc." aria-describedby="inputGroupPrepend" value="${items[i][7]}"><div class="input-group-prepend"><span class="input-group-text" id="inputGroupPrepend">%</span> </div></div></td><td>Rp.<span id="grosir_${items[i][0]}">${parseInt(items[i][6]).toLocaleString()}</span></td><td>Rp. <span id="acc_${items[i][0]}" ></span></td><td><span onclick="remove_item(${i})" class="btn btn-sm btn-outline-danger" style="cursor: pointer"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></span></td></tr>`;
                dataItemContainer.appendChild(element_grosir);
            } else { // item non grosir

                if (typeof trx_id === 'undefined') {
                    let elem_with_removebtn = `<tr><td>${items[i][1]}</td><td>${items[i][2]}</td><td>${items[i][3]}</td><td>${items[i][8]}</td><td> <input name="jumlah_item" min="0" style="width: 70px" id="${items[i][0]}" type="number" class="form-control form-control-sm" value="${items[i][4]}" ></td><td><div class="input-group"><input name="discount" id="${items[i][0]}" style="width: 30px" type="number" min="0" class="form-control" placeholder="disc." aria-describedby="inputGroupPrepend" value="${items[i][7]}"><div class="input-group-prepend"><span class="input-group-text" id="inputGroupPrepend">%</span> </div></div></td><td>Rp.<span id="grosir_${items[i][0]}">${parseInt(items[i][6]).toLocaleString()}</span></td><td>Rp. <span id="acc_${items[i][0]}" ></span></td><td><span onclick="remove_item(${i})" class="btn btn-sm btn-outline-danger" style="cursor: pointer"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></span></td></tr>`;
                    dataItemContainer.appendChild(elem_with_removebtn);
                } else {
                    let elem_noremove = `<tr><td>${items[i][1]}</td><td>${items[i][2]}</td><td>${items[i][3]}</td><td>${items[i][8]}</td><td> <input name="jumlah_item" min="0" style="width: 70px" id="${items[i][0]}" type="number" class="form-control form-control-sm" value="${items[i][4]}" ></td><td><div class="input-group"><input name="discount" id="${items[i][0]}" style="width: 30px" type="number" min="0" class="form-control" placeholder="disc." aria-describedby="inputGroupPrepend" value="${items[i][7]}"><div class="input-group-prepend"><span class="input-group-text" id="inputGroupPrepend">%</span> </div></div></td><td>Rp.<span id="grosir_${items[i][0]}">${parseInt(items[i][6]).toLocaleString()}</span></td><td>Rp. <span id="acc_${items[i][0]}" ></span></td></tr>`;
                    dataItemContainer.appendChild(elem_noremove);
                }
            }

        }
    }
}


const print_total_price = () => {
    let total_price = 0 + JSON.parse(localStorage.getItem('tax'))
    let items = JSON.parse(localStorage.getItem('items'))

    if (items != null) {
        for (let i = 0; i < items.length; i++) {
            let pr = parseInt(items[i][4]) * parseInt(items[i][6])
            total_price = total_price + (pr - (pr * parseInt(items[i][7]) / 100))
        }
    }
    localStorage.setItem('total_price', total_price);

    totalPriceField.textContent = total_price.toLocaleString()
    BillField.textContent = total_price.toLocaleString()
    finalPriceField.textContent = parseInt(total_price).toLocaleString()
}

const get_id = (id, item, barcode, unit, price, original_price, discount, stock, minimum_item, grosir_price) => {
    if (id != null) {
        let amount = stock - jumlahField < 0 ? 0 : jumlahField
        push_data(id, item, barcode, unit, amount, price, original_price, discount, stock, minimum_item, grosir_price, original_price)
    }

    // tutup modal sama clear input search
    $('#kasir-data-item_filter input').val('')
    $('#search-item').modal('hide')
}

const print_accumulate = () => {
    let items = JSON.parse(localStorage.getItem('items'))

    if (items != null) {
        for (let i = 0; i < items.length; i++) {
            if (items[i][9] > 0 && items[i][4] > items[i][9]) { // kalau grosir true dan qty lebih dari grosir
                let pr = parseInt(items[i][10] * items[i][4]) // grosir price * quantity 
                document.querySelector(`#acc_${items[i][0]}`).textContent = parseInt(pr - (pr * items[i][7] / 100)).toLocaleString()
            } else {
                let pr = parseInt(items[i][6] * items[i][4]) // original_price * quantity 
                document.querySelector(`#acc_${items[i][0]}`).textContent = parseInt(pr - (pr * items[i][7] / 100)).toLocaleString()
            }
        }
    }
}

const remove_item = (index) => {
    let items = JSON.parse(localStorage.getItem('items'))
    items.splice(index, 1); // remove element using index

    localStorage.setItem('items', JSON.stringify(items));

    $('#data-item tr').remove();
    dataItemContainer.children.remove();
    print_items();
    print_total_price();
    print_accumulate();
    itemCodeField.focus();
}


window.onload = () => {

    // insert value ke kolom biaya lain    
    localStorage.getItem('additional_fee') == null ? localStorage.setItem('additional_fee', 0) : $('#additional_fee').val(JSON.parse(localStorage.getItem('additional_fee')))
    // set tax to 0
    localStorage.setItem('tax', 0)

    // print item yg ada di localstorage
    print_items()
    print_total_price()
    print_accumulate()

    // create discount localstorage
    localStorage.setItem('discount', 0)
    localStorage.setItem('customer_discount', 0)

    // autofocus ke field jumlah dan kode item
    document.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            if (document.activeElement.getAttribute('id') === 'item_code') {
                jumlahField.focus();
                jumlahField.select();
            }
            else if (document.activeElement.getAttribute('id') === 'jumlah') {
                itemCodeField.focus();
                itemCodeField.select();
            } else {
                itemCodeField.focus();
                itemCodeField.select();
            }
        }
    });

    // akses modal payment, reset, and pending transaction
    document.addEventListener('keypress', function (e) {
        switch (e.key) {
            case '':
                // 
                break;
            
        }
    })

    // scan barcode
    itemCodeField.addEventListener('input', function () {
        if (itemCodeField.value.length > 1) {
            axios.get('/cashier/check', {
                params: {
                    code: itemCodeField.value,
                    dept: storageDept.value
                }
            })
                .then(function (response) {
                    if (response.data.status == true) {
                        let item = response.data.data
                        let amount = item.stock - jumlahField.value < 0 ? 0 : jumlahField.value
                        if (item.stock > 0) {
                            push_data(item.id, item.name, item.barcode, item.unit, amount, item.price, item.original_price, item.discount, item.stock, item.minimum_item, item.grosir_price, item.original_price)
                        } else {
                            alert('Maaf, stock item sedang kosong.')
                            itemCodeField.focus();
                            itemCodeField.select();
                        }
                    } else {
                        $('#search-item').modal('show')
                        $('#kasir-data-item_filter input').focus().val(itemCodeField.value)
                    }
                });
        }
    });


    // field discount diupdate (masing masing field)
     document.querySelectorAll("#data-item input[name='discount']").addEventListener('keyup', function (e) {
        console.log(e);
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
    })

}