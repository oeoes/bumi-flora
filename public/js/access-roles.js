function invite_user() {
    axios.post('/app/access/user', {
        name: $('#user-name').val(),
        email: $('#user-email').val(),
        phone: $('#user-phone').val(),
        password: $('#user-password').val(),
        role: $('#user-role').val(),
    }).then(function (response) {
        alert(response.data.message)
        
    }).catch(function (error) {
        alert(error.response.data.message)
    }).finally(function () {
        $('#user-modal').modal('toggle')
        location.reload()
    })
}

$(document).ready(function () {
    $(document).on('submit', '#invite-user-form', function (e) {
        e.preventDefault();
    })

    $(document).on('click', '#invite-user', function () {
        invite_user()
    })
})