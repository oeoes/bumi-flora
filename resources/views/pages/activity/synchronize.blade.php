@extends('layouts.master')

@section('page-title', 'Configuration')
@section('page-description', 'Synchronize local data with live server.')

@section('custom-js')
<script src="{{ asset('js/axios.js') }}"></script>
<script>
    'use strict';

    const btnRefresh = document.querySelector('#refresh');
    const btnStore = document.querySelector('#store');
    const progressCont = document.querySelector('#progress');
    const progressText = document.querySelector('#text-progress');
    const btnStatus = document.querySelector('#status');
    const btnSyncLocal = document.querySelector('#sync-local');

    btnRefresh.addEventListener('click', () => {
        btnRefresh.disabled = true;
        btnStore.disabled = true;
        progressCont.style.display = 'block';
        btnStatus.style.display = 'none';

        let sync = setInterval(() => {
            progressText.innerHTML = 'Synchronizing...'
        }, 3000);

        let pw = setInterval(() => {
            progressText.innerHTML = 'Please wait...'
        }, 5000);

        axios.get('/app/cashier/sync/local').then((response) => {
            console.log(response);
            if (response.data.status) {
                btnStatus.innerHTML = "You're up to date";
                btnStatus.classList.add('text-success');
            } else {
                btnStatus.innerHTML = `${response.data.message}`;
                btnStatus.classList.add('text-danger');
            }

        }).finally(() => {
            btnRefresh.disabled = false;
            btnStore.disabled = false;
            progressCont.style.display = 'none';
            btnStatus.style.display = 'block';
            progressText.innerHTML = 'Checking...'

            clearInterval(sync);
            clearInterval(pw);

            setTimeout(() => {
                btnStatus.classList.remove('text-success');
                btnStatus.classList.remove('text-danger');
                btnStatus.innerHTML = "Results would be here.";
            }, 5000);
        });


    });

    btnStore.addEventListener('click', () => {
        btnStore.disabled = true;
        btnRefresh.disabled = true;
        progressCont.style.display = 'block';
        btnStatus.style.display = 'none';
        progressText.innerHTML = 'Preparing...'

        let sync = setInterval(() => {
            progressText.innerHTML = 'Uploading...'
        }, 3000);

        let pw = setInterval(() => {
            progressText.innerHTML = 'Please wait...'
        }, 8000);

        axios.post('/app/cashier/sync/live').then((response) => {
            if (response.data.status) {
                btnStatus.innerHTML = "Data uploaded sucessfuly.";
                btnStatus.classList.add('text-success');
            } else {
                btnStatus.innerHTML = `${response.data.message}`;
                btnStatus.classList.add('text-danger');
            }
        }).finally(() => {
            btnRefresh.disabled = false;
            btnStore.disabled = false;
            progressCont.style.display = 'none';
            btnStatus.style.display = 'block';
            progressText.innerHTML = 'Checking...'

            clearInterval(sync);
            clearInterval(pw);

            setTimeout(() => {
                btnStatus.classList.remove('text-success');
                btnStatus.classList.remove('text-danger');
                btnStatus.innerHTML = "Results would be here.";
            }, 8000);
        })
    });
</script>
@endsection

@section('content')
<div class="page-content page-container" id="page-content">
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body d-flex justify-content-center align-items-center" style="height: 50vh">
                        <div>
                            <div class="text-center">
                                <div class="h3 text-muted mb-4">Ready to sync.</div>
                                <button id="refresh" class="btn btn-sm btn-outline-primary"><i data-feather="download" class="mr-2"></i> Get new data</button>
                                <button id="store" class="btn btn-sm btn-outline-info"><i data-feather="upload" class="mr-2"></i>Store to server</button>
                            </div>
                            <div id="status" class="text-center mt-3" style="font-size: 12px">
                                Results would be here.
                            </div>
                            <div id="sync-local" class="text-center mt-3" style="display:none">
                                <button class="btn btn-sm btn-outline-success rounded-pill"><i data-feather="refresh-cw" class="mr-2"></i>Sync</button>
                            </div>
                            <div id="progress" class="text-center mt-3" style="display: none">
                                <button class="btn" type="button" disabled>
                                    <span class="spinner-border spinner-border-sm text-success" role="status" aria-hidden="true"></span>
                                    <span id="text-progress" class="ml-2">Checking...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection