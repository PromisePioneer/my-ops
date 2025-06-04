<?php

namespace App\Support\UserDeduction;

use App\Models\AdditionalDeduction;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use function App\Helper\formatDate;

class AdditionalDeductionService
{
    private static int $perPage = 10;
    public function data(): LengthAwarePaginator
    {
        $data = AdditionalDeduction::with('user')
            ->paginate(self::$perPage);
        return self::formattedData($data);
    }

    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $data = AdditionalDeduction::with('user')->when('search', function ($query) use ($search) {
            $query->whereHas('user', function ($query) use ($search) {
                $query->where('nip', 'like', '%' . $search . '%')
                    ->orWhere('name', 'like', '%' . $search . '%');
            });
        })->paginate(self::$perPage);

        return self::formattedData($data);
    }


    private static function formattedData(LengthAwarePaginator $additionalDeduction): LengthAwarePaginator
    {
        $data = $additionalDeduction->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'date' => formatDate($item->date),
                'user_name' => $item->user->name,
                'type' => $item->type,
                'amount' => number_format($item->amount),
            ];
        });

        $additionalDeduction->setCollection($data);
        return $additionalDeduction;
    }
}
