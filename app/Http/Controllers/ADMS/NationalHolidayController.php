<?php

namespace App\Http\Controllers\ADMS;

use App\Http\Controllers\Controller;
use App\Models\NationalHoliday;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class NationalHolidayController extends Controller
{
    private NationalHoliday $nationalHoliday;

    public function __construct()
    {
        $this->nationalHoliday = new NationalHoliday();
    }


    public function index(): View
    {
        return view('pages.adms.national-holiday.index');
    }

    public function data(): JsonResponse
    {
        $nationalHoliday = $this->nationalHoliday->getData();
        return response()->json($nationalHoliday);
    }


    public function generateHoliday(): JsonResponse
    {
        $client = new Client();
        $year = Carbon::now()->year;
        $apiUrl = "https://api-harilibur.vercel.app/api";

        try {
            $response = $client->get($apiUrl);
            $data = json_decode($response->getBody(), true);


            $nationalHolidays = array_filter($data, function ($val) {
                return $val['is_national_holiday'] === true;
            });

            foreach ($nationalHolidays as $holiday) {
                NationalHoliday::upsert([
                    'date' => $holiday['holiday_date'],
                    'name' => $holiday['holiday_name'],
                ], [
                    'name' => $holiday['holiday_name'],
                ]);
            }

            return response()->json($data);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }
}
