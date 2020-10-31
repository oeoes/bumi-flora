function filter_opname() {
    $('#run-opname').text('Running...')

    axios.get('/app/storages/item/opname/filter', {
        params: {
            from: $('#from').val(),
            to: $('#to').val(),
            category: $('#category').val(),
            cabinet: $('#cabinet').val(),
            dept: $('#dept').val(),
        }
    }).then(function (response) {
        $('#data-opname').children().remove()
        if (response.data.data.length < 1) {
            $('#data-opname').append('<tr><td colspan="11" align="center">Data kosong.</td></tr>')
        } else {
            response.data.data.forEach(element => {
                let acc = ((parseInt(element.balance) + parseInt(Number(element.amount_in))) - parseInt(Number(element.amount_out)))
                $('#data-opname').append('<tr><td>' + element.name + '</td><td>' + element.unit + '</td><td>' + element.category + '</td><td>' + element.dept + '</td><td>' + element.cabinet + '</td><td>' + element.balance + '</td><td>' + Number(element.amount_in) + '</td><td>' + Number(element.amount_out) + '</td><td>' + acc + '</td><td>' + acc + '</td><td>' + (acc - acc) + '</td></tr>')
            });
            $('#data-opname').append('<tr><td colspan="10"></td><td><button onclick="export_opname()" class="btn btn-sm btn-outline-primary">Export</button></td></tr>')
        }
    }).finally(function () {
        setTimeout(() => {
            $('#run-opname').text('Run')
        }, 700);
    })
}

function export_opname() {
    axios({
        method: 'post',
        url: '/app/storages/item/opname/export',
        responseType: 'arraybuffer',
        data: {
            from: $('#from').val(),
            to: $('#to').val(),
            category: $('#category').val(),
            cabinet: $('#cabinet').val(),
            dept: $('#dept').val(),
        }
    }).then(function (response) {
        let blob = new Blob([response.data], {
            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
        });   
        let link = document.createElement("a");
        link.href = window.URL.createObjectURL(blob);
        link.download = 'stock-opname-export.xlsx';
        document.body.appendChild(link);
        link.click();
    }).catch(function (error) {
        console.log(error);        
    })
}

$(document).ready(function () {
    $('#data-opname').append('<tr><td colspan="11" align="center">Tidak ada data yang ditampilkan.</td></tr>')
})
