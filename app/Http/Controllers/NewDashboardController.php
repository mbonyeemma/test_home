<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\TestType as TestType;
use \App\Models\District as District;
use \App\Models\Facility as Facility;
use Illuminate\Http\Response;
use DB;

class NewDashboardController extends Controller
{
    public function index(Request $request)
    {
        $hubs_map = Facility::whereColumn('facility.parentid', '=', 'facility.id')
            ->leftjoin('healthregion', 'healthregion.id', '=', 'facility.health_region')
            ->leftjoin('district', 'district.id', '=', 'facility.districtid')
            ->where('facility.id', '<', 10000)->get();

        $new_array_hubs = [];
        $new_array_hubs_names = [];
        foreach ($hubs_map as $hub_map) {
            $new_array = array('name' => (string)($hub_map->name), 'lat' => (float)($hub_map->latitude), 'lon' => (float)($hub_map->longitude));
            $new_array_hubs_name = $hub_map->name;
            array_push($new_array_hubs_names, $new_array_hubs_name);
            array_push($new_array_hubs, $new_array);
        }

        $hub_geocordinates = json_encode($new_array_hubs, true);
        $hubs = array_merge_maintain_keys(array('' => 'Hub'), getAllFacilities());

        $districts = array_merge_maintain_keys(array('' => 'Districts'), getAllDistricts());

        $regions = array_merge_maintain_keys(array('' => 'Region'), getAllRgions());
        //$new = array('' => 'Region')+\App\Models\Region::all()->pluck('name','id')->toArray();
        $samples = TestType::all();

        $period = getWeekEndDates();
        $from = $period['start'];
        $to = $period['end'];
        $cur_date = date('M d Y');

        //$firstDate = date( 'M d Y', strtotime( 'Last Monday'));
        $firstDate = date('M d Y', strtotime("Monday -2 week"));

        $date_from = $request->from;
        $newdate_from = date('M d Y', strtotime($date_from));
        $date_to = $request->to;
        $newdate_to = date('M d Y', strtotime($date_to));
        $selected_hub = $request->hubid;
        $selected_region = $request->region;
        $selected_district = $request->district;


        $from_set = isset($date_from) ? $newdate_from  : $firstDate;
        $to_set = isset($date_to) ? $newdate_to  : $cur_date;
        $selected_rgn = isset($selected_region) ? $selected_region : '';

        $where = '';
        $wherereg = '';
        $whereregion = '';
        // Define filters
        // $where_clause = "WHERE  YEARWEEK(DATE(cl.created_at), 1) = YEARWEEK(CURDATE(), 1)";
        $where_clause = "WHERE cl.created_at between date_sub(now(),INTERVAL 2 WEEK) and now()";

        if ($date_from != '' && $date_to != '') {
            $where_clause = " WHERE cl.created_at BETWEEN '" . getMysqlDateFormat($date_from) . "'  AND '" . getMysqlDateFormat($date_to) . "'";
        }
        if ($selected_region != '') {
            $wherereg = " WHERE r.id = $selected_region ";
            $whereregion = " AND r.id = $selected_region ";
        }
        if ($selected_hub != '') {
            $where = " AND f.id = $selected_hub ";
        }
        if ($selected_region != '' && $selected_hub != '') {
            $where = " AND f.id = $selected_hub ";
        }
        $all_hubs = "SELECT mp.hubname,mp.tfacilities AS tFacility,mp.region, dsttrkt FROM
        (SELECT fs.name as hubname,dk.parentid,region,tfacilities, dsttrkt FROM (
        SELECT xy.parentid,r.name as region,tfacilities, ds.name AS dsttrkt FROM (
        SELECT parentid, COUNT(id) AS tfacilities FROM facility
                WHERE parentid IN (SELECT parentid FROM facility f WHERE parentid < 10000 AND id = parentid
                GROUP BY parentid)
                GROUP BY parentid) as xy
        INNER JOIN facility f ON f.id = xy.parentid
        LEFT JOIN district ds on ds.id = f.districtid
        LEFT JOIN region r on r.id = ds.regionid 
        ) as dk
        INNER JOIN facility fs ON fs.id = dk.parentid) as mp";
        $data_hubs = DB::select($all_hubs);

        $tracking_hubs = "SELECT hubid,mp.hubname,mp.tfacilities AS tFacility, Nvisits,district,mp.region FROM
        (select f.parentid as hubid, COUNT(distinct cl.facilityid) AS Nvisits, count(distinct f.districtid) as district from checklogin cl
        INNER JOIN facility f ON f.id = cl.facilityid
        " . $where_clause . "
        group by hubid) as sy
        INNER JOIN (SELECT fs.name as hubname,dk.parentid,region,tfacilities FROM (
        SELECT xy.parentid,r.name as region,tfacilities FROM (
        SELECT parentid, COUNT(id) AS tfacilities FROM facility
                WHERE parentid IN (SELECT parentid FROM facility f WHERE parentid < 10000 AND id = parentid
                " . $where . "
                GROUP BY parentid)
                GROUP BY parentid) as xy
        INNER JOIN facility f ON f.id = xy.parentid
        LEFT JOIN district ds on ds.id = f.districtid
        LEFT JOIN region r on r.id = ds.regionid 
        " . $wherereg . ") as dk
        INNER JOIN facility fs ON fs.id = dk.parentid) as mp ON mp.parentid = sy.hubid";

        $data = DB::select($tracking_hubs);

        // $data = DB::select($tracking_hubs);

        $namelist = collect($data)->groupBy('region')->toArray();
        $namelist_1 = collect($data)->groupBy('hubid')->toArray();

        $region_data = [];
        $region_data_names = [];
        foreach ($namelist as $key => $value) {
            $my_data = [
                'name' => $key,
                'y' => count($value),
                'drilldown' => $key,
            ];
            array_push($region_data_names, $key);
            array_push($region_data, $my_data);
        }

        $region_hub_data_names = [];
        $region_hub_data_names_Mid_Northern = [];
        $region_hub_data_names_North_East = [];
        $region_hub_data_names_West_Nile = [];
        $region_hub_data_names_Mid_Eastern = [];
        $region_hub_data_names_Mid_Western = [];
        $region_hub_data_names_East_Central = [];
        $region_hub_data_names_Central_2 = [];
        $region_hub_data_names_South_Western = [];
        $region_hub_data_names_Central_1 = [];
        $region_hub_data_names_Kampala = [];

        foreach ($namelist as $key => $value) {
            $dataal = array();
            $my_data_hub = [
                'name' => $value[0]->region,
                'id' => $value[0]->region
            ];
            array_push($region_hub_data_names, $my_data_hub);
            for ($i = 0; $i < count($region_data_names); $i++) 
            {
                $dataa = [];
                if (($region_data_names[$i] == $key) && ($region_data_names[$i] = "Mid Northern")) 
                {
                    $dataa_inner = [
                        'data' =>
                        [
                            $value[0]->hubname,
                            $value[0]->Nvisits
                        ]
                    ];
                    array_push($region_hub_data_names_Mid_Northern, $dataa_inner);
                    // array_push($dataa, $dataa_inner); 
                    // array_push($my_data_hub, $dataa);                  
                } 
                // else if ($region_data_names[$i] === "Kampala") 
                // {
                //     // dd($region_data_names[$i]);
                //     $dataa_inner = [
                //         'data' =>
                //         [
                //             $value[0]->hubname,
                //             $value[0]->Nvisits
                //         ]
                //     ];
                //     array_push($region_hub_data_names_Kampala, $dataa_inner);
                //     // array_push($dataa, $dataa_inner); 
                //     // array_push($my_data_hub, $dataa);                  
                // }
                // array_push($dataal, $dataa_inner);   
            }
        }
        // dd($region_hub_data_names_Mid_Northern);

        $total = count($data);
        $hub = [];
        $nvisits = [];
        $totalfacility = [];
        $percentage = [];

        $all = [];
        $i = 0;

        foreach ($data as $value) {
            $hub[$i] = $value->hubname;
            $nvisits[$i] = $value->Nvisits;
            $totalfacility[$i] = $value->tFacility;
            $percentage[$i] = round(($nvisits[$i] / $totalfacility[$i]) * 100, 1);
            $i++;
        }

        // Hub not trucking

        $hubs_not_tracking = "SELECT HubName,District,region FROM (
            SELECT f.name AS HubName,f.id, ds.name as District,r.name as region
            FROM package p
            INNER JOIN facility f ON f.id = p.hubid
            LEFT JOIN district ds on ds.id = f.districtid
            LEFT JOIN region r on r.id = ds.regionid
            INNER JOIN (
            SELECT parentid as hubid, COUNT(id) AS tfacilities FROM facility
            WHERE parentid IN (
            SELECT parentid FROM facility WHERE parentid < 10000 AND id = parentid
            GROUP BY parentid)
            group by parentid) as ty ON ty.hubid = p.hubid
            group by f.name,ds.name,r.name,f.id ) as h WHERE h.id NOT IN (
            SELECT hubid FROM (
            SELECT hubid FROM
            (select f.parentid as hubid, COUNT(distinct cl.facilityid) AS Nvisits,count(distinct f.districtid) as district from checklogin cl
            INNER JOIN facility f ON f.id = cl.facilityid
             " . $where_clause . "
            group by hubid) as sy
            INNER JOIN ( SELECT fs.name as hubname,dk.parentid,region,tfacilities FROM (
            SELECT xy.parentid,r.name as region,tfacilities FROM (
            SELECT parentid, COUNT(id) AS tfacilities FROM facility
            WHERE parentid IN (SELECT parentid FROM facility WHERE parentid < 10000 AND id = parentid
            GROUP BY parentid)
            GROUP BY parentid) as xy
            INNER JOIN facility f ON f.id = xy.parentid
            LEFT JOIN district ds on ds.id = f.districtid
            LEFT JOIN region r on r.id = ds.regionid 
            ) as dk
            INNER JOIN facility fs ON fs.id = dk.parentid) as mp ON mp.parentid = sy.hubid) as py  where py.hubid = h.id)";


