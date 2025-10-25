<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\File;

class AttachmentController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'file' => [
                'required',
                File::types(['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'])
                    ->max(10 * 1024),
            ],
        ]);

        $file = $request->file('file');
        $uuid = (string)Str::uuid();
        $fileName = "{$uuid}." . $file->getClientOriginalExtension();

        // Файл сохранится в storage/app/tmp/
        $file->storeAs('tmp', $fileName);

        return response()->json([
            'uuid' => $uuid,
            'original_name' => $file->getClientOriginalName(),
        ], 201);
    }

    /**
     * Удаляет временный файл по его UUID.
     */
    public function destroy(string $uuid): Response
    {
        $files = Storage::disk('local')->files('tmp');

        $fileToDelete = collect($files)->first(
            fn($file) => Str::startsWith(basename($file), $uuid)
        );

        if ($fileToDelete) {
            Storage::disk('local')->delete($fileToDelete);
            return response()->noContent();
        }

        return response(null, 404);
    }
}
