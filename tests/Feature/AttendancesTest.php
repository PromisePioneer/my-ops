<?php

use App\Models\Attendances;
use App\Models\DeviceLog;
use App\Models\FpDevice;
use App\Models\User;
use App\Models\UserWorkTime;
use App\Models\WorkTime;
use App\Service\IclockService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = new IclockService();
});

test('handshake method creates device log and updates fp device', function () {
    $request = Request::create('/handshake', 'POST', [
        'SN' => '12345',
        'option' => 'some_option'
    ]);

    $response = $this->service->handshake($request);

    expect(DeviceLog::count())->toBe(1)
        ->and(FpDevice::count())->toBe(1)
        ->and($response)->toContain('GET OPTION FROM: 12345');
});

test('recieveRecords handles OPERLOG correctly', function () {
    $request = Request::create('/receive', 'POST', [
        'table' => 'OPERLOG'
    ], [], [], [], "line1\nline2\nline3");

    $response = $this->service->recieveRecords($request);

    expect($response)->toBe('OK: 3');
});

test('recieveRecords processes valid attendance records for check-in', function () {
    // Create necessary data
    $user = User::factory()->create(['absent_id' => '1001']);
    $shift = WorkTime::factory()->create([
        'name' => fake()->name,
        'clock_in' => '08:00:00',
        'clock_out' => '17:00:00',
        'time_to_checkin' => '08:00:00',
        'end_time_to_checkin' => '10:00:00',
        'time_to_checkout' => '17:00:00',
        'end_time_to_checkout' => '23:59:00',
    ]);
    UserWorkTime::factory()->create(['user_id' => $user->id, 'shift_id' => $shift->id]);

    $request = Request::create('/iclock/cdata/', 'POST', [
        'SN' => '12345',
        'table' => 'CHECKINOUT',
        'Stamp' => '9999'
    ], [], [], [], "1001\t2023-08-17 09:00:00\t0");

    $response = $this->service->recieveRecords($request);

    expect($response)->toBe('OK: 1')
        ->and(Attendances::count())->toBe(1)
        ->and(Attendances::first()->status1)->toBe(0);
});

test('recieveRecords processes valid attendance records for check-out', function () {
    // Create necessary data
    $user = User::factory()->create(['absent_id' => '1001']);
    $shift = WorkTime::factory()->create([
        'name' => fake()->name,
        'clock_in' => '08:00:00',
        'clock_out' => '17:00:00',
        'time_to_checkin' => '08:00:00',
        'end_time_to_checkin' => '10:00:00',
        'time_to_checkout' => '17:00:00',
        'end_time_to_checkout' => '23:59:00',
    ]);
    UserWorkTime::factory()->create(['user_id' => $user->id, 'shift_id' => $shift->id]);

    $request = Request::create('/receive', 'POST', [
        'SN' => '12345',
        'table' => 'CHECKINOUT',
        'Stamp' => '9999'
    ], [], [], [], "1001\t2023-08-17 18:00:00\t1");

    $response = $this->service->recieveRecords($request);

    expect($response)->toBe('OK: 1')
        ->and(Attendances::count())->toBe(1)
        ->and(Attendances::first()->status1)->toBe(1);
});

test('recieveRecords ignores invalid user shifts', function () {
    $request = Request::create('/receive', 'POST', [
        'SN' => '12345',
        'table' => 'CHECKINOUT',
        'Stamp' => '9999'
    ], [], [], [], "1001\t2023-08-17 09:00:00\t0");

    $response = $this->service->recieveRecords($request);

    expect($response)->toBe('OK: 0')->and(Attendances::count())->toBe(0);
});