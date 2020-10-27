$(document).ready(function () {
    $('#error-type').hide()
    $('#spin').hide()
    $('#upload-file').prop('disabled', true)
    $(document).on('change', '#import_file_field', function () {
        if ($('#import_file_field').val().split('.')[$('#import_file_field').val().split('.').length - 1].toLowerCase() != 'csv') {
            $('#error-type').text('File type must be *.csv').show()
            $('#import_file_field').val('')
            $('#upload-file').prop('disabled', true)
        } 
        else {
            $('#upload-file').prop('disabled', false)
            $('#error-type').hide()
        }
    })

    $(document).on('click', '#upload-file', function () {
        let upload = new FormData()
        let file = $('#import_file_field')[0].files
        let size = file[0].size / 1000000
        
        upload.append('file', file[0])

        if (size > 2) {
            $('#error-type').text('File size is too large (max: 2Mb)').show()
            $('#import_file_field').val('')
            $('#upload-file').prop('disabled', true)
        } else {
            $('#spin').show()
            $('#upload-text').text('Importing...')
            $('#upload-file').prop('disabled', true)

            axios.post('/app/items/import', upload, {
                headers: {
                    'ContentType': 'multipart/form-data'
                }
            }).then(function (response) {
                console.log(response);
            }).catch(function (error) {

            }).finally(function () {
                $('#spin').hide()
                $('#upload-text').text('Upload')
                $('#import').modal('toggle')

                location.reload()
            })
        }
        
    })
})