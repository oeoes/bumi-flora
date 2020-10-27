function store_transaction(e) {
    console.log($(this).parents());
    let items = JSON.parse(localStorage.getItem('items'));
    let item_data = [];
    if (items != null) {
        for (let i = 0; i < items.length; i++) {
            item_data.push([items[i][0], items[i][4]]);
        }
    }
    // axios.post('/cashier/store', {
    //     items: item_data,
    //     payment_method: $('#payment_option').val(), 
    //     payment_type: localStorage.getItem('payment_type'),
    //     discount: localStorage.getItem('discount')
    // }).then(function (response) {
    //     console.log(response);        
    // }).catch(function (error) {
    //     console.log(error);        
    // })
}
