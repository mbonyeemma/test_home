@extends('layouts.dashboard')
@section('content')

<b>Data Archives</b>

<div class="well firstrow list">
    <!-- <div class="row">
        <div class="col-md-12">
            {{ Form::open(array('route' => 'new', 'class' => 'form-search pull-left', 'id' => 'samplelist')) }}
            {{ csrf_field() }}
            {{ Form::text('from', Request::get('from') , ['class' => 'input-field filter-date', 'id' => 'from', 'placeholder' => $firstDate]) }}
            {{ Form::text('to', Request::get('to'), ['class' => 'input-field filter-date', 'id' => 'to', 'placeholder' => $cur_date]) }}
            {{ Form::select('region', $regions, Request::get('region'),['class'=>'select2 selectdropdown autosubmitsearchform','id'=>'pop_region'])}}
            {{ Form::select('district', $districts, null,['class'=>'select2 selectdropdown','id'=>'district'])}}
            {{ Form::select('hubid', $hubs, Request::get('hubid'),['class'=>'select2 selectdropdown autosubmitsearchform','id'=>'hub'])}}

            <button type="submit" id="searchbutton" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-search"> Search</i></button>
            {{ Form::close() }}
        </div>
    </div> -->
</div>
<!-- <span class='label label-primary label-bs'>
    <strong>
        {{('From').' '.$from_set.' '.('To').' '.$to_set}}
    </strong>
</span> -->


<div class="panel panel-primary" style="margin-top: 20px;">
    <div class="panel-body">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#1" data-toggle="tab"><br> 2024</a>
            </li>
            <li>
                <a href="#2" data-toggle="tab"><br>2023</a>
            </li>
            <li>
                <a href="#5" data-toggle="tab"><br>2022</a>
            </li>
            <li>
                <a href="#6" data-toggle="tab"><br>2021</a>
            </li>
            <!-- <li>
                <a href="#3" data-toggle="tab"><br>2020</a>
            </li> -->
            <!-- <li>
                <a href="#4" data-toggle="tab"> <span id="volume-at-cphl"></span> Samples<br>DELIVERED AT CPHL</a>
            </li> -->
            <!-- <li>
                <a href="#3" data-toggle="tab">Reports</a>
            </li> -->
        </ul>
        <div class="tab-content ">
            <div class="tab-pane active" id="1">
                <div class="panel-body" style="overflow-x:auto;">
                    <div class="row">
                        <div class="panel panel-info col-sm-12">
                            <div class="row">
                                <div class="col">
                                    <div id="hubs" class="col-lg-12 ">
                                        <div class="card card-body">
                                            <div class="box-body table-responsive">
                                                <table id="tracking" class="table table-striped table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>File Name</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($files_2024 as $file)
                                                        <tr>
                                                            <td>{{ $file }}</td>
                                                            <td><a href="{{ asset('dataarchives/2024') }}/{{$file}}" target="_blank"> Download</a> </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="panel panel-info col-sm-7" id="container_map"></div> -->
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="2">
                <div class="panel-body" style="overflow-x:auto;">
                    <div class="row">
                        <div class="panel panel-info col-sm-12">
                            <div class="row">
                                <div class="col">
                                    <div id="hubs" class="col-lg-12 ">
                                        <div class="card card-body">
                                            <div class="box-body table-responsive">
                                                <table id="tracking_2023" class="table table-striped table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>File Name</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($files_2023 as $file)
                                                        <tr>
                                                            <td>{{ $file }}</td>
                                                            <td><a href="{{ asset('dataarchives/2023') }}/{{$file}}" target="_blank"> Download</a> </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="panel panel-info col-sm-7" id="container_map"></div> -->
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="3">
                <div class="panel-body" style="overflow-x:auto;">
                    <div class="row">
                        <div class="panel panel-info col-sm-12">
                            <div class="row">
                                <div class="col">
                                    <div id="hubs" class="col-lg-12 ">
                                        <div class="card card-body">
                                            <div class="box-body table-responsive">
                                                <table id="tracking_2022" class="table table-striped table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>File Name</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($files_2022 as $file)
                                                        <tr>
                                                            <td>{{ $file }}</td>
                                                            <td><a href="{{ asset('dataarchives/2022') }}/{{$file}}" target="_blank"> Download</a> </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="panel panel-info col-sm-7" id="container_map"></div> -->
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="4">
                <div class="panel-body" style="overflow-x:auto;">
                    <div class="row">
                        <div class="panel panel-info col-sm-12">
                            <div class="row">
                                <div class="col">
                                    <div id="hubs" class="col-lg-12 ">
                                        <div class="card card-body">
                                            <div class="box-body table-responsive">
                                                <table id="tracking_2021" class="table table-striped table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>File Name</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($files_2021 as $file)
                                                        <tr>
                                                            <td>{{ $file }}</td>
                                                            <td><a href="{{ asset('dataarchives/2021') }}/{{$file}}" target="_blank"> Download</a> </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="panel panel-info col-sm-7" id="container_map"></div> -->
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="5">
                <div class="panel-body" style="overflow-x:auto;">
                    <div class="row">
                        <div class="panel panel-info col-sm-12">
                            <div class="row">
                                <div class="col">
                                    <div id="hubs" class="col-lg-12 ">
                                        <div class="card card-body">
                                            <div class="box-body table-responsive">
                                                <table id="tracking" class="table table-striped table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>File Name</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($files_2022 as $file)
                                                        <tr>
                                                            <td>{{ $file }}</td>
                                                            <td><a href="{{ asset('dataarchives/2022') }}/{{$file}}" target="_blank"> Download</a> </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="panel panel-info col-sm-7" id="container_map"></div> -->
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="6">
                <div class="panel-body" style="overflow-x:auto;">
                    <div class="row">
                        <div class="panel panel-info col-sm-12">
                            <div class="row">
                                <div class="col">
                                    <div id="hubs" class="col-lg-12 ">
                                        <div class="card card-body">
                                            <div class="box-body table-responsive">
                                                <table id="tracking" class="table table-striped table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>File Name</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($files_2021 as $file)
                                                        <tr>
                                                            <td>{{ $file }}</td>
                                                            <td><a href="{{ asset('dataarchives/2021') }}/{{$file}}" target="_blank"> Download</a> </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="panel panel-info col-sm-7" id="container_map"></div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('page-js-script')
