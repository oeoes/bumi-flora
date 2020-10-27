function push_data_pending(description) {
    let pending_transactions = []
    let items = JSON.parse(localStorage.getItem('items'))

    if (localStorage.getItem("pending_transactions") !== null) {
        pending_transactions = JSON.parse(localStorage.getItem('pending_transactions'))
    }

    // add description to items array
    items.push(description)
    // push items to pending transaction
    pending_transactions.push(items)

    // store to localStorage
    localStorage.setItem('pending_transactions', JSON.stringify(pending_transactions));

    // clear items localStorage
    localStorage.removeItem('items')

    // reload page biar keren aja
    location.reload()
}

function print_pending_transaction() {
    let pending_transactions = JSON.parse(localStorage.getItem('pending_transactions'))

    if (pending_transactions == null) {
        $('#pending_transaction_list').append(
            '<tr id="none"><td class="text-muted" colspan="2" align="center">Daftar pending kosong</td></tr>'
        )
    } else {
        for (let i = 0; i < pending_transactions.length; i++) {
            $('#pending_transaction_list').append(
                '<tr><td>' + pending_transactions[i][pending_transactions[i].length-1] + '</td><td> <button onclick="restore(' + i + ')" type="button" class = "btn btn-sm rounded-pill pr-3 pl-3 btn-outline-success" > <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> </button> </td > < /tr>'
            )

        }
    }
}

function restore(index) {
    let pending_transactions = JSON.parse(localStorage.getItem('pending_transactions'))
    let items = []

    if (localStorage.getItem("items") !== null) {
        items = JSON.parse(localStorage.getItem('items'))
    }
    
    if (items.length > 0) {
        $('#warning_pending_restore').modal('toggle')
    } else {
        for (let i = 0; i < (pending_transactions[index].length)-1; i++) {
            items.push(pending_transactions[index][i])
        }

        // restore masukan lagi ke localstorage items
        localStorage.setItem('items', JSON.stringify(items));

        // renew pending_transactions array and update localStorage
        pending_transactions.splice(index, 1)
        localStorage.setItem('pending_transactions', JSON.stringify(pending_transactions));

        // print to view
        $('#pending_transaction_list tr').remove()
        print_pending_transaction()
        print_items()
        print_total_price()
        print_accumulate()

        $('#item_code').focus()
        $('#pending-list').modal('toggle')
    }
}

$(document).ready(function () {
    print_pending_transaction()
    // disable button confirm untuk pending
    $('#pending_transaction').prop('disabled', true)
    // validasi untuk pending transaction
    $(document).on('keyup', '#pending_description', (function () {
        if ($('#pending_description').val().length > 1) {
            $('#pending_transaction').prop('disabled', false)
        } else {
            $('#pending_transaction').prop('disabled', true)
        }
    }))

    // store pending transaction to local storage
    $(document).on('click', '#pending_transaction', (function () {
        push_data_pending($('#pending_description').val())
    }))
})