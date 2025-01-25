<?php

namespace App\Service\User;

use App\Http\Requests\ADMS\AttendancesSummaryAssignSPRequest;
use App\Http\Requests\SPRequest;
use App\Models\SP;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use function App\Helper\convertToRoman;

class SpService
{
    private static int $perPage = 10;
    public function generateSpNumber(SPRequest|AttendancesSummaryAssignSPRequest $request): string
    {
        $sp = SP::latest()->first();
        $spMonth = convertToRoman(Carbon::parse($request->due_date)->format('m'));
        $spYear = Carbon::parse($request->due_date)->format('Y');

        if ($sp) {
            $convertInvNumberToArray = explode('/', $sp->sp_number);
            $startingNumber = $convertInvNumberToArray[0];
            $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);

            return $startValue.'/MY-SP/'.$spMonth.'/'.$spYear;
        }

        $startingNumber = '001';
        $startValue = str_pad((int)$startingNumber, 3, '0', STR_PAD_LEFT);



        return $startValue.'/MY-SP/'.$spMonth.'/'.$spYear;
    }


    public function data(Request $request): LengthAwarePaginator
    {
        $data = SP::with('createdBy', 'user', 'branch');


        if ($request->user()->hasRole('Branch Manager')) {
            $data->whereHas('branch', function ($query) use ($request) {
                $query->where('branch_id', $request->user()->branch_id);
            });
        }


        $sp = $data->paginate(self::$perPage);
        return self::formattedData($sp);
    }

    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');

        $sp = $this->query()->whereHas('user', function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
            $query->orWhere('nip', 'like', '%' . $search . '%');
        })->orWhere('sp_number', 'like', '%' . $search . '%')
            ->paginate(self::$perPage);

        return self::formattedData($sp);
    }

    private static function formattedData(LengthAwarePaginator $sp): LengthAwarePaginator
    {
        $formattedData = $sp->getCollection()->map(function ($item) {
            $isExpired = false;

            $spActive = $item->where('expired_if_has_new_sp', 0)->get();

            foreach ($spActive as $active) {
                if ($active?->id === $item->id) {
                    $isExpired = true;
                }

                if ($active?->id === $item->id && $item->end_date < Carbon::now()) {
                    $isExpired = false;
                }
            }

            return [
                'id' => $item->id,
                'branch_name' => $item->branch->name ?? null,
                'user_id' => "({$item->user->nip}) {$item->user->name}",
                'sp_number' => $item->sp_number,
                'date' => Carbon::parse($item->start_date)->format('d/m/Y') . ' - ' . Carbon::parse($item->end_date)->format('d/m/Y'),
                'expired' => $isExpired,
                'sp_type' => $item->sp_type,
                'punished_by' => $item->punishedBy?->name,
                'created_by' => $item->createdBy->name,
            ];
        })->values();

        $sp->setCollection($formattedData);
        return $sp;
    }


    public function update(SPRequest $request, SP $sp): void
    {

        $punishedBy = User::where('id', $request->punished_by)->first();


        $sp->update([
            'date' => $request->date,
            'branch_id' => $punishedBy->branch_id,
            'user_id' => $request->user_id,
            'sp_number' => $this->generateSpNumber($request),
            'sp_type' => $request->sp_type,
            'created_by' => $request->user()->id,
            'list_of_reason' => json_encode($request['data']),
            'punished_by' => $request->punished_by
        ]);
    }

    public function showSPDetail(SP $sp): array
    {
        $spData = $sp->with('user')->where('id', $sp->id)->first();

        return [
            'id' => $spData->id,
            'branch_name' => $spData->branch->name ?? null,
            'user_id' => "({$spData->user->nip}) {$spData->user->name}",
            'sp_number' => $spData->sp_number,
            'date' => Carbon::parse($spData->start_date)->format('d/m/Y') . ' - ' . Carbon::parse($spData->end_date)->format('d/m/Y'),
            'sp_type' => $spData->sp_type,
            'punished_by' => $spData->punishedBy?->name,
            'created_by' => $spData->createdBy->name,
        ];
    }


    public function getEmployeeData(Request $request)
    {
        $search = $request->input('search');

        $query = User::where('active', '=', 1)
            ->orderBy('name')
            ->select('id', 'name', 'nip');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('nip', 'like', '%' . $search . '%');
            });
        }


        if ($request->user()->hasRole('Branch Manager')) {
            $query->whereNot('id', $request->user()->id)
                ->where('branch_id', $request->user()->branch_id);
        }

        if ($request->user()->hasRole('Operational Manager', 'Super Admin')) {
            $query->get();
        }

        $users = $query->get();

        return $users->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->nip . ' ' . $item->name,
            ];
        })->toArray();
    }

    public function getSPPic(Request $request)
    {
        $search = $request->input('search');

        $query = User::with('roles')
            ->where('active', '=', 1)
            ->orderBy('name')
            ->select('id', 'name', 'nip');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('nip', 'like', '%' . $search . '%');
            });
        }

        if ($request->user()->branch_id === null || $request->user()->branch_id === 1) {
            $query->whereHas('roles', function ($q) {
                $q->where('name', 'Operational Manager');
            });
        }

        if (!empty($request->user()->branch_id) && $request->user()->branch_id !== 1) {
            $query->where('branch_id', $request->user()->branch_id)
                ->whereHas('roles', function ($q) {
                    $q->where('name', 'Branch Manager');
                })->whereNot('branch_id', 1);
        }


        $users = $query->get();

        return $users->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->nip . ' ' . $item->name,
            ];
        })->toArray();
    }

}
