<?php

namespace App\Http\Controllers\Master\General;

use App\Http\Controllers\Controller;
use App\Models\NationalHoliday;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NationalHolidayController extends Controller
{
    private NationalHoliday $nationalHoliday;

    public function __construct()
    {
        $this->nationalHoliday = new NationalHoliday();
    }


    /**
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('view', NationalHoliday::class);
        return view('pages.adms.national-holiday.index');
    }


    /**
     * @throws AuthorizationException
     */
    public function data(): JsonResponse
    {
        $this->authorize('view', NationalHoliday::class);
        $nationalHoliday = $this->nationalHoliday->getData();
        return response()->json($nationalHoliday);
    }


    public function search(Request $request): JsonResponse
    {
        $search = $request->input('search');
        $data = NationalHoliday::orderBy('date', 'asc')
            ->when(!empty($search), function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->paginate(10);

        return response()->json($data);
    }

    /**
     * @throws AuthorizationException
     * @throws GuzzleException
     */
    public function generateHoliday(): JsonResponse
    {
        $this->authorize('view', NationalHoliday::class);
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
                NationalHoliday::updateOrCreate([
                    'date' => $holiday['holiday_date'],
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
