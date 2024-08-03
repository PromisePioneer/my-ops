<?php

namespace App\Service;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HandleFileUploadService
{

    public function upload(Request $request, string $fileType, string $fileName, ?string $currentFilePath = null): string
    {
        if ($currentFilePath && $request->file($fileName)) {
            Storage::disk('public')->delete($currentFilePath);
        }
        if ($request->file($fileName)) {
            return $request->file($fileName)->store($fileType, 'public');
        }

        return $currentFilePath;
    }

}
