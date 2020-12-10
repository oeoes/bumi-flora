function select_item(item_id, item_name, item_price) {
    $('#item_name').val(item_name)
    $('#item_id').val(item_id)
    $('#item_price').val(item_price)
    $('#pilih-item').modal('toggle')

    $('#generate').prop('disabled', false)
    $('#print').prop('disabled', false)
}

function store_grosir() {
    axios.post('/app/grosirs', {
        item_id: $('#item_id').val(),
        minimum_item: $('#minimum_item').val(),
        new_price: $('#new_price').val(),
    }).then(function (response) {
        location.reload()
    }).catch(function (error) {
        alert(error.response.data.message)
    })
}

$(document).ready(function () {
    
})
