@extends('layouts.master')

@section('page-title', 'Dashboard')
@section('page-description', 'Daily report.')

@section('custom-js')
<script src="{{ asset('js/axios.js') }}"></script>
<script src="{{ asset('js/Chart.js') }}"></script>
<script>
    function addData(chart, label, data, reset) {
        if (!reset) {
            let index = chart.data.labels.indexOf(label)

            if (index == -1) {
                chart.data.labels.push(label);
                chart.data.datasets.forEach((dataset) => {
                    dataset.data.push(data);
                });
            } else {
                chart.data.datasets.forEach((dataset) => {
                    dataset.data[index] = dataset.data[index] + data
                });

            }
        } else {
            // reset
            chart.data.labels.splice(0, chart.data.labels.length)
            chart.data.datasets[0].data.splice(0, chart.data.datasets[0].data.length)

            for (let i = 0; i < label.length; i++) {
                let index = chart.data.labels.indexOf(label[i])

                if (index == -1) {
                    chart.data.labels.push(label[i])

                    chart.data.datasets.forEach((dataset) => {
                        dataset.data.push(parseInt(data[i]));
                    });

                } else {
                    chart.data.datasets.forEach((dataset) => {
                        dataset.data[index] = dataset.data[index] + parseInt(data[i])
                    });

                }

            }

        }
        chart.update();
    }

    $(document).ready(function() {

        /** first time load graphic **/
        axios.get('/app/dashboard/demand')
            .then(function(response) {

                response.data.data.forEach(element => {
                    addData(myChart, element.name, parseInt(element.quantity), false)

                });
            })
            .catch(function(error) {
                console.log(error);
            })
            .finally(function() {})
        /** end first time load graphic **/

        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Jumlah item',
                    data: [],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }, ]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: false
                        }
                    }]
                }
            }
        });

        setInterval(() => {
            // grafik
            let name = []
            let quantity = []
            axios.get('/app/dashboard/demand')
                .then(function(response) {
                    response.data.data.forEach(element => {
                        name.push(element.name)
                        quantity.push(element.quantity)
                    });
                    addData(myChart, name, quantity, true)
                })
                .catch(function(error) {
                    console.log(error);
                })
                .finally(function() {})

            // summary
            axios.get('/app/dashboard/accumulate')
                .then(function(response) {
                    $('#total_transaction').text(response.data.data[0].length)
                    if (response.data.data[1].length < 1) {
                        $('#total_omset').text('0')
                    } else {
                        let transaction_number = [];
                        let total_bill = 0;
                        let omset_arr = response.data.data[1].map(function(oms) {
                            if (!transaction_number.includes(oms.transaction_number)) {
                                transaction_number.push(oms.transaction_number)
                                total_bill = total_bill + (oms.tax + oms.additional_fee);
                            }
                            return parseInt(oms.price) - oms.discount;
                        });
                        let omset = omset_arr.reduce(function(prevVal, currVal) {
                            return prevVal + currVal;
                        });
                        $('#total_omset').text((omset + total_bill).toLocaleString())
                    }
                })
        }, 5000);

        /* first time load*/
        axios.get('/app/dashboard/cashier', {
                params: {
                    cashier: $('#cashier').val()
                }
            })
            .then(function(response) {
                $('#cashier_transaction').text(response.data.data[0].length)
                if (response.data.data[1].length < 1) {
                    $('#cashier_omset').text('0')
                } else {
                    let transaction_number = [];
                    let total_bill = 0;
                    let omset_arr = response.data.data[1].map(function(oms) {
                        if (!transaction_number.includes(oms.transaction_number)) {
                            transaction_number.push(oms.transaction_number)
                            total_bill = total_bill + (oms.tax + oms.additional_fee);
                        }
                        return parseInt(oms.price) - oms.discount;
                    });
                    let omset = omset_arr.reduce(function(prevVal, currVal) {
                        return prevVal + currVal;
                    });
                    $('#cashier_omset').text((omset + total_bill).toLocaleString())
                }
            })

        axios.get('/app/dashboard/accumulate')
            .then(function(response) {
                console.log(response);
                $('#total_transaction').text(response.data.data[0].length)
                if (response.data.data.length < 1) {
                    $('#total_omset').text('0')
                } else {
                    let transaction_number = [];
                    let total_bill = 0;
                    let omset_arr = response.data.data[1].map(function(oms) {
                        if (!transaction_number.includes(oms.transaction_number)) {
                            transaction_number.push(oms.transaction_number)
                            total_bill = total_bill + (oms.tax + oms.additional_fee);
                        }
                        return parseInt(oms.price) - oms.discount;
                    });
                    let omset = omset_arr.reduce(function(prevVal, currVal) {
                        return prevVal + currVal;
                    });
                    $('#total_omset').text((omset + total_bill).toLocaleString())
                }
            })
        /** end first time load */


        $(document).on('change', '#cashier', function() {
            axios.get('/app/dashboard/cashier', {
                    params: {
                        cashier: $('#cashier').val()
                    }
                })
                .then(function(response) {
                    $('#cashier_transaction').text(response.data.data[0].length)
                    if (response.data.data[1].length < 1) {
                        $('#cashier_omset').text('0')
                    } else {
                        console.log(response.data);
                        let transaction_number = [];
                        let total_bill = 0;
                        let omset_arr = response.data.data[1].map(function(oms) {
                            if (!transaction_number.includes(oms.transaction_number)) {
                                transaction_number.push(oms.transaction_number)
                                total_bill = total_bill + (oms.tax + oms.additional_fee);
                            }
                            return parseInt(oms.price) - oms.discount;
                        });
                        let omset = omset_arr.reduce(function(prevVal, currVal) {
                            return prevVal + currVal;
                        });
                        $('#cashier_omset').text((omset + total_bill).toLocaleString())
                    }
                })
                .catch(function(error) {
                    console.log(error);
                })
                .finally(function() {

                });
        });
    });
