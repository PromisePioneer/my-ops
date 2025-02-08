<?php

namespace App\Jobs;

use AllowDynamicProperties;
use App\Models\Attendances;
use App\Models\FpDevice;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

#[AllowDynamicProperties] class ProcessAttendance implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;


    protected FpDevice $fpDevice;
    protected $startDate;
    protected $endDate;
    protected $attLog;

    /**
     * Create a new job instance.
     */
    public function __construct(FpDevice $fpDevice, $startDate, $endDate, $attLog)
    {
        $this->fpDevice = $fpDevice;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->attLog = $attLog;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        foreach ($this->attLog as $record) {
            $recordDate = Carbon::parse($record['timestamp']);
            if ($recordDate->between($this->startDate, $this->endDate)) {
                Attendances::create([
                    'sn' => $this->fpDevice->serial_number,
                    'table' => '999',
                    'stamp' => 'ATTLOG',
                    'employee_id' => $record['id'],
                    'timestamp' => $record['timestamp'],
                    'status1' => $record['type'],
                ]);
            }
        }
    }
}
