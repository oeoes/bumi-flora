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
        
        $('#barcode_canvas').append('<div class="col-md-6 offset-md-3" style="margin-top: 70px"><div class="h3 text-center">' + response.data.item.name + '</div><img src=\"data:image/png;base64,' + response.data.barcode + '\" /><div class="row no-gutters"><div class="col-6"><span>' + response.data.item.barcode + '</span></div><div class="col-6 text-right"><span>Rp.' + response.data.item.price.toLocaleString() + '</span></div></div></div>')
        $('#barcode_canvas').siblings().remove()
    })
}

$(document).ready(function () {
    $('#generate').prop('disabled', true)
    $('#print').prop('disabled', true)
})