</script>
<!-- scroll reveal -->
<script>
    ScrollReveal().reveal('.card');
</script>
@endsection

@section('custom-css')
<link href="{{ asset('css/dataTables.css') }}" rel="stylesheet">
<style>
    @media only screen and (max-width: 600px) {
        .my-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
        }
    }
</style>
@endsection

@section('content')
<div class="page-content page-container" id="page-content">
    <div class="padding">
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <small class="text-muted text-primary">Hari ini</small>
                                <div class="text-highlight mt-2 font-weight-500">
                                    {{ \Carbon\Carbon::now()->format('l, d F Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <small class="text-muted">Jumlah kasir</small>
                                <div class="text-highlight mt-2 font-weight-500">{{ count($employee) }} Orang</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <small class="text-muted">Jumlah transaksi</small>
                                <div class="text-highlight mt-2 font-weight-500"><span id="total_transaction"></span>
                                    transaksi
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <small class="text-muted">Total pendapatan</small>
                                <div class="text-highlight mt-2 font-weight-500 text-success">+ Rp. <span id="total_omset"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <div>Aktifitas kasir</div>
                            </div>
                            <div class="col">
                                <select id="cashier" class="form-control form-control-sm">
                                    @foreach($employee as $e)
                                    <option value="{{$e->id }}">{{ ucwords($e->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="text-highlight text-lg"><span id="cashier_transaction"></span></div>
                                <small>Transaksi</small>
                            </div>
                            <div class="col-md-8">
                                <div class="text-highlight text-lg">Rp. <span id="cashier_omset"></span></div>
                                <small>Pendapatan</small>
                            </div>

                            <!-- <div class="col-12 mt-5">
                                <div class="mb-2 mt-2 mt-sm-0">
                                    <small class="text-muted">Target pendapatan harian</small>
                                </div>
                                <small>Total pendapatan:
                                    <strong class="text-primary">25%</strong>
                                </small>
                                <div class="progress my-3 circle" style="height:6px;">
                                    <div class="progress-bar circle gd-warning" data-toggle="tooltip" title=""
                                        style="width: 25%" data-original-title="25%"></div>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card p-3">
                    <div class="card-header">
                        Item : <small><a href="{{ route('storages.gudang') }}" class="text-warning">GUDANG</a></small>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted mb-2">Total Item</small>
                                <div class="h5">{{ $gudang[0]->total_item }}</div>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted mb-2">Asset</small>
                                <div class="h6">Rp.{{ number_format((float) $gudang[0]->asset) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3">
                    <div class="card-header">
                        Item : <small><a href="{{ route('storages.utama') }}" class="text-info">UTAMA</a></small>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted mb-2">Total Item</small>
                                <div class="h5">{{ $utama[0]->total_item }}</div>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted mb-2">Asset</small>
                                <div class="h6">Rp.{{ number_format((float) $utama[0]->asset) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3">
                    <div class="card-header">
                        Item : <small><a href="{{ route('storages.ecommerce') }}" class="text-success">ONLINE</a></small>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted mb-2">Total Item</small>
                                <div class="h5">{{ $ecommerce[0]->total_item }}</div>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted mb-2">Asset</small>
                                <div class="h6">Rp.{{ number_format((float) $ecommerce[0]->asset) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Grafik permintaan barang
                    </div>
                    <div class="card-body">
                        <canvas id="myChart" width="400" height="190"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection