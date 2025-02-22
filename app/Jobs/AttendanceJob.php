<?php

namespace App\Jobs;

use AllowDynamicProperties;
use App\Models\AttendanceJobProgress;
use App\Models\Attendances;
use App\Models\FpDevice;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Jmrashed\Zkteco\Lib\ZKTeco;

#[AllowDynamicProperties] class AttendanceJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    protected FpDevice $fpDevice;
    protected $startDate;
    protected $endDate;
    public $timeout = 12000000;

    /**
     * Create a new job instance.
     */
    public function __construct($fpDevice, $startDate, $endDate)
    {
        $this->fpDevice = $fpDevice;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $zk = new ZKTeco($this->fpDevice->ip_address, 4370);
        $connected = $zk->connect();
        if ($connected) {
            $jobProgress = AttendanceJobProgress::create([
                'device_id' => $this->fpDevice->id,
                'status' => 'Pending',
            ]);
            $chunk = array_chunk($zk->getAttendance(), 100);
            foreach ($chunk as $item) {
                foreach ($item as $record) {
                    $recordDate = Carbon::parse($record['timestamp']);
                    if ($recordDate->between($this->startDate, $this->endDate)) {
                        $data = [
                            'sn' => $this->fpDevice->serial_number,
                            'table' => '999',
                            'stamp' => 'ATTLOG',
                            'employee_id' => $record['id'],
                            'timestamp' => $record['timestamp'],
                            'status1' => $record['type'],
                        ];
                        Attendances::create($data);
                    }
                }
            }

            $jobProgress->update([
                'status' => 'Sukses',
            ]);
        }
        $zk->disconnect();
    }
}