        $not_tracking = DB::select($hubs_not_tracking);

        $total_hubs_not_tracking = count($not_tracking);

        // Volume of samples reived at Hub

        $tracking_completed_at_cphl = "SELECT f.name as HubName,d.name as District, r.name as region from untracked_packages cl
                    INNER JOIN facility f ON f.id = cl.hubid
                    INNER JOIN district d on d.id = f.districtid
                    INNER JOIN region r on r.id = d.regionid
                    " . $where_clause . "
                    AND f.parentid < 10000 AND f.id = parentid
                    " . $whereregion . "
                    group by f.name, district, region";

        $tracked_at_cphl = DB::select($tracking_completed_at_cphl);


        $count_tracking_cphl = count($tracked_at_cphl);

        // Define the x-axis

        $x_axis = "SELECT month_created,month_full
                FROM
                (
                SELECT month_created,month_full,testtype,sum(numberofsamples) as total
                FROM (SELECT distinct pe.package_id, tt.name as testtype, p.numberofsamples,DATE_FORMAT(p.created_at , '%M%Y') as month_created,DATE_FORMAT(p.created_at , '%Y%m') as month_full from package p
                inner join packagemovement_events pe ON pe.package_id = p.id
                inner join testtypes tt ON p.test_type = tt.id
                where pe.location = p.hubid 
                AND p.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)) as tx
                group by 1,2,3
                order by month(1)) as alltx
                group by 1,2
                order by month(1)";


        $x_axis_data = DB::select($x_axis);
        $x_sis = [];
        $x = 0;
        // $xy = json_decode(json_encode($x_axis_data, true));
        foreach ($x_axis_data as $x_axis_value) {
            $x_sis[$x] = returnFormatedDate($x_axis_value->month_created);
            $x++;
        }

        // Define the Y-axis for volumes delivered at hubs

        $data_hub_volume = "SELECT month_created,month_full,testtype,total
                FROM
                (
                SELECT month_created,month_full,testtype,sum(numberofsamples) as total
                FROM (SELECT distinct pe.package_id, tt.name as testtype, p.numberofsamples,DATE_FORMAT(p.created_at , '%Y%m') as month_created,DATE_FORMAT(p.created_at , '%Y%M') as month_full from package p
                inner join packagemovement_events pe ON pe.package_id = p.id
                inner join testtypes tt ON p.test_type = tt.id
                where pe.location = p.hubid 
                AND p.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)) as tx
                group by 1,2,3
                order by month(1)) as alltx
                group by 1,2,3
                order by month(1)";
        // $row_data = DB::select($data_hub_volume);
        $row_data = [];
        // dd($data_hub_volume);

        // dd($row_data);
        $y_axis_vl = [];
        $y_axis_eid = [];
        $y_axis_covid = [];
        $y_axis_scd = [];
        $y_axis_genexpert = [];
        $y_axis_vhf = [];
        $y_axis_evd = [];
        $y_axis_cbc = [];
        $y_axis_cd4 = [];
        $y_axis_eqa_responses = [];

        foreach ($x_axis_data as $key => $x_axis_year_month) {
            foreach ($row_data as $key => $y_axis_row) {
                if ($x_axis_year_month->month_full == $y_axis_row->month_created) {
                    //get VL sample-type's total count
                    if ($y_axis_row->testtype  == 'VL PLASMA') {
                        $sample_type_total_count = ($y_axis_row->total == '') ? 0 : $y_axis_row->total;
                        array_push($y_axis_vl, $sample_type_total_count);
                    }
                    if ($y_axis_row->testtype  == 'EID HIV') {
                        $sample_type_total_count = ($y_axis_row->total == '') ? 0 : $y_axis_row->total;
                        array_push($y_axis_eid, $sample_type_total_count);
                    }
                    if ($y_axis_row->testtype  == 'COVID 19') {
                        $sample_type_total_count = ($y_axis_row->total == '') ? 0 : $y_axis_row->total;
                        array_push($y_axis_covid, $sample_type_total_count);
                    }
                    if ($y_axis_row->testtype  == 'Sickle Cell (SCD)') {
                        $sample_type_total_count = ($y_axis_row->total == '') ? 0 : $y_axis_row->total;
                        array_push($y_axis_scd, $sample_type_total_count);
                    }
                    if ($y_axis_row->testtype  == 'Genexpert') {
                        $sample_type_total_count = ($y_axis_row->total == '') ? 0 : $y_axis_row->total;
                        array_push($y_axis_genexpert, $sample_type_total_count);
                    }
                    if ($y_axis_row->testtype  == 'CBC/FBC') {
                        $sample_type_total_count = ($y_axis_row->total == '') ? 0 : $y_axis_row->total;
                        array_push($y_axis_cbc, $sample_type_total_count);
                    }
                    if ($y_axis_row->testtype  == 'CD4/CD8') {
                        $sample_type_total_count = ($y_axis_row->total == '') ? 0 : $y_axis_row->total;
                        array_push($y_axis_cd4, $sample_type_total_count);
                    }
                    if ($y_axis_row->testtype  == 'EQA - Responses') {
                        $sample_type_total_count = ($y_axis_row->total == '') ? 0 : $y_axis_row->total;
                        array_push($y_axis_eqa_responses, $sample_type_total_count);
                    }
                    if ($y_axis_row->testtype  == 'VHF') {
                        $sample_type_total_count = ($y_axis_row->total == '') ? 0 : $y_axis_row->total;
                        array_push($y_axis_vhf, $sample_type_total_count);
                    }
                    if ($y_axis_row->testtype  == 'EVD') {
                        $sample_type_total_count = ($y_axis_row->total == '') ? 0 : $y_axis_row->total;
                        array_push($y_axis_evd, $sample_type_total_count);
                    }
                }
            }
        }
        // $q = "select * from vl_tracking_codes";

        // $users = DB::connection('mysql2')->select($q);
        // dd($users);

        //dd($y_axis_eid);

        // 0704231724

        //$y_axis_vl2=[3, 4, 7, 20, 5, 6, 4, 9, 7, 40, 30, 25];

        //dd($y_axis_vl2);

        // Sample delivered at cphl

        $cphl_delivered_volume = "SELECT month_created,month_full,testtype,total
                FROM
                (
                SELECT month_created,month_full,testtype,sum(numberofsamples) as total
                FROM (SELECT distinct pe.package_id, tt.name as testtype, p.numberofsamples,DATE_FORMAT(p.created_at , '%Y%m') as month_created,DATE_FORMAT(p.created_at , '%Y%M') as month_full from package p
                inner join packagemovement_events pe ON pe.package_id = p.id
                inner join testtypes tt ON p.test_type = tt.id
                where pe.location = 2490 
                AND p.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)) as tx
                group by 1,2,3
                order by month(1)) as alltx
                group by 1,2,3
                order by month(1)";
        // $result = DB::select($cphl_delivered_volume);
        $result = [];

        $y_axis_vl_cphl = [];
        $y_axis_eid_cphl = [];
        $y_axis_covid_cphl = [];
        $y_axis_scd_cphl = [];
        foreach ($x_axis_data as $key => $x_axis_year_months) {
            foreach ($result as $key => $y_axis_rows) {
                if ($x_axis_year_months->month_full == $y_axis_rows->month_created) {

                    //get VL sample-type's total count
                    if ($y_axis_rows->testtype  == 'VL') {
                        $sample_type_total_counts = ($y_axis_rows->total == '') ? 0 : $y_axis_rows->total;
                        array_push($y_axis_vl_cphl, $sample_type_total_counts);
                    }
                    if ($y_axis_rows->testtype  == 'EID HIV') {
                        $sample_type_total_counts = ($y_axis_rows->total == '') ? 0 : $y_axis_rows->total;
                        array_push($y_axis_eid_cphl, $sample_type_total_counts);
                    }
                    if ($y_axis_rows->testtype  == 'COVID 19') {
                        $sample_type_total_counts = ($y_axis_rows->total == '') ? 0 : $y_axis_rows->total;
                        array_push($y_axis_covid_cphl, $sample_type_total_counts);
                    }
                    if ($y_axis_rows->testtype  == 'Sickle Cell (SCD)') {
                        $sample_type_total_count = ($y_axis_rows->total == '') ? 0 : $y_axis_rows->total;
                        array_push($y_axis_scd_cphl, $sample_type_total_count);
                    }
                }
            }
        }

        return view('newdashboard.index', compact(
            'data',
            'hub',
            'percentage',
            'total',
            'x_axis_data',
            'samples',
            'date_from',
            'date_to',
            'cur_date',
            'from_set',
            'to_set',
            'y_axis_vl_cphl',
            'y_axis_eid_cphl',
            'y_axis_covid_cphl',
            'y_axis_scd_cphl',
            'not_tracking',
            'total_hubs_not_tracking',
            'tracked_at_cphl',
            'count_tracking_cphl',
            'selected_rgn',
            'hubs',
            'from',
            'to',
            'districts',
            'regions',
            'x_sis',
            'y_axis_vl',
            'y_axis_eid',
            'y_axis_covid',
            'y_axis_scd',
            'y_axis_genexpert',
            'y_axis_cbc',
            'y_axis_cd4',
            'y_axis_eqa_responses',
            'firstDate',
            'y_axis_vhf',
            'y_axis_evd',
            'hub_geocordinates',
            'new_array_hubs_names',
            'region_data',
            'data_hubs',
            'region_hub_data_names'
        ));
    }

    public function dataarchives(Request $request)
    {
        $path_2024   = './dataarchives/2024';
        $files_2024 = scandir($path_2024);
        $files_2024 = array_diff(scandir($path_2024), array('.', '..'));

        $path_2023   = './dataarchives/2023';
        $files_2023 = scandir($path_2023);
        $files_2023 = array_diff(scandir($path_2023), array('.', '..'));

        $path_2022   = './dataarchives/2022';
        $files_2022 = scandir($path_2022);
        $files_2022 = array_diff(scandir($path_2022), array('.', '..'));

        $path_2021   = './dataarchives/2021';
        $files_2021 = scandir($path_2021);
        $files_2021 = array_diff(scandir($path_2021), array('.', '..'));

        $hubs_map = Facility::whereColumn('facility.parentid', '=', 'facility.id')
            ->leftjoin('healthregion', 'healthregion.id', '=', 'facility.health_region')
            ->leftjoin('district', 'district.id', '=', 'facility.districtid')
            ->where('facility.id', '<', 10000)->get();

        $new_array_hubs = [];
        $new_array_hubs_names = [];
        foreach ($hubs_map as $hub_map) {
            $new_array = array('name' => (string)($hub_map->name), 'lat' => (float)($hub_map->latitude), 'lon' => (float)($hub_map->longitude));
            $new_array_hubs_name = $hub_map->name;
            array_push($new_array_hubs_names, $new_array_hubs_name);
            array_push($new_array_hubs, $new_array);
        }
        $hub_geocordinates = json_encode($new_array_hubs, true);
        $hubs = array_merge_maintain_keys(array('' => 'Hub'), getAllFacilities());
        // dd($hubs);
        $districts = array_merge_maintain_keys(array('' => 'Districts'), getAllDistricts());

        $regions = array_merge_maintain_keys(array('' => 'Region'), getAllRgions());
        //$new = array('' => 'Region')+\App\Models\Region::all()->pluck('name','id')->toArray();
        $samples = TestType::all();

        $period = getWeekEndDates();
        $from = $period['start'];
        $to = $period['end'];
        $cur_date = date('M d Y');

        //$firstDate = date( 'M d Y', strtotime( 'Last Monday'));
        $firstDate = date('M d Y', strtotime("Monday -2 week"));

        $date_from = $request->from;
        $newdate_from = date('M d Y', strtotime($date_from));
        $date_to = $request->to;
        $newdate_to = date('M d Y', strtotime($date_to));

        // dd($date_from);

        $selected_hub = $request->hubid;
        //dd($selected_hub);
        $selected_region = $request->region;
        //dd($selected_region);
        $selected_district = $request->district;

        $from_set = isset($date_from) ? $newdate_from  : $firstDate;
        $to_set = isset($date_to) ? $newdate_to  : $cur_date;
        $selected_rgn = isset($selected_region) ? $selected_region : '';
        //dd($selected_rgn);

        $where = '';
        $wherereg = '';
        $whereregion = '';
        // Define filters
        // $where_clause = "WHERE  YEARWEEK(DATE(cl.created_at), 1) = YEARWEEK(CURDATE(), 1)";
        $where_clause = "WHERE cl.created_at between date_sub(now(),INTERVAL 2 WEEK) and now()";

        if ($date_from != '' && $date_to != '') {
            $where_clause = " WHERE cl.created_at BETWEEN '" . getMysqlDateFormat($date_from) . "'  AND '" . getMysqlDateFormat($date_to) . "'";
        }
        if ($selected_region != '') {
            $wherereg = " WHERE r.id = $selected_region ";
            $whereregion = " AND r.id = $selected_region ";
        }
        if ($selected_hub != '') {
            $where = " AND f.id = $selected_hub ";
        }
        if ($selected_region != '' && $selected_hub != '') {
            $where = " AND f.id = $selected_hub ";
        }

        $tracking_hubs = "SELECT hubid,mp.hubname,mp.tfacilities AS tFacility, Nvisits,district,mp.region FROM
        (select f.parentid as hubid, COUNT(distinct cl.facilityid) AS Nvisits, count(distinct f.districtid) as district from checklogin cl
        INNER JOIN facility f ON f.id = cl.facilityid
        " . $where_clause . "
        group by hubid) as sy
        INNER JOIN ( SELECT fs.name as hubname,dk.parentid,region,tfacilities FROM (
        SELECT xy.parentid,r.name as region,tfacilities FROM (
        SELECT parentid, COUNT(id) AS tfacilities FROM facility
                WHERE parentid IN (SELECT parentid FROM facility f WHERE parentid < 10000 AND id = parentid
                " . $where . "
                GROUP BY parentid)
                GROUP BY parentid) as xy
        INNER JOIN facility f ON f.id = xy.parentid
        LEFT JOIN district ds on ds.id = f.districtid
        LEFT JOIN region r on r.id = ds.regionid 
        " . $wherereg . ") as dk
        INNER JOIN facility fs ON fs.id = dk.parentid) as mp ON mp.parentid = sy.hubid";

        $data = [];
        $namelist = collect($data)->groupBy('region')->toArray();
        $region_data = [];
        foreach ($namelist as $key => $value) {
            $my_data = [
                'name' => $key,
                'y' => count($value),
            ];
            array_push($region_data, $my_data);
        }

        $total = count($data);
        $hub = [];
        $nvisits = [];
        $totalfacility = [];
        $percentage = [];

        $all = [];
        $i = 0;
        foreach ($data as $value) {
            $hub[$i] = $value->hubname;
            $nvisits[$i] = $value->Nvisits;
            $totalfacility[$i] = $value->tFacility;
            $percentage[$i] = round(($nvisits[$i] / $totalfacility[$i]) * 100, 1);
            $i++;
        }

        // Hub not trucking

        $hubs_not_tracking = "SELECT HubName,District,region FROM (
            SELECT f.name AS HubName,f.id, ds.name as District,r.name as region
            FROM package p
            INNER JOIN facility f ON f.id = p.hubid
            LEFT JOIN district ds on ds.id = f.districtid
            LEFT JOIN region r on r.id = ds.regionid
            INNER JOIN (
            SELECT parentid as hubid, COUNT(id) AS tfacilities FROM facility
            WHERE parentid IN (
            SELECT parentid FROM facility WHERE parentid < 10000 AND id = parentid
            GROUP BY parentid)
            group by parentid) as ty ON ty.hubid = p.hubid
            group by f.name,ds.name,r.name,f.id ) as h WHERE h.id NOT IN (
            SELECT hubid FROM (
            SELECT hubid FROM
            (select f.parentid as hubid, COUNT(distinct cl.facilityid) AS Nvisits,count(distinct f.districtid) as district from checklogin cl
            INNER JOIN facility f ON f.id = cl.facilityid
             " . $where_clause . "
            group by hubid) as sy
            INNER JOIN ( SELECT fs.name as hubname,dk.parentid,region,tfacilities FROM (
            SELECT xy.parentid,r.name as region,tfacilities FROM (
            SELECT parentid, COUNT(id) AS tfacilities FROM facility
            WHERE parentid IN (SELECT parentid FROM facility WHERE parentid < 10000 AND id = parentid
            GROUP BY parentid)
            GROUP BY parentid) as xy
            INNER JOIN facility f ON f.id = xy.parentid
            LEFT JOIN district ds on ds.id = f.districtid
            LEFT JOIN region r on r.id = ds.regionid 
            ) as dk
            INNER JOIN facility fs ON fs.id = dk.parentid) as mp ON mp.parentid = sy.hubid) as py  where py.hubid = h.id)";

        $not_tracking = [];
        $total_hubs_not_tracking = count($not_tracking);
        // dd($hubs_not_tracking);

        // Volume of samples reived at Hub2222222222222222

        $tracking_completed_at_cphl = "SELECT f.name as HubName,d.name as District, r.name as region from untracked_packages cl
                    INNER JOIN facility f ON f.id = cl.hubid
                    INNER JOIN district d on d.id = f.districtid
                    INNER JOIN region r on r.id = d.regionid
                    " . $where_clause . "
                    AND f.parentid < 10000 AND f.id = parentid
                    " . $whereregion . "
                    group by f.name, district, region";

        $tracked_at_cphl = [];
        // dd($tracked_at_cphl);
        $count_tracking_cphl = count($tracked_at_cphl);

        // Define the x-axis

        $x_axis = "SELECT month_created,month_full
                FROM
                (
                SELECT month_created,month_full,testtype,sum(numberofsamples) as total
                FROM (SELECT distinct pe.package_id, tt.name as testtype, p.numberofsamples,DATE_FORMAT(p.created_at , '%M%Y') as month_created,DATE_FORMAT(p.created_at , '%Y%m') as month_full from package p
                inner join packagemovement_events pe ON pe.package_id = p.id
                inner join testtypes tt ON p.test_type = tt.id
                where pe.location = p.hubid 
                AND p.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)) as tx
                group by 1,2,3
                order by month(1)) as alltx
                group by 1,2
                order by month(1)";
        $x_axis_data = [];
        $x_sis = [];
        $x = 0;
        // $xy = json_decode(json_encode($x_axis_data, true));
        foreach ($x_axis_data as $x_axis_value) {
            $x_sis[$x] = returnFormatedDate($x_axis_value->month_created);
            $x++;
        }

        // Define the Y-axis for volumes delivered at hubs

        $data_hub_volume = "SELECT month_created,month_full,testtype,total
                FROM
                (
                SELECT month_created,month_full,testtype,sum(numberofsamples) as total
                FROM (SELECT distinct pe.package_id, tt.name as testtype, p.numberofsamples,DATE_FORMAT(p.created_at , '%Y%m') as month_created,DATE_FORMAT(p.created_at , '%Y%M') as month_full from package p
                inner join packagemovement_events pe ON pe.package_id = p.id
                inner join testtypes tt ON p.test_type = tt.id
                where pe.location = p.hubid 
                AND p.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)) as tx
                group by 1,2,3
                order by month(1)) as alltx
                group by 1,2,3
                order by month(1)";
        $row_data = [];

        // dd($row_data);
        $y_axis_vl = [];
        $y_axis_eid = [];
        $y_axis_covid = [];
        $y_axis_scd = [];
        $y_axis_genexpert = [];
        $y_axis_vhf = [];
        $y_axis_evd = [];
        $y_axis_cbc = [];
        $y_axis_cd4 = [];
        $y_axis_eqa_responses = [];

        foreach ($x_axis_data as $key => $x_axis_year_month) {
            foreach ($row_data as $key => $y_axis_row) {
                if ($x_axis_year_month->month_full == $y_axis_row->month_created) {
                    //get VL sample-type's total count
                    if ($y_axis_row->testtype  == 'VL PLASMA') {
                        $sample_type_total_count = ($y_axis_row->total == '') ? 0 : $y_axis_row->total;
                        array_push($y_axis_vl, $sample_type_total_count);
                    }
                    if ($y_axis_row->testtype  == 'EID HIV') {
                        $sample_type_total_count = ($y_axis_row->total == '') ? 0 : $y_axis_row->total;
                        array_push($y_axis_eid, $sample_type_total_count);
                    }
                    if ($y_axis_row->testtype  == 'COVID 19') {
                        $sample_type_total_count = ($y_axis_row->total == '') ? 0 : $y_axis_row->total;
                        array_push($y_axis_covid, $sample_type_total_count);
                    }
                    if ($y_axis_row->testtype  == 'Sickle Cell (SCD)') {
                        $sample_type_total_count = ($y_axis_row->total == '') ? 0 : $y_axis_row->total;
                        array_push($y_axis_scd, $sample_type_total_count);
                    }
                    if ($y_axis_row->testtype  == 'Genexpert') {
                        $sample_type_total_count = ($y_axis_row->total == '') ? 0 : $y_axis_row->total;
                        array_push($y_axis_genexpert, $sample_type_total_count);
                    }
                    if ($y_axis_row->testtype  == 'CBC/FBC') {
                        $sample_type_total_count = ($y_axis_row->total == '') ? 0 : $y_axis_row->total;
                        array_push($y_axis_cbc, $sample_type_total_count);
                    }
                    if ($y_axis_row->testtype  == 'CD4/CD8') {
                        $sample_type_total_count = ($y_axis_row->total == '') ? 0 : $y_axis_row->total;
                        array_push($y_axis_cd4, $sample_type_total_count);
                    }
                    if ($y_axis_row->testtype  == 'EQA - Responses') {
                        $sample_type_total_count = ($y_axis_row->total == '') ? 0 : $y_axis_row->total;
                        array_push($y_axis_eqa_responses, $sample_type_total_count);
                    }
                    if ($y_axis_row->testtype  == 'VHF') {
                        $sample_type_total_count = ($y_axis_row->total == '') ? 0 : $y_axis_row->total;
                        array_push($y_axis_vhf, $sample_type_total_count);
                    }
                    if ($y_axis_row->testtype  == 'EVD') {
                        $sample_type_total_count = ($y_axis_row->total == '') ? 0 : $y_axis_row->total;
                        array_push($y_axis_evd, $sample_type_total_count);
                    }
                }
            }
        }

        // Sample delivered at cphl

        $cphl_delivered_volume = "SELECT month_created,month_full,testtype,total
                FROM
                (
                SELECT month_created,month_full,testtype,sum(numberofsamples) as total
                FROM (SELECT distinct pe.package_id, tt.name as testtype, p.numberofsamples,DATE_FORMAT(p.created_at , '%Y%m') as month_created,DATE_FORMAT(p.created_at , '%Y%M') as month_full from package p
                inner join packagemovement_events pe ON pe.package_id = p.id
                inner join testtypes tt ON p.test_type = tt.id
                where pe.location = 2490 
                AND p.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)) as tx
                group by 1,2,3
                order by month(1)) as alltx
                group by 1,2,3
                order by month(1)";
        $result = DB::select($cphl_delivered_volume);

        $y_axis_vl_cphl = [];
        $y_axis_eid_cphl = [];
        $y_axis_covid_cphl = [];
        $y_axis_scd_cphl = [];
        foreach ($x_axis_data as $key => $x_axis_year_months) {
            foreach ($result as $key => $y_axis_rows) {
                if ($x_axis_year_months->month_full == $y_axis_rows->month_created) {

                    //get VL sample-type's total count
                    if ($y_axis_rows->testtype  == 'VL') {
                        $sample_type_total_counts = ($y_axis_rows->total == '') ? 0 : $y_axis_rows->total;
                        array_push($y_axis_vl_cphl, $sample_type_total_counts);
                    }
                    if ($y_axis_rows->testtype  == 'EID HIV') {
                        $sample_type_total_counts = ($y_axis_rows->total == '') ? 0 : $y_axis_rows->total;
                        array_push($y_axis_eid_cphl, $sample_type_total_counts);
                    }
                    if ($y_axis_rows->testtype  == 'COVID 19') {
                        $sample_type_total_counts = ($y_axis_rows->total == '') ? 0 : $y_axis_rows->total;
                        array_push($y_axis_covid_cphl, $sample_type_total_counts);
                    }
                    if ($y_axis_rows->testtype  == 'Sickle Cell (SCD)') {
                        $sample_type_total_count = ($y_axis_rows->total == '') ? 0 : $y_axis_rows->total;
                        array_push($y_axis_scd_cphl, $sample_type_total_count);
                    }
                }
            }
        }

        return view('newdashboard.dataarchives', compact(
            'data',
            'hub',
            'percentage',
            'total',
            'x_axis_data',
            'samples',
            'date_from',
            'date_to',
            'cur_date',
            'from_set',
            'to_set',
            'y_axis_vl_cphl',
            'y_axis_eid_cphl',
            'y_axis_covid_cphl',
            'y_axis_scd_cphl',
            'not_tracking',
            'total_hubs_not_tracking',
            'tracked_at_cphl',
            'count_tracking_cphl',
            'selected_rgn',
            'hubs',
            'from',
            'to',
            'districts',
            'regions',
            'x_sis',
            'y_axis_vl',
            'y_axis_eid',
            'y_axis_covid',
            'y_axis_scd',
            'y_axis_genexpert',
            'y_axis_cbc',
            'y_axis_cd4',
            'y_axis_eqa_responses',
            'firstDate',
            'y_axis_vhf',
            'y_axis_evd',
            'hub_geocordinates',
            'new_array_hubs_names',
            'region_data',
            'files_2021',
            'files_2022',
            'files_2023',
            'files_2024'
        ));
    }

    public function getDownload(Request $request)
    {
        //PDF file is stored under project/public/download/info.pdf
        // $file="./download/info.pdf";
        return Response::download($file);
    }

    public function store() {}

    public function totalNumberofSamplesDeliveredAtHub()
    {
        $query = "SELECT distinct FORMAT(sum(p.numberofsamples), 0) as total from package p
                inner join packagemovement_events pe ON pe.package_id = p.id
                inner join testtypes tt ON p.test_type = tt.id
                where pe.location = p.hubid 
                AND p.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)";
        $total_samples = \DB::select($query);
        echo $total_samples[0]->total;
    }

    public function totalNumberofSamplesDeliveredAtCphl()
    {
        $query = "SELECT distinct FORMAT(sum(p.numberofsamples), 0) as total from package p
                inner join packagemovement_events pe ON pe.package_id = p.id
                inner join testtypes tt ON p.test_type = tt.id
                where pe.location = 2490 
                AND p.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)";
        $total_samples = \DB::select($query);
        echo $total_samples[0]->total;
    }


    public function getDistrictsRegion($regionid)
    {
        // $city = District::where('regionid',$regionid)->get();
        // return response()->json($city);

        // Both of these querries work......

        // $query = 'select d.name as district from district d
        //         inner join region r on r.id = d.regionid
        //         where r.id = '.$regionid;
        // $result = \DB::select($query);
        // return response()->json($result);

        $newquery = "SELECT f.id as hubid, f.name AS hubname,ds.id as districtID, ds.name as district FROM package p
                    INNER JOIN facility f ON f.id = p.hubid
                    LEFT JOIN district ds on ds.id = f.districtid
                    LEFT JOIN region r on r.id = ds.regionid
                    INNER JOIN (SELECT parentid, COUNT(id) AS tfacilities FROM
                            facility
                        WHERE parentid IN (SELECT parentid FROM facility WHERE parentid < 10000 
                                GROUP BY parentid)
                        GROUP BY parentid) AS x ON x.parentid = p.hubid
                    WHERE r.id = '" . $regionid . "'
                                    AND f.parentid = f.id
                    GROUP BY f.id,f.name, ds.name,ds.id,ds.name";
        $result = \DB::select($newquery);

        // dd($result);
        $arrayLength = count($result);

        $hub_arr = [];
        $dist_arr = [];


        for ($i = 0; $i < $arrayLength; $i++) {
            $hub_arr = array_merge_maintain_keys($hub_arr, [$result[$i]->hubid => $result[$i]->hubname]);
            $dist_arr = array_merge_maintain_keys($dist_arr, [$result[$i]->districtID => $result[$i]->district]);
        }

        $dist_hub_arr = ['hubs' =>  $hub_arr, 'districts' => $dist_arr];

        $data = json_encode($dist_hub_arr, JSON_FORCE_OBJECT);

        return $data;
    }


    public function getDistrictHubs($districtID)
    {
        $result = "SELECT k.id as hubid, k.name as hubname FROM (
        SELECT id, parentid,districtid, name from facility ff 
        WHERE ff.parentid < 10000 AND ff.id = ff.parentid) as k
        LEFT JOIN facility fc ON fc.parentid = k.parentid
        LEFT JOIN district ds on ds.id = k.districtid
        WHERE k.districtid = '" . $districtID . "'
        group by k.id, k.name
        order by k.name asc";

        $data = \DB::select($result);

        // dd($result);
        $arrayLength = count($data);

        $hub_arr = [];

        for ($i = 0; $i < $arrayLength; $i++) {
            $hub_arr = array_merge_maintain_keys($hub_arr, [$data[$i]->hubid => $data[$i]->hubname]);
        }

        $dist_hub_arr = ['hub' =>  $hub_arr];

        $hubs_data = json_encode($dist_hub_arr, JSON_FORCE_OBJECT);

        return $hubs_data;
    }

    public function showFacilitiesVisitedByHub(Request $request, $hub_id)
    {
        $date_from = $request->date_from;
        $date_to = $request->date_to;
        $where_clause = "WHERE cl.created_at between date_sub(now(),INTERVAL 2 WEEK) and now()";

        if ($date_from != '' && $date_to != '') {
            $where_clause = " WHERE cl.created_at BETWEEN '" . getMysqlDateFormat($date_from) . "'  AND '" . getMysqlDateFormat($date_to) . "'";
        }

        $query = "SELECT fc.name,fc.parentid FROM (
                select facilityid from checklogin cl
                INNER JOIN (select usr.id as userid,usr.name as username,stf.designation from users usr 
                INNER JOIN staff stf ON usr.id = stf.user_id
                ) as us ON us.userid = cl.staffid
                INNER JOIN facility f ON f.id = cl.facilityid
                " . $where_clause . "
                group by facilityid) as flog
                INNER JOIN facility fc ON fc.id = flog.facilityid
                where fc.parentid = $hub_id";
        $data = DB::select($query);
        $table_str = '
            <table id="facil" class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>Facilities Visited</th>  
                    </tr>
                </thead>
                <tbody>';
        foreach ($data as $facility) {
            $table_str .= '<tr>
                        <td>' . $facility->name . '</td>
                    </tr>';
        }
        $table_str .= '</tbody>
            </table>
        ';
        echo $table_str;
    }

    public function showFacilitesNotVisited(Request $request, $hub_id)
    {
        $date_from = $request->date_from;
        $date_to = $request->date_to;
        $where_clause = "WHERE cl.created_at between date_sub(now(),INTERVAL 2 WEEK) and now()";

        if ($date_from != '' && $date_to != '') {
            $where_clause = " WHERE cl.created_at BETWEEN '" . getMysqlDateFormat($date_from) . "'  AND '" . getMysqlDateFormat($date_to) . "'";
        }

        $query = "SELECT name From (
                    SELECT id, name from facility 
                    where parentid = $hub_id
                    and parentid < 10000) as hb WHERE hb.id NOT IN (
                    SELECT ids FROM (
                    SELECT fc.id as ids,fc.name,fc.parentid FROM (
                    select facilityid from checklogin cl
                    INNER JOIN (select usr.id as userid,usr.name as username,stf.designation from users usr 
                        INNER JOIN staff stf ON usr.id = stf.user_id
                        ) as us ON us.userid = cl.staffid
                        INNER JOIN facility f ON f.id = cl.facilityid
                     " . $where_clause . "
                    group by facilityid) as flog
                    INNER JOIN facility fc ON fc.id = flog.facilityid
                    where fc.parentid = $hub_id) as xy WHERE xy.ids = hb.id)";
        $result = DB::select($query);
        $table_str = '
            <table id="facil" class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>Facilities Not Visited</th>  
                    </tr>
                </thead>
                <tbody>';
        foreach ($result as $res) {
            $table_str .= '<tr>
                        <td>' . $res->name . '</td>
                    </tr>';
        }
        $table_str .= '</tbody>
            </table>
        ';
        echo $table_str;
    }

    public function getVisitdates()
    {
        $dates = "use sample_tracker;
select cl.thedate,f.name, f.parentid as hubid, fc.name, ds.name as District, r.name as Region from checklogin cl 
INNER JOIN (select usr.id as userid,usr.name as username,stf.designation from users usr 
                INNER JOIN staff stf ON usr.id = stf.user_id
                where stf.designation = 1) as us ON us.userid = cl.staffid
INNER JOIN facility f ON f.id = cl.facilityid
INNER JOIN facility fc ON fc.id = f.parentid
INNER JOIN district ds on ds.id = fc.districtid
INNER JOIN region r on r.id = ds.regionid
where cl.thedate between '2022-01-01' and '2022-03-31'

order by hubid";
    }
}
