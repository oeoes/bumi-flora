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
        
        upload.append('file', file[0]);
        upload.append('dept', $('#dept').val());

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
                alert('Berhasil.')
            }).catch(function (error) {

            }).finally(function () {
                $('#spin').hide()
                $('#upload-text').text('Upload')
                $('#import').modal('toggle')

                $('#upload-file').prop('disabled', false)

                location.reload()
            })
        }
        
    })

    $(document).on('click', '#download-file', function () {
        let fileType = $('input[name="file_type"]:checked').val();
        let reportType = $('input[name="report_type"]:checked').val();

        $('#download-file').text('Downloading...');
        axios({
            method: 'post',
            url: '/app/items/data/export',
            responseType: 'arraybuffer',
            data: {
                fileType: fileType,
                reportType: reportType,
            }
        }).then(function (response) {
            let config = JSON.parse(response.config.data)
            let blob = new Blob([response.data], {
                type: config.fileType === 'pdf' ? "application/pdf" : "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
            }) 

            let link = document.createElement("a");
            link.href = window.URL.createObjectURL(blob);
            link.download = config.fileType === 'pdf' ? 'master-data.pdf' : 'master-data.xlsx';
            document.body.appendChild(link);
            link.click();
        }).catch(function (error) {

        }).finally(function () {
            $('#download-file').text('Download');
            $('#export').modal('hide');
        });
        
    })
})