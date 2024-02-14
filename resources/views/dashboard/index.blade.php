@extends('layout')

@section('header')
    <h2>Tableau de bord</h2>
@endsection

@section('content')
<div class="container">
    <!-- Audit data -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $auditSetting->year }}</h3>
                    <p>Année d'audit</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="{{ route('attributaires.index') }}" class="small-box-footer">Plus d'infos <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $auditSetting->audition_percentage }}<sup style="font-size: 20px">%</sup></h3>
                    <p>Pourcentage d'audition</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="{{ route('attributaires.index') }}" class="small-box-footer">Plus d'infos <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $auditedMarketCount }}</h3>
                    <p>Marchés à auditer</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="{{ route('attributaires.index') }}" class="small-box-footer">Plus d'infos <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $totalMarketCount }}</h3>
                    <p>Marchés</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="{{ route('attributaires.index') }}" class="small-box-footer">Plus d'infos <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    </div>
    <!-- Charts -->
    <div class="row">
        <div class="col-md-6">
            <!-- DONUT CHART -->
            <div class="card card-danger">
                <div class="card-header">
                    <h3 class="card-title">Marchés par mode de passation</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="donutChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- PIE CHART -->
            <div class="card card-danger">
                <div class="card-header">
                    <h3 class="card-title">Marché par seuil</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col (LEFT) -->
        <div class="col-md-6">
            <!-- BAR CHART -->
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Montants par mode de passation</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- STACKED BAR CHART -->
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Top 5 des attributaires</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="stackedBarChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col (RIGHT) -->
    </div>
    <!-- Additional content here -->
</div>
@endsection

@section('js')
<script src="{{ asset('js/Chart.min.js') }}"></script>

<script>
  $(function () {
    //-------------
    //- DONUT CHART -
    //-------------
    var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
    var donutData = {
        labels: @json($modePassationLabels),
        datasets: [
            {
                data: @json($modePassationData),
                backgroundColor: @json($backgroundColors),
            }
        ]
    }

    var donutOptions = {
      maintainAspectRatio : false,
      responsive : true,
    }
    new Chart(donutChartCanvas, {
      type: 'doughnut',
      data: donutData,
      options: donutOptions
    })

    //-------------
    //- PIE CHART -
    //-------------
    var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
    var pieData = {
        labels: @json($pieChartLabels),
        datasets: [
            {
                data: @json($pieChartData),
                backgroundColor: @json($pieChartColors),
            }
        ]
    };

    var pieOptions = {
        maintainAspectRatio: false,
        responsive: true,
    };

    new Chart(pieChartCanvas, {
        type: 'pie',
        data: pieData,
        options: pieOptions
    });

    //-------------
    //- BAR CHART -
    //-------------
    var barChartCanvas = $('#barChart').get(0).getContext('2d');
    var barChartData = {
        labels: @json($barChartLabels),
        datasets: [{
            label: 'Montant total par mode de passation',
            backgroundColor: 'rgba(60,141,188,0.9)',
            data: @json($barChartData)
        }]
    };

    var barChartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        tooltips: {
            callbacks: {
                label: function(tooltipItem, data) {
                    var label = data.labels[tooltipItem.index] || '';
                    var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                    var formattedValue = value.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' MRU'; // Format number with two decimal places
                    return label + ': ' + formattedValue;
                }
            }
        }
    };

    new Chart(barChartCanvas, {
        type: 'bar',
        data: barChartData,
        options: barChartOptions
    });
    //-------------
    //- STACKED BAR CHART -
    //-------------
    var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d');
    var stackedBarChartData = {
        labels: @json($stackedBarChartLabels),
        datasets: [{
            label: 'Nombre de marchés',
            backgroundColor: [
                'rgba(60,141,188,0.9)', 
                'rgba(210, 214, 222, 1)', 
                'rgba(244, 67, 54, 0.8)', 
                'rgba(76, 175, 80, 0.8)', 
                'rgba(255, 235, 59, 0.8)'],
            data: @json($stackedBarChartData)
        }]
    };

    var stackedBarChartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            xAxes: [
                { 
                    stacked: true,
                    ticks: {
                    callback: function(value) {
                        // Convert x-axis labels to uppercase
                        return value.toUpperCase();
                    }
        }, }],
            yAxes: [{ stacked: true }]
        },
        tooltips: {
            callbacks: {
                label: function(tooltipItem, data) {
                    var label = data.labels[tooltipItem.index] || '';
                    var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                    var formattedValue = value.toLocaleString(undefined, { minimumFractionDigits: 2}); // Format number with two decimal places
                    return label + ': ' + formattedValue;
                }
            }
        }
    };

    new Chart(stackedBarChartCanvas, {
        type: 'bar',
        data: stackedBarChartData,
        options: stackedBarChartOptions
    });

  })
</script>
@endsection
