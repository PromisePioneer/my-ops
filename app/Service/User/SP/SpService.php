<?php

namespace App\Service\User\SP;

use AllowDynamicProperties;
use App\Http\Requests\ADMS\AttendancesSummaryAssignSPRequest;
use App\Http\Requests\SPRequest;
use App\Models\SP;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use function App\Helper\convertToRoman;

#[AllowDynamicProperties] class SpService
{
    private static int $perPage = 10;
    public function __construct()
    {
        $this->SpRepository = new SPRepository();
    }

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
        $query = SPACLFilter::apply($this->SpRepository->mainQuery(), $request)->paginate(self::$perPage);
        return self::formattedData($query);
    }

    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');

        $sp = SP::search($search)->query(function ($query) use ($request) {
            $data = $query->join('users', 'users.id', '=', 'sp.user_id');
            SPACLFilter::apply($data, $request);
        })->paginate(self::$perPage);

        return self::formattedData($sp);
    }


    public function filter(Request $request): LengthAwarePaginator
    {
        $sp = SPQueryFilter::apply($this->SpRepository->mainQuery(), $request)->paginate(self::$perPage);
        return self::formattedData($sp);
    }

    private static function formattedData(LengthAwarePaginator $sp): LengthAwarePaginator
    {
        $formattedData = $sp->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'branch_name' => $item->branch->name ?? null,
                'user_id' => "({$item->user->nip}) {$item->user->name}",
                'sp_number' => $item->sp_number,
                'date' => Carbon::parse($item->start_date)->format('d/m/Y') . ' - ' . Carbon::parse($item->end_date)->format('d/m/Y'),
                'expired' => Carbon::parse($item->end_date)->greaterThan(Carbon::now()),
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
