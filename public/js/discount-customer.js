function get_customer(name, id) {
    $('#customer_id').val(id)
    $('#customer_name').val(name)

    $('#pilih-customer').modal('toggle')
}
$(document).ready(() => {
    $(document).on('click', '#create-discount', function () {
        axios.post('/app/discounts/customer', {
            promo_name: $('#promo_name').val(),
            customer_id: $('#customer_id').val(),
            value: $('#value').val()
        }).then((response) => {
            console.log(response.data);            
        }).finally(() =>{
            location.reload()
        })
    })
})