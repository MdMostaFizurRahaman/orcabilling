<?php

namespace App\Http\Controllers\System;

use App\System\ActivityLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ActivityLogController extends Controller
{

    public function panel()
    {
        return view('pages.system.access-log-panel');
    }

    public function accessLogs(Request $request)
    {
        return view('pages.system.access_log');
    }

    /**
     * Display a datatable of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dataTable()
    {
        $activityLogs = ActivityLog::select(['id_log', 'log_dt', 'user_id', 'client_id', 'user_type', 'user_ip', 'action', 'status']);

        return DataTables::of($activityLogs)
            ->addColumn('view', function ($query) {
                return '<button data-id="' . $query->id_log . '" class="btn btn-sm btn-primary view"><i class="fas fa-binoculars"></i> View</button>';
            })
            ->addColumn('subject', function ($query) {
                return $query->subject()->username;
            })
            ->rawColumns(['subject', 'view'])
            ->make(true);
    }

    public function show($id)
    {
        return ActivityLog::whereIdLog($id)->with(['user' => function($query){
            $query->select('id', 'username');
        }])->first();
    }
}
