$(document).ready(function () {

    // autofocus ke field jumlah dan kode item
    $(document).on('keydown', 'input', (function (e) {
        if (e.key === 'Shift') {
            if ($('#jumlah').is(':focus')) {
                $('#item_code').focus().select()
            }
            else if ($('#item_code').is(':focus')) {
                $('#jumlah').focus().select()
            }
        }
    }));

    // cari item
    $(document).on('keydown', '#item_code', function (e) {
        let code = $('#item_code').val();

        if (e.key === 'Enter') {
            if (code.length > 0) {
                axios.get('/cashier/check', {
                        params: {
                            code: $('#item_code').val(),
                            dept: $('#dept').val()
                        }
                    })
                    .then(function (response) {
                        if (response.data.status == true) {
                            let item = response.data.data
                            let amount = item.stock - $('#jumlah').val() < 0 ? 1 : $('#jumlah').val()
                            if (item.stock > 0) {
                                if (item.minimum_item > 0 && amount >= item.minimum_item) {
                                    push_data(item.id, item.name, item.barcode, item.unit, amount, item.price, item.grosir_price, item.discount, item.stock, item.minimum_item, item.grosir_price, item.original_price)
                                } else {
                                    push_data(item.id, item.name, item.barcode, item.unit, amount, item.price, item.original_price, item.discount, item.stock, item.minimum_item, item.grosir_price, item.original_price)
                                }
                            } else {
                                alert('Maaf, stock item sedang kosong.')
                                $('#item_code').select().focus();
                            }
                        } else {
                            let dt = $('#kasir-data-item').DataTable();
                            $('#search-item').modal('show')

                            dt.search($('#item_code').val()).draw();
                            
                        }
                    })
            } else {
                $('#search-item').modal('toggle')
                $('#kasir-data-item_filter input').focus().val($('#item_code').val())
            }
        }
    });

    // focus to item code when modal closed
    $('#search-item').on('hidden.bs.modal', function () {
        $('#item_code').focus().select();
    });

    // useful key strokes
    $(document).on('keydown', 'html', function (e) {
        switch (e.key) {
            case 'Escape': // tutup modal cari item
                if (($('#search-item').data('bs.modal') || {})._isShown) {
                    $('#search-item').modal('hide');
                    $('#item_code').focus().select();
                }
                break;
            case 'F2':
                $('#item_code').focus().select();
                break;
        
            default:
                break;
        }
    });
})