function select_item(item_id, item_name) {
    $('#item_name').val(item_name)
    $('#item_id').val(item_id)

    $('#pilih-item').modal('toggle')
}

function browse_item_update(key) {
    $('#pilih-item-update').modal('toggle')
    // $(`#editdiscount${key}`).modal('toggle')
}

$(document).ready(() => {
    $(document).on('change', '#promo_item_type', function () {
        if ($('#promo_item_type').val() == 'item') {
            $('#item-block').removeClass('disc-toggle')
            $('#category-block').addClass('disc-toggle')
        } else if ($('#promo_item_type').val() == 'category') {
            $('#item-block').addClass('disc-toggle')
            $('#category-block').removeClass('disc-toggle')
        } else {
            $('#item-block').addClass('disc-toggle')
            $('#category-block').addClass('disc-toggle')
        }
    })

    $(document).on('click', '#create-discount', function () {
        axios.post('/app/discounts/item', {
            promo_name: $('#promo_name').val(),
            promo_item_type: $('#promo_item_type').val(),
            category_id: $('#category').val(),
            item_id: $('#item_id').val(),
            value: $('#value').val()
        }).then((response) => {
            location.reload()
        })
    })

    $(document).on('click', '.check', function () {
        let form_id = $(this).attr('id').split("-")[2]
        
        if (this.checked) {
            $('input[name="discount_active_at[]"]').prop('checked', true)
        } else {
            $('input[name="discount_active_at[]"]').prop('checked', false)
        }
    })
})