<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payroll\BPJSKetRequest;
use App\Models\BPJSKet;
use Illuminate\Http\JsonResponse;

class BPJSKetController extends Controller
{
    public function data(): JsonResponse
    {
        $bpjsKet = BpjsKet::all();
        return response()->json($bpjsKet);
    }

    public function edit(BPJSKet $bpjsKet): JsonResponse
    {
        return response()->json($bpjsKet);
    }

    public function update(BPJSKetRequest $request, BPJSKet $bpjsKet): JsonResponse
    {
        $bpjsKet->update($request->validated());

        return response()->json([
            'message' => 'Data berhasil diubah.',
        ]);
    }
}
