<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class OfferingLetter extends Model
{
    use HasFactory;

    protected $table = 'offering_letters';

    protected $fillable = [
        'branch_id',
        'contact_id',
        'offering_number',
        'date',
        'attachment',
        'foreword',
        'notes',
        'marketing_agent_name',
        'marketing_agent_contact',
        'status',
        'file',
        'created_by',
    ];

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function serviceCategory(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'offering_letter_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    //eloquent
    public function getOfferingLettersBasedOnUserBranch(Request $request, int $perPage): LengthAwarePaginator
    {
        $offeringLetters = self::with('contact', 'user', 'branch')
            ->where('branch_id', $request->user()->branch_id)
            ->select('id', 'offering_number', 'status', 'created_at', 'created_by', 'contact_id', 'branch_id')
            ->paginate($perPage);

        self::formatOfferingLettersData($offeringLetters);

        return $offeringLetters;
    }

    public function searchOfferingLettersBasedOnUserBranch(Request $request, $perPage): LengthAwarePaginator
    {
        $offeringLetter = self::where('offering_number', 'like', '%'.$request->search.'%')
            ->orWhereHas('contact', function ($query) use ($request) {
                $query->where('full_name', 'like', '%'.$request->search.'%');
                $query->orWhere('company_name', 'like', '%'.$request->search.'%');
            })
            ->orWhere('date', 'like', '%'.$request->search.'%')
            ->orWhere('attachment', 'like', '%'.$request->search.'%')
            ->orWhere('foreword', 'like', '%'.$request->search.'%')
            ->orWhere('notes', 'like', '%'.$request->search.'%')
            ->orWhere('marketing_agent_name', 'like', '%'.$request->search.'%')
            ->orWhere('marketing_agent_contact', 'like', '%'.$request->search.'%')
            ->where('branch_id', Auth::user()->branch_id)
            ->paginate($perPage);

        self::formatOfferingLettersData($offeringLetter);

        return $offeringLetter;
    }

    private static function formatOfferingLettersData(LengthAwarePaginator $offeringLetter): LengthAwarePaginator
    {
        $formattedData = $offeringLetter->getCollection()->map(function ($offeringLetter) {
            return [
                'id' => $offeringLetter->id,
                'offering_number' => $offeringLetter->offering_number,
                'status' => $offeringLetter->status,
                'created_at' => $offeringLetter->created_at,
                'created_by' => $offeringLetter->user->name,
                'company_name' => $offeringLetter->contact->company_name,
                'branch' => $offeringLetter->branch?->name,
            ];
        });
        $offeringLetter->setCollection($formattedData);

        return $offeringLetter;
    }

    public function filteringDataBasedOnBranch(int $branchId, int $perPage): LengthAwarePaginator
    {
        $query = self::where('branch_id', $branchId)->paginate($perPage);

        return self::formatOfferingLettersData($query);
    }
}
