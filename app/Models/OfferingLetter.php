<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

/**
 * 
 *
 * @property int $id
 * @property int|null $branch_id
 * @property int $contact_id
 * @property string $offering_number
 * @property string $date
 * @property string $attachment
 * @property string $foreword
 * @property string $notes
 * @property string $marketing_agent_name
 * @property string $marketing_agent_contact
 * @property string $file
 * @property int $status
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\Contact $contact
 * @property-read \App\Models\ServiceCategory|null $serviceCategory
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|OfferingLetter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfferingLetter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OfferingLetter query()
 * @method static \Illuminate\Database\Eloquent\Builder|OfferingLetter whereAttachment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferingLetter whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferingLetter whereContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferingLetter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferingLetter whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferingLetter whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferingLetter whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferingLetter whereForeword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferingLetter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferingLetter whereMarketingAgentContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferingLetter whereMarketingAgentName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferingLetter whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferingLetter whereOfferingNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferingLetter whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OfferingLetter whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
