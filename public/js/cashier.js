$(document).ready(function () {
    // insert value  
    localStorage.getItem('additional_fee') == null ? localStorage.setItem('additional_fee', 0) : $('#additional_fee').val(JSON.parse(localStorage.getItem('additional_fee')))
    localStorage.getItem('tax') == null ? localStorage.setItem('tax', 0) : $('#tax').val(JSON.parse(localStorage.getItem('tax')));
    localStorage.getItem('discount') == null ? localStorage.setItem('discount', 0) : $('#discount_value_nominal').val(JSON.parse(localStorage.getItem('discount')));

    // print item yg ada di localstorage
    print_items()
    print_total_price()
    print_accumulate()

    // field discount diupdate
    $(document).on('keyup', "#data-item input[name='discount']", (function () {
        let items = JSON.parse(localStorage.getItem('items'))

        if (items != null) {
            for (let i = 0; i < items.length; i++) {
                if (items[i][0] == $(this).attr('id')) {
                    items[i][7] = $(this).val() // increase or decrease discount
                    items[i][5] = items[i][6] - Math.trunc(items[i][6] * items[i][7] / 100); // update price
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
        if ($(this).val() === '0') {
            $(this).val(1);
        }
        
        if (items != null) {
            for (let i = 0; i < items.length; i++) {
                if (items[i][0] == $(this).attr('id')) {
                    if (items[i][8] - $(this).val() < 0) {
                        alert('Stock tidak boleh kurang dari 0')
                        $(this).val(0);
                        location.reload();
                    } else {
                        items[i][4] = $(this).val() // increase or decrease qty

                        // set kondisi untuk grosir_price
                        if (parseInt(items[i][9]) > 0 && parseInt(items[i][4]) >= parseInt(items[i][9])) { // min item > 0 dan qty > min item
                            items[i][6] = items[i][10] // originalprice diganti jadi grosir price
                            $(`#grosir_${items[i][0]}`).text(parseInt(items[i][10]).toLocaleString())
                        } else if (parseInt(items[i][9]) > 0 && parseInt(items[i][4]) < parseInt(items[i][9])) {
                            items[i][6] = items[i][11] // originalprice diganti jadi before_grosir_price
                            $(`#grosir_${items[i][0]}`).text(parseInt(items[i][11]).toLocaleString())
                        }
                    }
                }
            }
        }

        localStorage.setItem('items', JSON.stringify(items));

        // set tax and discount to 0
        $('#discount_value_percentage').val(0);
        $('#discount_value_nominal').val(0);
        $('#tax_percentage').val(0);
        $('#tax_nominal').val(0);
        $('#customer').val('umum');
        $('#discount-info').text('');
        localStorage.setItem('discount', 0);
        localStorage.setItem('customer_discount', 0);
        localStorage.setItem('tax', 0);
        $('#cont_discount').css('display', 'none')

        // print items
        print_accumulate()
        print_total_price()
    }))

    // show/hide text for discount
    if ($('#discount_value_nominal').val() > 0) {
        $('#cont_discount').css('display', 'block')
    } else {
        $('#cont_discount').css('display', 'none')
    }

    // perhitungan discount percentage
    $(document).on('keyup', '#discount_value_percentage', (function () {
        // show/hide text for discount
        if ($('#discount_value_percentage').val() > 0 || JSON.parse(localStorage.getItem('customer_discount')) > 0) {
            $('#cont_discount').css('display', 'block')
        } else {
            $('#cont_discount').css('display', 'none')
        }

        let total = 0
        let items = JSON.parse(localStorage.getItem('items'))

        if (items != null) {
            for (let i = 0; i < items.length; i++) {
                let pr = parseInt(items[i][4]) * parseInt(items[i][6])
                total = total + (pr - (pr * parseInt(items[i][7]) / 100))
            }
        }

        // convert to nomiinal and write to discount value nominal field
        let percentage_val = (total * $('#discount_value_percentage').val()) / 100
        $('#discount_value_nominal').val(percentage_val < 1 ? 0 : Math.trunc(percentage_val))
        
        // store to local storage
        localStorage.setItem('discount', Math.trunc(percentage_val))
        // print value to screen by id desired_discount_value
        $('#desired_discount_value').text($('#discount_value_percentage').val() + '%')
        
        print_total_price()
    }));

    // perhitungan discount nominal
    $(document).on('keyup', '#discount_value_nominal', (function () {
        // show/hide text for discount
        if ($('#discount_value_nominal').val() > 0 || JSON.parse(localStorage.getItem('customer_discount')) > 0) {
            $('#cont_discount').css('display', 'block')
        } else {
            $('#cont_discount').css('display', 'none')
        }

        let total = 0
        let items = JSON.parse(localStorage.getItem('items'))

        if (items != null) {
            for (let i = 0; i < items.length; i++) {
                let pr = parseInt(items[i][4]) * parseInt(items[i][6])
                total = total + (pr - (pr * parseInt(items[i][7]) / 100))
            }
        }

        // convert to nomiinal and write to discount value nominal field
        let nominal_val = $('#discount_value_nominal').val();
        let percentage_val = nominal_val < 1 ? 0 : 100 * nominal_val / total;
        $('#discount_value_percentage').val(percentage_val.toFixed(2))
        
        // store to local storage
        localStorage.setItem('discount', Math.trunc(nominal_val))
        // print value to screen by id desired_discount_value
        $('#desired_discount_value').text(parseInt(nominal_val).toLocaleString())

        print_total_price()
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

    // pajak percentage
    $(document).on('keyup', '#tax_percentage', function () {
        let tax = JSON.parse(localStorage.getItem('tax'))
        let items = JSON.parse(localStorage.getItem('items'))
        let total_price = 0

        if (items != null) {
            for (let i = 0; i < items.length; i++) {
                let pr = parseInt(items[i][4]) * parseInt(items[i][6])
                total_price = total_price + (pr - (pr * parseInt(items[i][7]) / 100))
            }
        }

        // write tax value percentage to nominal value
        let tax_percentage_val = (total_price * parseInt($('#tax_percentage').val()) / 100)
        $('#tax_nominal').val(Math.trunc(tax_percentage_val))

        if ($('#tax_percentage').val() > 0) {
            localStorage.setItem('tax', Math.trunc(tax_percentage_val))

            print_total_price()

        } else {
            total_price = total_price - tax
            localStorage.setItem('total_price', total_price)
            localStorage.setItem('tax', 0)
            print_total_price()
        }
    })

    // pajak nominal
    $(document).on('keyup', '#tax_nominal', function () {
        let tax = JSON.parse(localStorage.getItem('tax'))
        let items = JSON.parse(localStorage.getItem('items'))
        let total_price = 0

        if (items != null) {
            for (let i = 0; i < items.length; i++) {
                let pr = parseInt(items[i][4]) * parseInt(items[i][6])
                total_price = total_price + (pr - (pr * parseInt(items[i][7]) / 100))
            }
        }

        // write tax value percentage to nominal value
        let tax_nominal_val = (parseInt($('#tax_nominal').val()) * 100 / total_price)
        $('#tax_percentage').val(tax_nominal_val.toFixed(2))

        if ($('#tax_nominal').val() > 0) {
            localStorage.setItem('tax', parseInt($('#tax_nominal').val()))

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
        let items = JSON.parse(localStorage.getItem('items'));
        let total = 0;

        if (items != null) {
            for (let i = 0; i < items.length; i++) {
                let pr = parseInt(items[i][4]) * parseInt(items[i][6])
                total += (pr - (pr * parseInt(items[i][7]) / 100))
            }
        }
        
        if ($('#customer').val() != 'umum') {
            axios.get(`/app/discounts/customer/${$('#customer').val()}`)
                .then(function (response) {
                    // hitung potongannya berapa
                    let disc = response.data.status ? Math.trunc(total * (response.data.data[0].value / 100)) : 0;

                    // bila discount customer tersedia
                    if (response.data.status == true) {
                        $('#cont_discount').css('display', 'block')

                        $('#discount-info').text(`Discount available: -${response.data.data[0].value}%`);
                        localStorage.setItem('customer_discount', disc); // simpan discount ke localStorage

                        print_total_price();

                    } else {
                        localStorage.setItem('customer_discount', 0);
                        $('#discount-info').text('');

                        JSON.parse(localStorage.getItem('discount')) > 0 ? '' : $('#cont_discount').css('display', 'none');

                        print_total_price();
                    }
                });
        }
        else {
            localStorage.setItem('customer_discount', 0);
            $('#discount-info').text('');

            JSON.parse(localStorage.getItem('discount')) > 0 ? '' : $('#cont_discount').css('display', 'none');

            print_total_price();
         }
    })

});