<!-- Mappppsss -->
<script src="https://code.highcharts.com/maps/highmaps.js"></script>
<script src="https://code.highcharts.com/maps/modules/exporting.js"></script>
<script src="https://code.highcharts.com/maps/modules/offline-exporting.js"></script>
<script src="https://code.highcharts.com/maps/modules/accessibility.js"></script>
<!-- Mappppsss -->

<!-- Stacked -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<!-- Stacked -->

<!-- <script src="{{ asset('js/highchart.js') }}"></script> -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<!-- <script src="{{ asset('js/exporting.js') }}"></script> -->
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script src="{{ asset('js/exporting-data.js') }}"></script>
<script src="{{ asset('js/accessibility.js') }}"></script>
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('js/jszip.min.js') }}"></script>
<script src="{{ asset('js/pdfmake.min.js') }}"></script>
<script src="{{ asset('js/vfs_fonts.js') }}"></script>
<script src="{{ asset('js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('js/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('js/jquery.stickytabs.js') }}"></script>

<!-- <script src="https://code.highcharts.com/highcharts.js"></script>
 --><!-- <script src="https://code.highcharts.com/modules/exporting.js"></script> -->
<!-- <script src="https://code.highcharts.com/modules/export-data.js"></script> -->
<!-- <script src="https://code.highcharts.com/modules/accessibility.js"></script> -->


