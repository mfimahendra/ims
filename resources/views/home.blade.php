@extends('layouts.app')

@section('content-header')
    <div class="container-fluid">
    </div>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    {{-- <div class="card-header"></div> --}}
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <p>MENU</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8">
                                <div class="card-body">
                                    <div class="chart">
                                        <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="row">
                                    <div class="col-6">
                                        <a href="{{ route('transaction.incomingIndex') }}" class="btn btn-block bg-cyan" target="_blank">
                                            <i class="fa-solid fa-boxes-packing"></i>
                                            Barang Masuk
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <a href="{{ route('transaction.outgoingIndex') }}" class="btn btn-block btn-warning" target="_blank">
                                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                            Barang Keluar
                                        </a>
                                    </div>                                    
                                    <div class="col-12" style="margin: 10px 0;">
                                        <a href="{{ route('transaction.transactionLogsIndex') }}" class="btn btn-block bg-gray" target="_blank">
                                            <i class="fa-solid fa-clock-rotate-left"></i>
                                            Riwayat
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    {{-- <script src="../../plugins/chart.js/Chart.min.js"></script> --}}
    <script src="{{ asset('adminlte/plugins/chart.js/Chart.min.js') }}"></script>
    <script>        

        var areaChartData = {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            datasets: [{
                    label: 'Outgoing',
                    backgroundColor: 'rgba(60,141,188,0.9)',
                    borderColor: 'rgba(60,141,188,0.8)',
                    pointRadius: false,
                    pointColor: '#3b8bba',
                    pointStrokeColor: 'rgba(60,141,188,1)',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(60,141,188,1)',
                    data: [28, 48, 40, 19, 86, 27, 90]
                },
                {
                    label: 'Incoming',
                    backgroundColor: 'rgba(210, 214, 222, 1)',
                    borderColor: 'rgba(210, 214, 222, 1)',
                    pointRadius: false,
                    pointColor: 'rgba(210, 214, 222, 1)',
                    pointStrokeColor: '#c1c7d1',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(220,220,220,1)',
                    data: [65, 59, 80, 81, 56, 55, 40]
                },
            ]
        }

        var areaChartOptions = {
            maintainAspectRatio: false,
            responsive: true,
            legend: {
                display: false
            },
            scales: {
                xAxes: [{
                    gridLines: {
                        display: false,
                    }
                }],
                yAxes: [{
                    gridLines: {
                        display: false,
                    }
                }]
            }
        }
        // This will get the first returned node in the jQuery collection.        

        //-------------
        //- LINE CHART -
        //--------------        
        var lineChartOptions = $.extend(true, {}, areaChartOptions)
        var lineChartData = $.extend(true, {}, areaChartData)
        lineChartData.datasets[0].fill = false;
        lineChartData.datasets[1].fill = false;
        lineChartOptions.datasetFill = false

        //-------------
        //- DONUT CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.        
        var donutData = {
            labels: [
                'Chrome',
                'IE',
                'FireFox',
                'Safari',
                'Opera',
                'Navigator',
            ],
            datasets: [{
                data: [700, 500, 400, 600, 300, 100],
                backgroundColor: ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
            }]
        }
        var donutOptions = {
            maintainAspectRatio: false,
            responsive: true,
        }

        //-------------
        //- PIE CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.        
        var pieData = donutData;
        var pieOptions = {
            maintainAspectRatio: false,
            responsive: true,
        }
        //Create pie or douhnut chart
        //-------------
        //- BAR CHART -
        //-------------
        var barChartCanvas = $('#barChart').get(0).getContext('2d')
        var barChartData = $.extend(true, {}, areaChartData)
        var temp0 = areaChartData.datasets[0]
        var temp1 = areaChartData.datasets[1]
        barChartData.datasets[0] = temp1
        barChartData.datasets[1] = temp0

        var barChartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            datasetFill: false
        }

        new Chart(barChartCanvas, {
            type: 'bar',
            data: barChartData,
            options: barChartOptions
        })
    </script>
@endsection
