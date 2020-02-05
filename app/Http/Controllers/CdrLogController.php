<?php

namespace App\Http\Controllers;

use phpseclib\Net\SFTP;
use Illuminate\Http\Request;
use App\Http\Controllers\ParseLog;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;


class CdrLogController extends Controller
{

    public function index()
    {
        return view('pages.cdrlogs.index');
    }

    public function getCdrLogs()
    {
        $cdrLogs = DB::table('raw_process')->select()->get();

        return DataTables::of($cdrLogs)
            ->addColumn('reparse', function($query){
                return '<button data-file_name="'.$query->file_name.'" class="btn btn-sm btn-success reparse"><i class="fa fa-recycle"></i> Reparse</button>';
            })
            ->addColumn('show',function ($query) {
                return '<button data-file_name="'.$query->file_name.'" class="btn btn-sm btn-info show"><i class="fa fa-binoculars"></i> Show</button>';
            })
            ->addColumn('file_name',function ($query) {
                return $query->file_name;
            })
            ->addColumn('rows_count',function ($query) {
                return $query->rows_count;
            })
            ->addColumn('processed_time',function ($query) {
                return $query->processed_time;
            })
            ->addColumn('status',function ($query) {
                return $query->status;
            })
           ->rawColumns(['show', 'reparse'])
            ->make(true);
    }

    public function show(Request $request, $file_name)
    {
        $cdrLog = DB::table('raw_process')->where('file_name', $file_name)->first();
        if($cdrLog->status_report != NULL)
        {
            $cdrLog->status_report = json_encode(unserialize($cdrLog->status_report));
        }
        return \json_encode($cdrLog);
    }

    public function reparse($file_name)
    {
        $sftp = new SFTP('192.206.45.123');

        if (!$sftp->login('root', 'tb@sbc')) {
            throw new Exception('Login failed');
        }else{
            $sftp->chdir('/lib/tb/toolpack/setup/12358/test_log/');

            $query = DB::table('raw_process')->where('file_name', $file_name);
            $file_exists = $query->where('status', 0)->orWhere('status', 2)->orWhere('status', 3)->exists();
            if($file_exists)
            {
                $sftp->get($file_name, 'cdr.log.gz');

                $this->uncompress('cdr.log.gz', 'cdr.log');

                $parser = new ParseLog;

                if($rowsCount = $parser->parse('cdr.log', $file_name))
                {
                    $cdrRecord = DB::table('raw_process')->where('file_name', $file_name);
                    $oldStatus = $cdrRecord->first()->status;
                    if($oldStatus = 2)
                    {
                        $cdrRecord->update([
                            'status' => 1,
                            'rows_count' => $rowsCount,
                            'status_report' => NULL,
                        ]);
                    }
                }
            } else {
                return $response = $this->composeResponse(false, 'This file is already parsed!');
            }

            return $response = $this->composeResponse(true, 'Log paresed successfully');
        }
    }

    public function composeResponse($status, $msg = NULL)
    {
        $response['status'] = $status;
        $response['msg'] = $msg;
        return $response;
    }

    public function uncompress($source, $destination)
    {
        $file = fopen($destination, "w");
        fwrite($file, implode("", gzfile($source)));
        fclose($file);
    }
}
