'use strict';

let btnStoreEcommerce = document.querySelector('#store_ecommerce');
let customer = document.querySelector('#customer');
let additionalFee = document.querySelector('#additional_fee');
let storageDept = document.querySelector('#dept');
let nominalField = document.querySelector('#nominal');

btnStoreEcommerce.addEventListener('click', () => {
    btnStoreEcommerce.textContent = 'Menyimpan...';

    let items = JSON.parse(localStorage.getItem('items'))
    let item_data = []

    if (items != null) {
        for (let i = 0; i < items.length; i++) {
            // item_id, qty, item_name, price, satuan, discount item
            item_data.push([items[i][0], items[i][4], items[i][1], items[i][5], items[i][3], items[i][7]])
        }
    }

    axios.post('/cashier/store', {
        items: item_data,
        discount: localStorage.getItem('discount'),
        customer: customer.value,
        additional_fee: additionalFee.value,
        tax: localStorage.getItem('tax'),
        dept: storageDept.value,
        nominal: nominalField,
        transaction_id: $('#id-edit-transaction').val() ? $('#id-edit-transaction').val() : ''
    }).then(function (response) {
        console.log(response);
        if (response.data.status == true) {
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
        }
    }).catch(function (error) {
        console.log(error.response)      
    }).finally(function (e) {
        location.reload()

        btnStoreEcommerce.textContent = 'Simpan';
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
    })
});