<script type="text/javascript">
    var from = <?php echo json_encode($date_from) ?>;
    var to = <?php echo json_encode($date_to) ?>;
    var hubs = <?php echo json_encode($hub) ?>;
    var percentage = <?php echo json_encode($percentage) ?>;
    var thubs = <?php echo json_encode($total) ?>;
    var xaxis = <?php echo json_encode($x_sis) ?>;
    var vl_data = <?php echo json_encode($y_axis_vl); ?>;
    var vl = vl_data.map((i) => Number(i));

    var eid_data = <?php echo json_encode($y_axis_eid); ?>;
    var eid = eid_data.map((i) => Number(i));

    var covid_data = <?php echo json_encode($y_axis_covid); ?>;
    var covid = covid_data.map((i) => Number(i));

    var scd_data = <?php echo json_encode($y_axis_scd); ?>;
    var scd = scd_data.map((i) => Number(i));

    var genexpert_data = <?php echo json_encode($y_axis_genexpert); ?>;
    var gene = genexpert_data.map((i) => Number(i));

    var cbc_data = <?php echo json_encode($y_axis_cbc); ?>;
    var cbc = cbc_data.map((i) => Number(i));

    var cd4_data = <?php echo json_encode($y_axis_cd4); ?>;
    var cd4 = cd4_data.map((i) => Number(i));

    var eqa_resp_data = <?php echo json_encode($y_axis_eqa_responses); ?>;
    var eqa_resp = eqa_resp_data.map((i) => Number(i));

    var vhf_data = <?php echo json_encode($y_axis_vhf); ?>;
    var vhf = vhf_data.map((i) => Number(i));

    var evd_data = <?php echo json_encode($y_axis_evd); ?>;
    var evd = evd_data.map((i) => Number(i));
    var new_array_hubs_names = <?php echo json_encode($new_array_hubs_names); ?>;
    var region_data = <?php echo json_encode($region_data); ?>;


    (async () => {
        const topology = await fetch(
            'https://code.highcharts.com/mapdata/countries/ug/ug-all.topo.json'
        ).then(response => response.json());
        var hub_geocordinates = <?php echo $hub_geocordinates; ?>

        // Initialize the chart
        Highcharts.mapChart('container_map', {

            chart: {
                map: topology
            },

            title: {
                text: 'Location Of Hubs'
            },

            accessibility: {
                description: 'Map where hub locations have been defined using ' +
                    'latitude/longitude.'
            },

            mapNavigation: {
                enabled: true
            },

            tooltip: {
                headerFormat: '',
                pointFormat: '<b>{point.name}</b><br>Lat: {point.lat}, Lon: ' +
                    '{point.lon}'
            },

            series: [{
                // Use the gb-all map with no data as a basemap
                name: 'Uganda',
                borderColor: '#A0A0A0',
                nullColor: 'rgba(200, 200, 200, 0.3)',
                showInLegend: false
            }, {
                name: 'Separators',
                type: 'mapline',
                nullColor: '#ffe599',
                showInLegend: false,
                enableMouseTracking: false,
                accessibility: {
                    enabled: false
                }
            }, {
                // Specify points using lat/lon
                type: 'mappoint',
                name: 'Hubs',
                accessibility: {
                    point: {
                        valueDescriptionFormat: '{xDescription}. Lat: ' +
                            '{point.lat:.2f}, lon: {point.lon:.2f}.'
                    }
                },
                color: Highcharts.getOptions().colors[1],
                data: hub_geocordinates,
            }]
        });

    })();

    Highcharts.chart('container_pie', {
        chart: {
            type: 'pie'
        },
        title: {
            text: 'Hubs Tracking By Region'
        },
        tooltip: {
            valueSuffix: 'Hubs'
        },
        subtitle: {
            text: 'Number Of Hubs Per Region'
        },
        plotOptions: {
            series: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: [{
                    enabled: true,
                    distance: 20
                }, {
                    enabled: true,
                    distance: -40,
                    format: '{point.percentage:.0f}',
                    style: {
                        fontSize: '1.2em',
                        textOutline: 'none',
                        opacity: 0.7
                    },
                    filter: {
                        operator: '>',
                        property: 'percentage',
                        value: 10
                    }
                }]
            }
        },
        series: [{
            name: 'Hubs',
            colorByPoint: true,
            data: region_data
        }]
    });

    Highcharts.chart('container_stacked', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Hubs Tracking',
            align: 'left'
        },
        xAxis: {
            categories: new_array_hubs_names
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Facilities Visited | Not Visited'
            },
            stackLabels: {
                enabled: true
            }
        },
        legend: {
            align: 'left',
            x: 70,
            verticalAlign: 'top',
            y: 70,
            floating: true,
            backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: true
        },
        tooltip: {
            headerFormat: '<b>{category}</b><br/>',
            pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
        },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: true
                }
            }
        },
        series: [{
            name: 'Visited',
            data: [3, 5, 1, 13, 45, 45, 6, 4, 2, 252, 3, 5, 1, 13, 45, 45, 6, 4, 2, 252]
        }, {
            name: 'Not Visited',
            data: [14, 8, 8, 12, 3, 5, 1, 13, 45, 45, 6, 4, 2, 252, 3, 5, 1, 13, 45, 45, 6, 4, 2, 252]
        }]
    });

    Highcharts.chart('container', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'HUB VISITS'
        },
        xAxis: {
            categories: hubs
        },
        yAxis: {
            title: {
                text: 'Percentages'
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },
        tooltip: {
            pointFormat: '<b>{point.y:.1f} %</b> Visited'
        },
        plotOptions: {
            series: {
                allowPointSelect: true,
            },
            column: {
                dataLabels: {
                    enabled: true,
                }
            }
        },
        labels: {
            overFlow: 'justify'
        },
        series: [{
            name: 'Percentages',
            data: percentage
        }],
        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }
    });

    Highcharts.chart('graph', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'VOLUME OF SAMPLESsss DELIVERED AT HUB'
        },
        xAxis: {
            categories: xaxis
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Total Number of Samples'
            },
            stackLabels: {
                enabled: false,
                style: {
                    fontWeight: 'bold',
                    color: ( // theme
                        Highcharts.defaultOptions.title.style &&
                        Highcharts.defaultOptions.title.style.color
                    ) || 'gray'
                }
            }
        },
        legend: {
            align: 'right',
            x: -30,
            verticalAlign: 'top',
            y: 25,
            floating: true,
            backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },
        tooltip: {
            headerFormat: '<b>{point.x}</b><br/>',
            pointFormat: '{series.name}: {point.y}'
        },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: false
                }
            }
        },
        series: [{
            name: 'VL DBS',
            data: vl
        }, {
            name: 'EID',
            data: eid
        }, {
            name: 'COVID19',
            data: covid
        }, {
            name: 'EVD',
            data: evd
        }, {
            name: 'Sickle Cell',
            data: scd
        }, {
            name: 'VHF',
            data: vhf
        }, {
            name: 'TB',
            data: gene
        }, {
            name: 'CBC/FBC',
            data: cbc
        }, {
            name: 'CD4/CD8',
            data: cd4
        }, {
            name: 'EQA-Response',
            data: eqa_resp
        }]
    });
