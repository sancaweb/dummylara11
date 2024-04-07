<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityController extends Controller
{
    public function index()
    {

        $userAct = User::orderBy('name', 'ASC')->get();
        // $userAct = User::with('causer')->orderBy('name', 'ASC')->get();
        $logNameAct = Activity::select('log_name')->distinct()->get();

        $dataPage = [

            'pageTitle' => "Activity Log",
            'page' => 'activity',
            'userAct' => $userAct,
            'logNameAct' => $logNameAct

        ];

        return view('pages.activity.index', $dataPage);
    }

    public function show(Activity $activity)
    {

        if ($activity) {
            $dataJson['message'] = "Data Activity ditemukan";

            if ($activity->causer_id === null) {
                $user = "System";
            } else {
                $user = $activity->causer->name;
            }

            return ResponseFormat::success([
                'user' => $user,
                'log_name' => $activity->log_name,
                'description' => $activity->description,
                // 'properties' => $properties,
                'properties' => $activity->properties,
                'created_at' => $activity->created_at->translatedFormat('j F Y H:i:s'),
            ], "Data Activity ditemukan");
        } else {
            return ResponseFormat::error([
                'error' => "Data Activity tidak ditemukan"
            ], "Data Activity tidak ditemukan", 404);
        }
    }

    public function datatable(Request $request)
    {


        $columns = array(
            0 => 'activity_log.id',
            1 => 'user.name',
            2 => 'log_name',
            3 => 'description',
            4 => 'created_at',
        );

        $totalData = Activity::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        //custom data
        $userAct = $request->input('userAct');
        $logNameAct = $request->input('logNameAct');
        $descAct = $request->input('descAct');
        $dateRangeFilter = $request->input('dateRangeFilter');



        /** pengambilan query */
        $filter = false;
        $getActivities = Activity::with('causer');
        // $getActivities = Activity::offset($start)->limit($limit);

        if (!empty($userAct)) {
            if ($userAct == 'system') {
                $getActivities->where('causer_id', null);
            } else {
                $getActivities->where('causer_id', $userAct);
            }
            $filter = true;
        }


        if (!empty($logNameAct)) {
            $getActivities->where('log_name', $logNameAct);
            $filter = true;
        }

        if (!empty($dateRangeFilter)) {

            $exDateRange = explode('-', $dateRangeFilter);
            $pertamaRep = str_replace('/', '-', trim($exDateRange[0]));
            $duaRep = str_replace('/', '-', trim($exDateRange[1]));

            $waktuMulai =  Carbon::parse($pertamaRep)->format('Y-m-d');
            $waktuAkhir = Carbon::parse($duaRep)->format('Y-m-d');

            $getActivities->whereDate('created_at', '>=', $waktuMulai);
            $getActivities->whereDate('created_at', '<=', $waktuAkhir);

            $filter = true;
        }



        if (!empty($descAct)) {
            $getActivities->orWhere('description', 'LIKE', "%{$descAct}%");
            $filter = true;
        }

        if ($filter == true) {
            $totalFiltered = $getActivities->count();
        }

        if ($request->input('order.0.column') == 1) {
            $getActivitiesSort = $getActivities
                ->offset($start)
                ->limit($limit)
                ->get();

            if ($dir == 'desc') {
                $activities = $getActivitiesSort->sortByDesc(function ($query) {
                    return $query->causer->name;
                });
            } else {
                $activities = $getActivitiesSort->sortBy(function ($query) {
                    return $query->causer->name;
                });
            }
        } else {
            $activities = $getActivities
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        }

        $data = array();
        if (!empty($activities)) {
            $no = $start;
            foreach ($activities as $act) {
                $no++;
                // if ($act->causer_id === null) {
                //     $username = "System";
                // } else {
                //     $username = $act->causer->name;
                // }
                $nestedData['no'] = $no;
                // $nestedData['user'] = $username;
                $nestedData['user'] = $act->causer?->name;
                $nestedData['logName'] = $act->log_name;
                $nestedData['description'] = $act->description;
                $nestedData['created_at'] = $act->created_at->translatedFormat('j F Y H:i:s');
                $nestedData['action'] = '<button data-id="' . $act->id . '" class="btn btn-info btn-circle btn-detail">
                <i class="fas fa-search"></i>
                            </button>';

                $data[] = $nestedData;
            }
        }




        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered) . "test",
            "data"            => $data,
            "order"           => $order,
            "dir" => $dir,
            "start" => $start,
            "limit" => $limit
        );


        return response()->json($json_data, 200);
    }
}
