<?php

namespace App\Jobs;

use AllowDynamicProperties;
use App\Models\AttendanceJobProgress;
use App\Models\Attendances;
use App\Models\FpDevice;
use App\Support\Attendances\IclockService;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Jmrashed\Zkteco\Lib\ZKTeco;

#[AllowDynamicProperties] class AttendanceJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    protected FpDevice $fpDevice;
    protected $startDate;
    protected $endDate;
    public int $timeout = 0;
    public $tries = 3;
    protected AttendanceJobProgress $progress;
    public IclockService $iclockService;

    /**
     * Create a new job instance.
     */
    public function __construct($fpDevice, $startDate, $endDate)
    {
        $this->iclockService = new IclockService();
        $this->fpDevice = $fpDevice;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->progress = AttendanceJobProgress::create([
            'device_id' => $fpDevice->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'pending',
        ]);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $zk = new ZKTeco($this->fpDevice->ip_address, 4370);
            if ($zk->connect()) {
                $item = $zk->getAttendance();
                $startDate = Carbon::parse($this->startDate)->startOfDay();
                $endDate = Carbon::parse($this->endDate)->endOfDay();
                foreach ($item as $record) {
                    $recordDate = Carbon::parse(substr($record['timestamp'], 0, 10));
                    if ($recordDate->between($startDate, $endDate)) {
                        $data = [
                            'sn' => $this->fpDevice->serial_number,
                            'table' => 'ATTLOG',
                            'stamp' => '999',
                            'employee_id' => $record['id'],
                            'timestamp' => $record['timestamp'],
                            'status1' => $record['type'],
                        ];


                        $shift = $this->iclockService->getShiftForUser(
                            $data['employee_id'],
                            $data['timestamp'],
                            $data['status1']
                        );
                        $this->iclockService->processAttendanceRecord($data, $shift);
                    }
                }
            }
            $this->progress->update(['status' => 'Sukses']);
        } catch (\Exception $e) {
            Log::error("Attendance Job Failed: " . $e->getMessage());

            $this->progress->update([
                'status' => 'Gagal',
                'error_message' => $e->getMessage(),
            ]);
        }
    }
}
