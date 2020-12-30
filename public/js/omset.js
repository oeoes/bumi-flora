function select_item(item_id, item_name) {
    $('#item_name').val(item_name)
    $('#item_id').val(item_id)
    $('#pilih-item').modal('toggle')
}

$(document).ready(function () {
    $('#omset-run-sort').prop('disabled', true)
    $('#data-omset').append('<tr><td colspan="11" align="center">Tidak ada data yang ditampilkan.</td></tr>')

    $(document).on('change', '#transaction_date_from', function () {
        if ($('#transaction_date_to').val() != '') {
            $('#omset-run-sort').prop('disabled', false)
        }
    })

    $(document).on('change', '#transaction_date_to', function () {
        if ($('#transaction_date_from').val() != '') {
            $('#omset-run-sort').prop('disabled', false)
        }
    })

    $(document).on('click', '#omset-run-sort', function () {
        let state = false        
        axios.get('/app/omsets/calculate', {
            params: { 
                date_from: $('#transaction_date_from').val(),
                date_to: $('#transaction_date_to').val(),
                omset_type: $('#omset_type').val(),
                category: $('#category').val(),
                item: $('#item_id').val(),
                dept: $('#dept').val()
            }
        }).then(function (response) {
            $('#data-omset').children().remove()
            if (response.data.data.length > 0) {   
                state = true

                let omset = 0
                let profit = 0

                response.data.data.forEach(element => {
                    $('#data-omset').append(`<tr><td>${element.name}</td><td>${element.unit}</td><td>${element.category}</td><td>Rp.${parseInt(element.main_cost).toLocaleString()}</td><td>Rp.${parseInt(element.price).toLocaleString()}</td><td>${element.qty}</td><td>Rp.${parseInt(element.discount).toLocaleString()}</td><td>Rp.${parseInt(element.discount_item).toLocaleString()}</td><td>Rp.${parseInt(element.discount_customer).toLocaleString()}</td><td>Rp.${parseInt(element.omset).toLocaleString()}</td><td id="laba_${element.id}">Rp.${parseInt(element.profit).toLocaleString()}</td></tr>`)
                    element.profit > 0 ? $(`#laba_${element.id}`).addClass('text-success') : $(`#laba_${element.id}`).addClass('text-danger');
                    omset = omset + parseInt(element.omset)
                    profit = profit + parseInt(element.profit)
                });
                $('#data-omset').append(`<tr><td colspan="9" align="left">Total</td><td><b style="font-weight: 600">Rp.${parseInt(omset).toLocaleString()}</b></td><td><b style="font-weight: 600">Rp.${parseInt(profit).toLocaleString()}</b></td></tr>`)
                $('#data-omset').append('<tr><td colspan="11" align="right"><span id="export-omset" class="btn btn-sm btn-outline-primary" style="cursor: pointer">Export</span></td></tr>')
            } else {
                state = false
                $('#data-omset').append('<tr><td colspan="11" align="center" class="text-danger">Data transaksi tidak tersedia.</td></tr>')
            }     
        }).finally(function () {
            if (!state) {
                setTimeout(() => {
                    $('#data-omset').children().remove()
                    $('#data-omset').append('<tr><td colspan="11" align="center">Tidak ada data yang ditampilkan.</td></tr>')
                }, 2500);
            }
        })
    });

    $(document).on('click', '#export-omset', function () {
        $('#export-omset').text('Exporting...')
        axios({
            method: 'post',
            url: '/app/omsets/calculate/export',
            responseType: 'arraybuffer',
            data: {
                date_from: $('#transaction_date_from').val(),
                date_to: $('#transaction_date_to').val(),
                omset_type: $('#omset_type').val(),
                category: $('#category').val(),
                item: $('#item_id').val(),
                dept: $('#dept').val()
            }
        }).then(function (response) {
            let blob = new Blob([response.data], {
                type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
            })    
            let link = document.createElement("a");
            link.href = window.URL.createObjectURL(blob);
            link.download = 'omset-export.xlsx';
            document.body.appendChild(link);
            link.click();
        }).catch(function (error) {
            console.log(error);            
        }).finally(function () {
            $('#export-omset').text('Export')
        })
    })

    $(document).on('change', '#omset_type', function () {
        if ($('#omset_type').val() == 'item') {
            $('#item-block').removeClass('hide-el')
            $('#category-block').addClass('hide-el')
        } else if ($('#omset_type').val() == 'category') {
            $('#item-block').addClass('hide-el')
            $('#category-block').removeClass('hide-el')
        } else {
            $('#item-block').addClass('hide-el')
            $('#category-block').addClass('hide-el')
        }
    })
})