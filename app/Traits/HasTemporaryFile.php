<?php

namespace App\Traits;

use App\Models\TemporaryFile;

trait HasTemporaryFile
{
    protected function uploadTemporaryFileFromRequest($request, $inputName = 'file'): TemporaryFile
    {
        $file = $request->file($inputName);

        $fileName = $file->getClientOriginalName();
        $folder = uniqid() . '-' . now()->timestamp;
        $file->storeAs('temporary-files/' . $folder, $fileName);

        return TemporaryFile::create([
            'folder' => $folder,
            'name' => $fileName,
        ]);
    }

    protected function getTemporaryFilePath(TemporaryFile $temporaryFile): string
    {
        return storage_path('app/temporary-files/'.$temporaryFile->folder.'/'. $temporaryFile->name);
    }

    protected function deleteTemporaryFile(TemporaryFile $temporaryFile): void
    {
        $path = storage_path('app/temporary-files/'.$temporaryFile->folder.'/'. $temporaryFile->name);

        if (file_exists($path)){
            unlink($path);
        }

        $temporaryFile->delete();
    }
}
