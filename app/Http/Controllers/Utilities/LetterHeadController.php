<?php

namespace App\Http\Controllers\Utilities;

use App\Http\Controllers\Controller;
use App\Http\Requests\Utilities\LetterHead\LetterHeadRequest;
use App\Models\LetterHead;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class LetterHeadController extends Controller
{
    public function index(): View
    {

        $letterHead = LetterHead::where('id', 1)->first();

        return view('pages.utilities.letter-head.index', compact('letterHead'));
    }

    public function update(LetterHeadRequest $request, LetterHead $letterHead): JsonResponse
    {

        $headerFile = $letterHead->header ?? null;
        $footerFile = $letterHead->footer ?? null;

        if ($request->file('header')) {
            $headerFile = $request->file('header')->store('images/letter-head', 'public');
        }

        if ($request->file('footer')) {

            $footerFile = $request->file('footer')->store('images/letter-head', 'public');
        }

        $letterHead->update([
            'header' => $headerFile ?? null,
            'footer' => $footerFile ?? null,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'data berhasil disimpan',
        ], 200);
    }
}
