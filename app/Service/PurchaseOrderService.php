<?php

namespace App\Service;

use App\Http\Requests\PORequest;
use App\Models\Contact;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Throwable;
use function App\Helper\convertToRoman;

class PurchaseOrderService
{
    private static int $perPage = 10;


    private static function generatePurchaseOrderNumber(Request $request): string
    {
        $po = PurchaseOrder::where('contact_id', $request->contact_id)
            ->latest()
            ->first();

        $companyCode = Contact::where('id', $request->contact_id)->first()->company_code;
        $month = convertToRoman(Carbon::parse($request->date)->format('m'));
        $year = Carbon::parse($request->date)->format('Y');


        if ($po) {
            $convertInvNumberToArray = explode('/', $po->fab_number);
            $startingNumber = $convertInvNumberToArray[0];
            $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);

            return $startValue . '/' . 'PO/' . 'MYT-' . $companyCode . '/' . $month . '/' . $year;
        }

        $startingNumber = '000';
        $startValue = str_pad((int)$startingNumber + 1, 3, '0', STR_PAD_LEFT);

        return $startValue . '/' . 'PO/' . 'MYT-' . $companyCode . '/' . $month . '/' . $year;
    }

    public function data(): LengthAwarePaginator
    {
        $data = PurchaseOrder::with('contact')->paginate(self::$perPage);
        return self::formattedData($data);
    }


    public function search(Request $request): LengthAwarePaginator
    {
        $search = $request->input('search');
        $data = PurchaseOrder::with('contact')
            ->when(!empty($search), function ($query, $search) {
                $query->where('subject', 'like', '%' . $search . '%')
                    ->orWhere('po_number', 'like', '%' . $search . '%')
                    ->orWhereHas('contact', function ($query) use ($search) {
                        $query->where('pic_name', 'like', '%' . $search . '%')
                            ->orWhere('company_name', 'like', '%' . $search . '%');
                    })->orWhereHas('picName', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    });
            })->paginate(self::$perPage);

        return self::formattedData($data);
    }

    private static function formattedData($data): LengthAwarePaginator
    {
        $poData = $data->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'subject' => $item->subject,
                'contact' => $item->contact->company_name . '-' . $item->contact->pic_name,
                'date' => $item->date,
                'po_number' => $item->po_number,
                'pic' => $item->picName->name,
                'status' => $item->status,
            ];
        });

        $data->setCollection($poData);
        return $data;
    }


    /**
     * @throws Throwable
     */
    public function store(PORequest $request): void
    {
        DB::transaction(function () use ($request) {
            $data = $request->validated();
            $data['po_number'] = self::generatePurchaseOrderNumber($request);
            $po = PurchaseOrder::create($data);
            $this->purchaseOrderItemStoreOrUpdate($request, $po);
        });
    }


    /**
     * @throws Throwable
     */
    public function update(PORequest $request, PurchaseOrder $purchaseOrder): void
    {
        DB::transaction(function () use ($request, $purchaseOrder) {
            $data = $request->validated();
            $data['po_number'] = self::generatePurchaseOrderNumber($request);
            $purchaseOrder->update($data);
            PurchaseOrderItem::whereIn('po_id', $purchaseOrder->id)->delete();
            $this->purchaseOrderItemStoreOrUpdate($request, $purchaseOrder);
        });
    }


    public function purchaseOrderItemStoreOrUpdate($request, $po): void
    {
        foreach ($request['data'] as $key => $value) {
            $value['po_id'] = $po->id;
            PurchaseOrderItem::create($value);
        }
    }
}
