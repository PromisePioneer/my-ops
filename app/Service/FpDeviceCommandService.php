<?php

namespace App\Service;

use App\Models\FpDevice;
use App\Models\FPDeviceCommand;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FpDeviceCommandService
{
    public function getFpDeviceData(Request $request): array
    {
        $search = $request->input('search');
        $fpDevice = FpDevice::with('branch')
            ->orderBy('id', 'desc')->when($request->user()->hasRole('Branch Manager'), function ($query) use ($request) {
                $query->where('branch_id ', $request->user()->branch_id);
            });
        if (!empty($search)) {
            $fpDevice->where('serial_number', 'like', '%' . $search . '%')
                ->orWhereHas('branch', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                });
        }

        $device = $fpDevice->get();

        return $device->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->serial_number . ' - ' . $item->name
                ,
            ];
        })->toArray();
    }

    public function generateCommandId()
    {
        $lastCommand = FPDeviceCommand::orderBy('id', 'desc')->latest()->first();
        return $lastCommand ? $lastCommand->id + 1 : 1;
    }


    /**
     * @throws \Exception
     */
    public function storeCommands(Request $request, $userId = null)
    {
        $data = $request->all();
        $data['start_date'] = $request->start_date;
        $data['end_date'] = $request->end_date;
        $data['user_id'] = $userId;
        $data['command_id'] = $this->generateCommandId();
        $data['status'] = 1;
        $data['device_id'] = $request->device_id;


        $runningCommands = FPDeviceCommand::where('user_id', $userId)->where('status', true)->first();


        if (!empty($runningCommands)) {
            throw new \Exception('Command sedang berjalan, silahkan hapus command sebelumnya');
        }


        if ($request->type === 'Tarik Data Absen Dari Mesin Per Karyawan') {
            $data['command'] = sprintf(
                'C:%d:DATA QUERY ATTLOG SN=%s PIN=%d\tStartTime=%s\tEndTime=%s',
                $this->generateCommandId(),
                FpDevice::where('id', $data['device_id'])->first()->sn,
                $userId,
                date("Y-m-d\TH:i:s", strtotime($data['start_date'])),
                date("Y-m-d\TH:i:s", strtotime($data['end_date'])),
            );
        }

        if ($request->type === 'Tarik Data Absen Dari Mesin') {
            $data['command'] = sprintf(
                'C:%d:DATA QUERY ATTLOG \tStartTime=%s\tEndTime=%s',
                $userId,
                date("Y-m-d\TH:i:s", strtotime($data['start_date'])),
                date("Y-m-d\TH:i:s", strtotime($data['end_date'])),
            );
        }

        return FPDeviceCommand::create($data);
    }

    public function queryAttendanceLog(): Application|Response|string|ResponseFactory
    {
        $command = FPDeviceCommand::with('device')
            ->whereIn('type', ['Tarik Data Absen Dari Mesin', 'Tarik Data Absen Dari Mesin Per Karyawan'])->where('status', true)
            ->get();

        if (!empty($command)) {
            foreach ($command as $cmd) {
                return response($cmd->command, 200)
                    ->header('Content-Type', 'text/plain');
            }
        }

        return "OK";
    }
}
