<?php

namespace App\Http\Controllers\UserProfile\Utilities;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\LetterHeadRequest;
use App\Models\LetterHead;
use App\Models\WorkTime;
use App\Support\Master\Common\LetterHead\Repository\LetterHeadRepository;
use App\Support\Master\Common\LetterHead\Service\LetterHeadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

#[AllowDynamicProperties] class LetterHeadController extends Controller
{

    public function __construct()
    {
        $this->letterHeadService = new LetterHeadService();
    }


    public function index(): View
    {
        $letterHead = LetterHead::where('id', 1)->first();

        return view('pages.master.common.letter-head.index', compact('letterHead'));
    }


    public function data(Request $request): JsonResponse
    {
        return response()->json($this->letterHeadService->data($request));
    }


    public function store(LetterHeadRequest $request): JsonResponse
    {
        $this->letterHeadService->store($request);
        return response()->json(['message' => 'Data berhasil disimpan.']);
    }


    public function edit(LetterHead $letterHead)
    {
        return response()->json($letterHead);
    }


    /**
     * @throws \Throwable
     */
    public function setActive(Request $request, LetterHead $letterHead)
    {
        DB::transaction(function () use ($letterHead, $request) {
            LetterHead::where('is_active', true)
                ->where('company_id', $request->session()->get('company_session'))
                ->update(['is_active' => false]);
            $letterHead->update(['is_active' => true]);
        });

        return response()->json(['message' => 'Data berhasil disimpan.']);
    }

    public function update(LetterHeadRequest $request, LetterHead $letterHead): JsonResponse
    {
        $this->letterHeadService->update($request, $letterHead);
        return response()->json(['message' => 'Data berhasil disimpan.']);
    }


    public function destroy(Request $request, LetterHead $letterHead): JsonResponse
    {
        $letterHead->whereIn('id', $request->get('id'))->delete();
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}
