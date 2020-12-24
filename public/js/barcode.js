function select_item(item_id, item_name) {
    $('#item_name').val(item_name)
    $('#item_id').val(item_id)
    $('#pilih-item').modal('toggle')

    $('#generate').prop('disabled', false)
    $('#print').prop('disabled', false)
}

function generate() {
    axios.post('/app/barcodes/generate', {
        'item_id': $('#item_id').val()
    }).then(function (response) {
        $('#barcode_canvas').children().remove()

        for (let i = 0; i < $('#copy').val(); i++) {
            if ($('#size').val() == 'small') {
                $('#barcode_canvas').append('<div class="col-md-3 text-center" style="margin-top: 70px"><div class="h6 text-left">' + response.data.item.name + '</div><img src=\"data:image/png;base64,' + response.data.barcode + '\" /><div class="row no-gutters"><div class="col-6 text-left text-12"><span>' + response.data.item.barcode + '</span></div><div class="col-6 text-right text-12"><span>Rp.' + response.data.item.price.toLocaleString() + '</span></div></div></div>')
            } else {
                $('#barcode_canvas').append('<div class="col-md-6 text-center" style="margin-top: 70px"><div class="h4 text-left">' + response.data.item.name + '</div><img src=\"data:image/png;base64,' + response.data.barcode + '\" /><div class="row no-gutters"><div class="col-6 text-left"><span>' + response.data.item.barcode + '</span></div><div class="col-6 text-right"><span>Rp.' + response.data.item.price.toLocaleString() + '</span></div></div></div>')
            }
        }

        switch ($('#size').val()) {
            case 'small':
                $('#barcode_canvas img').css({
                    "height": "15mm",
                    "width": "33mm"
                })
                break;

            case 'medium':
                $('#barcode_canvas img').css({
                    "height": "18mm",
                    "width": "96mm"
                })
                break;

            case 'large':
                $('#barcode_canvas img').css({
                    "height": "28mm",
                    "width": "105%"
                })
                break;
        }

        $('#barcode_canvas').siblings().remove()
    })
}

function print_barcode() {
    $('#print').text('Printing...')
    axios.post('/app/barcodes/print', {
        item_id: $('#item_id').val()
    }).then(function (response) {
        alert(response.data.message);
        
    }).catch(function (error) {
        alert(error.response.data.message);
        
    }).finally(function () {
        $('#print').text('Print')
    })
}

$(document).ready(function () {
    $('#generate').prop('disabled', true)
    $('#print').prop('disabled', true)
})
