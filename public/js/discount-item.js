function select_item(item_id, item_name) {
    // 
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
            value: $('#value').val()
        }).then((response) => {
            location.reload()
        })
    })
})