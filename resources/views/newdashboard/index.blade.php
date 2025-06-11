@extends('layouts.dashboard')
@section('content')

<b>FILTER HERE</b>

<div class="well firstrow list">
    <div class="row">
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
    </div>
</div>
<span class='label label-primary label-bs'>
    <strong>
        {{('From').' '.$from_set.' '.('To').' '.$to_set}}
    </strong>
</span>


<div class="panel panel-primary" style="margin-top: 20px;">
    <div class="panel-body">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#1" data-toggle="tab"><b>100 HUBS</b><br> MAP OF ALL HUBS</a>
            </li>
            <li>
                <a href="#2" data-toggle="tab"><b></b><br>Facility Visits</a>
            </li>
            <!-- <li>
                <a href="#5" data-toggle="tab"><b>{{$total_hubs_not_tracking}} HUBS </b><br>HUBS NOT TRACKING</a>
            </li> -->
            <li>
                <a href="#6" data-toggle="tab"><b> Facility </b><br>Visits</a>
            </li>
            <li>
                <a href="#3" data-toggle="tab"> <span id="volume"></span> Samples<br>DELIVERED AT HUBS</a>
            </li>
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
                        <div class="panel panel-info col-sm-7">
                            <div class="row">
                                <div class="col">
                                    <div id="hubs" class="col-lg-12 ">
                                        <div class="card card-body">
                                            <div class="box-body table-responsive">
                                                <table id="tracking" class="table table-striped table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Hub Name</th>
                                                            <th>Facilities <br> Served</th>
                                                            <th>District</th>
                                                            <th>Health Region</th>
                                                            <!-- <th>View Details</th> -->
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($data_hubs as $value)
                                                        <tr>
                                                            <td>{{ $value->hubname }}</td>
                                                            <td>{{ $value->tFacility }} </td>
                                                            <td>{{ $value->dsttrkt }}</td>
                                                            <td>{{ $value->region }}</td>
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
                        <div class="panel panel-info col-sm-5" id="container_map"></div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="2">
                <div class="card card-body col-sm-7">
                    <div class="box-body table-responsive">
                        <table id="nottrackingtable" class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>RRH</th>
                                    <th>Hub <br> Name</th>
                                    <th>Districts <br> Served</th>
                                    <th>Facilities <br> Served</th>
                                    <th>Facilities <br> ith 2 or more Visits</th>
                                    <th>(%) <br> of hounoured visit facilities</th>
                                    <th>Health <br>Region</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $value)
                                <tr>
                                    <td>{{ $value->hubname }}</td>
                                    <td>{{ $value->hubname }}</td>
                                    <td>{{ $value->district }}</td>
                                    <td>{{ $value->tFacility }}</td>
                                    <td>{{ $value->Nvisits }}</td>
                                    <td>{{ round(($value->Nvisits / $value->tFacility) * 100, 1) }}</td>
                                    <td>{{ $value->region }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-sm-5" id="container_drilld"></div>
            </div>
            <div class="tab-pane" id="3">
                <div class="panel-body" style="overflow-x:auto;">
                    <!--  Filter Here
                {{ Form::select('hubid', $hubs, null,['class'=>'select2 selectdropdown autosubmitsearchform'])}} 
                <button type="submit" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-download"> Download</i></button> -->
                    <div id="graph"></div>
                    SUMMARY OF SAMPLES DELIVERED AT HUBS
                    <br>
                    <!--                 <div class="panel panel-info">                    
                    <div class="card card-body">
                        <table id="listtable" class="table table-condensed table-sm">
                            <thead>
                                <tr>
                                    <th>Sample Type</th>
                                    @foreach($x_axis_data as $months)
                                    <th>{{ returnFormatedDate($months->month_created) }}</th>               
                                    @endforeach                              
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($samples as $sample)
                                    <tr>
                                        <td>{{ $sample->name }}</td>
                                        @foreach($x_axis_data as $months)
                                        <td></td>
                                        @endforeach    
                                    </tr> 
                                @endforeach                               
                            </tbody>                         
                        </table>
                    </div>
                </div> -->
                </div>
            </div>
            <div class="tab-pane" id="4">
                <div class="panel-body" style="overflow-x:auto;">
                    <div id="samplecphl"></div>
                </div>
            </div>
            <div class="tab-pane" id="5">
                <div class="card card-body">
                    <div class="box-body table-responsive">
                        <table id="compatcphl" class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Hub</th>
                                    <th>District</th>
                                    <th>Health Regions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- @foreach ($tracked_at_cphl as $tracked)
                                <tr>
                                    <td>{{ $tracked->HubName }}</td>
                                    <td>{{ $tracked->District }}</td>
                                    <td>{{ $tracked->region }}</td>
                                </tr>
                                @endforeach -->
                                @foreach ($not_tracking as $value)
                                <tr>
                                    <td>{{ $value->HubName }}</td>
                                    <td>{{ $value->District }}</td>
                                    <td>{{ $value->region }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="6">
                <div id="container_pie"></div>

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

<!-- drilldown -->
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<!-- drilldown -->

<!-- <script src="{{ asset('js/highchart.js') }}"></script> -->
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
    var region_hub_data_names = <?php echo json_encode($region_hub_data_names); ?>;


    Highcharts.chart('container_drilld', {
        chart: {
            type: 'pie'
        },
        title: {
            text: 'Regional Tracking Trends'
        },
        subtitle: {
            text: 'Click the slices to Each Regions Hubs And Facilities'
        },
        accessibility: {
            announceNewData: {
                enabled: true
            },
            point: {
                valueSuffix: 'Hubs'
            }
        },
        plotOptions: {
            series: {
                borderRadius: 5,
                dataLabels: [{
                    enabled: true,
                    distance: 15,
                    format: '{point.name}'
                }, {
                    enabled: true,
                    distance: '-30%',
                    filter: {
                        property: 'percentage',
                        operator: '>',
                        value: 5
                    },
                    format: '{point.y:.0f}',
                    style: {
                        fontSize: '0.9em',
                        textOutline: 'none'
                    }
                }]
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: ' +
                '<b>{point.y:.2f}%</b> of total<br/>'
        },
        series: [{
            name: 'Regions',
            colorByPoint: true,
            data: region_data
        }],
        drilldown: {
            series: [{
                    name: 'Kampala',
                    id: 'Kampala',
                    data: [
                        [
                            'Adilang HC III',
                            36.89
                        ],
                        [
                            'Alop HC II',
                            18.16
                        ],
                        [
                            'David Fagerlees Medical Centre',
                            0.54
                        ],
                        [
                            'Ligiligi HC II',
                            0.7
                        ],
                        [
                            'v93.0',
                            0.8
                        ],
                        [
                            'v92.0',
                            0.41
                        ],
                        [
                            'v91.0',
                            0.31
                        ],
                        [
                            'v90.0',
                            0.13
                        ],
                        [
                            'v89.0',
                            0.14
                        ],
                        [
                            'v88.0',
                            0.1
                        ],
                        [
                            'v87.0',
                            0.35
                        ],
                        [
                            'v86.0',
                            0.17
                        ],
                        [
                            'v85.0',
                            0.18
                        ],
                        [
                            'v84.0',
                            0.17
                        ],
                        [
                            'v83.0',
                            0.21
                        ],
                        [
                            'v81.0',
                            0.1
                        ],
                        [
                            'v80.0',
                            0.16
                        ],
                        [
                            'v79.0',
                            0.43
                        ],
                        [
                            'v78.0',
                            0.11
                        ],
                        [
                            'v76.0',
                            0.16
                        ],
                        [
                            'v75.0',
                            0.15
                        ],
                        [
                            'v72.0',
                            0.14
                        ],
                        [
                            'v70.0',
                            0.11
                        ],
                        [
                            'v69.0',
                            0.13
                        ],
                        [
                            'v56.0',
                            0.12
                        ],
                        [
                            'v49.0',
                            0.17
                        ]
                    ]
                },
                {
                    name: 'West Nile',
                    id: 'West Nile',
                    data: [
                        [
                            'v15.3',
                            0.1
                        ],
                        [
                            'v15.2',
                            2.01
                        ],
                        [
                            'v15.1',
                            2.29
                        ],
                        [
                            'v15.0',
                            0.49
                        ],
                        [
                            'v14.1',
                            2.48
                        ],
                        [
                            'v14.0',
                            0.64
                        ],
                        [
                            'v13.1',
                            1.17
                        ],
                        [
                            'v13.0',
                            0.13
                        ],
                        [
                            'v12.1',
                            0.16
                        ]
                    ]
                },
                {
                    name: 'Edge',
                    id: 'Edge',
                    data: [
                        [
                            'v97',
                            6.62
                        ],
                        [
                            'v96',
                            2.55
                        ],
                        [
                            'v95',
                            0.15
                        ]
                    ]
                },
                {
                    name: 'Firefox',
                    id: 'Firefox',
                    data: [
                        [
                            'v96.0',
                            4.17
                        ],
                        [
                            'v95.0',
                            3.33
                        ],
                        [
                            'v94.0',
                            0.11
                        ],
                        [
                            'v91.0',
                            0.23
                        ],
                        [
                            'v78.0',
                            0.16
                        ],
                        [
                            'v52.0',
                            0.15
                        ]
                    ]
                }
            ]
        }
    });

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
            text: 'Major trophies for some English teams',
            align: 'left'
        },
        xAxis: {
            categories: ['Arsenal', 'Chelsea', 'Liverpool', 'Manchester United']
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Count trophies'
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
            shadow: false
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
            name: 'BPL',
            data: [3, 5, 1, 13]
        }, {
            name: 'FA Cup',
            data: [14, 8, 8, 12]
        }, {
            name: 'CL',
            data: [0, 2, 6, 3]
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