</script>
<script>
    $(document).ready(function() {
        //$('#listtable').DataTable();     
        $('#tracking').DataTable({
            dom: 'Bflrtip',
            buttons: [{
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                'colvis'
            ]
        });

        $('#tracking_2023').DataTable({
            dom: 'Bflrtip',
            buttons: [{
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                'colvis'
            ]
        });

        $('#tracking_2022').DataTable({
            dom: 'Bflrtip',
            buttons: [{
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                'colvis'
            ]
        });

        $('#tracking_2021').DataTable({
            dom: 'Bflrtip',
            buttons: [{
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                'colvis'
            ]
        });


        $('#nottrackingtable').DataTable({
            dom: 'Bflrtip',
            buttons: [

                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                'colvis'
            ]
        });

        $('#compatcphl').DataTable({
            dom: 'Bfrtip',
            buttons: [

                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                'colvis'
            ]
        });

        $('.filter-date').datepicker({
            format: 'mm/dd/yyyy',
            endDate: '+0d',
            autoclose: true
        });
        $('.select2').select2();

    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: "{{ url('volume/statistics') }}",
            success: function(data) {
                $('#volume').html(data);
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: "{{ url('/volume/cphl/statistics') }}",
            success: function(data) {
                $('#volume-at-cphl').html(data);
            }
        });
    });

    // $("#searchbutton").click(function(){

    function getFacilitiesTracking(hubid) {
        var date_from = $('#from').val();
        var date_to = $('#to').val();
        // Display Modal
        $('#facilitytracking').modal('show');

        // AJAX request
        $.ajax({
            url: '<?php echo url('tracking/facility_list'); ?>/' + hubid + '?date_from=' + date_from + '&date_to=' + date_to,
            type: 'GET',
            success: function(response) {
                console.log(response);
                // Add response in Modal body          
                $('.modal-body').html(response);
            }
        });
    }
    // });

    function notVisitedTracking(hubid) {
        var date_from = $('#from').val();
        var date_to = $('#to').val();

        // Display Modal
        $('#notvisited').modal('show');
        // AJAX request
        $.ajax({
            url: '<?php echo url('not_visited/facility'); ?>/' + hubid + '?date_from=' + date_from + '&date_to=' + date_to,
            type: 'GET',
            success: function(response) {
                console.log(response);
                // Add response in Modal body          
                $('.modal-body').html(response);
            }
        });
    }
</script>
<script type="text/javascript">
    $(document).ready(function() {
        // $('#from').change(function() {
        //     $('#date_from').val($(this).val());
        // });

        // $('#to').change(function() {
        //     $('#date_to').val($(this).val());
        // });
        // $("#searchbutton").click(function(){
        //     var date_from = $('#from').val();
        //alert(date_from);
        // })
    });
</script>

<script type="text/javascript">
    $('#pop_region').on('change', function() {
        var regionID = $(this).val();
        if (regionID) {
            $.ajax({
                url: '/regions/get_district_region/' + regionID,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(data) {
                    if (data) {
                        $('#district').empty();
                        $('#district').focus;
                        $('#district').append('<option value="">-- Select District--</option>');

                        $('#hub').empty();
                        $('#hub').focus;
                        $('#hub').append('<option value="">-- Select Hub--</option>');

                        $.each(data.hubs, function(key, value) {
                            $('select[name="hubid"]').append('<option value="' + key + '">' + value + '</option>');
                        });

                        $.each(data.districts, function(key, value) {
                            $('select[name="district"]').append('<option value="' + key + '">' + value + '</option>');
                        });
                    } else {
                        $('#district').empty();
                        $('#hub').empty();
                    }
                }
            });
        } else {
            $('#district').empty();
            $('#hub').empty();
        }
    });

    $('#district').on('change', function() {
        var districtID = $(this).val();
        if (districtID) {
            $.ajax({
                url: '/district/get_district_hub/' + districtID,
                type: "GET",
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(data) {
                    //console.log(data);
                    if (data) {

                        $('#hub').empty();
                        $('#hub').focus;
                        $('#hub').append('<option value="">-- Select Hub--</option>');
                        $.each(data.hub, function(key, value) {
                            $('select[name="hubid"]').append('<option value="' + key + '">' + value + '</option>');
                        });
                    } else {
                        $('#hub').empty();
                    }
                }
            });
        } else {
            $('#hub').focus;
        }
    });
</script>

<script type="text/javascript">
    var cphl_vl_data = <?php echo json_encode($y_axis_vl_cphl); ?>;
    var vl_cphl = cphl_vl_data.map((i) => Number(i));

    var cphl_eid_data = <?php echo json_encode($y_axis_eid_cphl); ?>;
    var eid_cphl = cphl_eid_data.map((i) => Number(i));

    var cphl_covid_data = <?php echo json_encode($y_axis_covid_cphl); ?>;
    var covid_cphl = cphl_covid_data.map((i) => Number(i));

    var cphl_scd_data = <?php echo json_encode($y_axis_scd_cphl); ?>;
    var scd_cphl = cphl_scd_data.map((i) => Number(i));

    Highcharts.chart('samplecphl', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'VOLUME OF SAMPLES DELIVERED AT CPHL'
        },
        xAxis: {
            categories: xaxis
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Total Number of Samples'
            },
            stackLabels: {
                enabled: false,
                style: {
                    fontWeight: 'bold',
                    color: ( // theme
                        Highcharts.defaultOptions.title.style &&
                        Highcharts.defaultOptions.title.style.color
                    ) || 'gray'
                }
            }
        },
        legend: {
            align: 'right',
            x: -30,
            verticalAlign: 'top',
            y: 25,
            floating: true,
            backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },
        tooltip: {
            headerFormat: '<b>{point.x}</b><br/>',
            pointFormat: '{series.name}: {point.y}'
        },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: false
                }
            }
        },
        series: [{
            name: 'VL',
            data: vl_cphl
        }, {
            name: 'EID',
            data: eid_cphl
        }, {
            name: 'COVID19',
            data: covid_cphl
        }, {
            name: 'Sickle Cell',
            data: scd_cphl
        }]
    });
</script>

<div class="modal fade" tabindex="-1" id="facilitytracking" role="dialog">
    <div class="modal-dialog " style="width:100%;max-width:1250px;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tracking Facility Details</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img class="img-responsive" src="<?php echo asset('img/loading.gif'); ?>" alt="Loading">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" id="notvisited" role="dialog">
    <div class="modal-dialog " style="width:100%;max-width:1250px;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Not Visited Facilities</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img class="img-responsive" src="<?php echo asset('img/loading.gif'); ?>" alt="Loading">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



@stop