function store_transaction(e) {
    $(e).text('Simpan & Bayar...')
    
    let items = JSON.parse(localStorage.getItem('items'))
    let item_data = []

    if (items != null) {
        for (let i = 0; i < items.length; i++) {
            item_data.push([items[i][0], items[i][4]])
        }
    }

    axios.post('/cashier/store', {
        items: item_data,
        payment_method: $('#payment_option').val(), 
        payment_type: localStorage.getItem('payment_type'),
        discount: localStorage.getItem('discount')
    }).then(function (response) {
        if (response.data.status == true) {
            localStorage.removeItem('items')
            localStorage.removeItem('total_price')
            localStorage.removeItem('payment_type')
            localStorage.setItem('discount', 0)

            $('#data-item tr').remove()
            $('#discount_value').val(0)
            print_items()
            print_accumulate()
            print_total_price()
        }
    }).catch(function (error) {
        console.log(error);        
    }).finally(function (e) {
        $(e).text('Simpan & Bayar')
        $('#payment').modal('toggle')
        $('#item_code').val('');
        $('#item_code').focus();

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

    $(document).on('change', '#payment_option', (function () {
        console.log($('#payment_option').val());
        switch ($('#payment_option').val()) {        
            case 'tunai':
                // kosongin field nominal
                $('#nominal').val('')
                // disable button pay
                $('#save_n_pay').prop('disabled', true)

                $('#nominal_block').removeClass('payment_toggle')
                $('#ewallet_block').addClass('payment_toggle')
                $('#debit_block').addClass('payment_toggle')
                $('#transfer_block').addClass('payment_toggle')

                $('#nominal').keyup(function () {
                    let total_price = localStorage.getItem('total_price')
                    let result = $('#nominal').val() - total_price
                    $('#cashback').text(result.toLocaleString())

                    if (result < 0) {
                        $('#cashback').addClass('text-danger')
                        $('#cashback').removeClass('text-success')
                    } else {
                        $('#cashback').addClass('text-success')
                        $('#cashback').removeClass('text-danger')
                    }

                    // enable button pay
                    $('#save_n_pay').prop('disabled', false)

                    // payment type in localstorage
                    localStorage.setItem('payment_type', 'cash')
                })

                break;

            case 'ewallet':
                // disable button pay
                $('#save_n_pay').prop('disabled', true)

                $('#nominal_block').addClass('payment_toggle')
                $('#ewallet_block').removeClass('payment_toggle')
                $('#debit_block').addClass('payment_toggle')
                $('#transfer_block').addClass('payment_toggle')

                $('#cashback').text('-')

                // payment type in localstorage
                $("input[name='ewallet_method']").change(function () {
                    localStorage.setItem('payment_type', $(this).val())

                    // enable button pay
                    $('#save_n_pay').prop('disabled', false)
                })
                
                break;
            
            case 'debit':
                // disable button pay
                $('#save_n_pay').prop('disabled', true)

                $('#nominal_block').addClass('payment_toggle')
                $('#ewallet_block').addClass('payment_toggle')
                $('#debit_block').removeClass('payment_toggle')
                $('#transfer_block').addClass('payment_toggle')

                $('#cashback').text('-')

                // payment type in localstorage
                $("input[name='debit_method']").change(function () {
                    localStorage.setItem('payment_type', $(this).val())
                    // enable button pay
                    $('#save_n_pay').prop('disabled', false)
                })
                break;
            
            case 'bank_transfer':
                // disable button pay
                $('#save_n_pay').prop('disabled', true)

                $('#nominal_block').addClass('payment_toggle')
                $('#ewallet_block').addClass('payment_toggle')
                $('#debit_block').addClass('payment_toggle')
                $('#transfer_block').removeClass('payment_toggle')

                $('#cashback').text('-')

                // payment type in localstorage
                localStorage.setItem('payment_type', $("#transfer_block input").val())
                // enable button pay
                $('#save_n_pay').prop('disabled', false)
                break;

            default:
                // disable button pay
                $('#save_n_pay').prop('disabled', true)

                $('#nominal_block').addClass('payment_toggle')
                $('#ewallet_block').addClass('payment_toggle')
                $('#debit_block').addClass('payment_toggle')
                $('#transfer_block').addClass('payment_toggle')

                $('#cashback').text('-')

                // payment type in localstorage
                localStorage.setItem('payment_type', '')
                break;
        }
    }))
})
