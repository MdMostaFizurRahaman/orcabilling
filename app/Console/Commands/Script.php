<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Spatie\Regex\Regex;
use phpseclib\Net\SFTP;
use Illuminate\Console\Command;
use App\Http\Controllers\ParseLog;
use Illuminate\Support\Facades\DB;

class Script extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:script';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sftp = new SFTP('192.206.45.123');

        if (!$sftp->login('root', 'tb@sbc')) {
            throw new Exception('Login failed');
        }else{
            $sftp->chdir('/lib/tb/toolpack/setup/12358/test_log/');

            $files = preg_grep("/^(\.|\.\.)$|\.log$/", $sftp->nlist(), PREG_GREP_INVERT);
            rsort($files);
            $latestFiles = array_slice($files, 0, 10, true);

            foreach($latestFiles as $file){

                if($newCDR = $this->insertCdrRow($file))
                {
                    $file_name = DB::table('raw_process')->where('status', 0)->orderBy('file_name', 'desc')->pluck('file_name')->first();

                    $sftp->get($file_name, 'cdr.log.gz');

                    $this->updateCdrRow($file_name, $status = 2);

                    $this->uncompress('cdr.log.gz', 'cdr.log');

                    $parser = new ParseLog;

                    if($rowsCount = $parser->parse('cdr.log', $file_name))
                    {
                        $cdrRecord = DB::table('raw_process')->where('file_name', $file_name);
                        $oldStatus = $cdrRecord->first()->status;
                        $oldStatusReport = $cdrRecord->first()->status_report;
                        $newStatus = ($oldStatus = 2) ? 1 : $oldStatus;
                        $newStatusReport = $oldStatus = 2 ? NULL : $oldStatusReport;
                        $cdrRecord->update([
                            'status' => $newStatus,
                            'rows_count' => $rowsCount,
                            'status_report' => $newStatusReport,
                        ]);
                    }

                } else {
                    continue;
                }
            }
            return 'true';
        }
    }

    public function insertCdrRow ($file)
    {
        return $newRecord = DB::table('raw_process')->insertOrIgnore([
            'file_name' => $file,
            'status' => 0,
            'processed_time' => Carbon::now(),
        ]);
    }

    public function updateCdrRow ($file_name, $status)
    {
        return $cdrRow = DB::table('raw_process')->where('file_name', $file_name)->update([
            'status' => $status,
        ]);
    }

    public function uncompress($source, $destination)
    {
        $file = fopen($destination, "w");
        fwrite($file, implode("", gzfile($source)));
        fclose($file);
    }

}
