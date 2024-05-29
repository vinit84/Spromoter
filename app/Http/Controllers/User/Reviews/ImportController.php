<?php

namespace App\Http\Controllers\User\Reviews;

use App\Http\Controllers\Controller;
use App\Imports\YotpoImport;
use App\Models\ReviewImport;
use App\Models\Store;
use App\Models\TemporaryFile;
use App\Traits\HasTemporaryFile;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class ImportController extends Controller
{
    use HasTemporaryFile;

    public function index(Store $store)
    {
        return view('user.reviews.import.index');
    }

    public function store(Request $request)
    {
        try {
            $temporaryFile = $this->uploadTemporaryFileFromRequest($request);

            return success(trans('File uploaded successfully'), data: [
                'folder' => $temporaryFile->folder,
                'name' => $temporaryFile->name,
                'provider' => $request->input('provider'),
            ]);
        } catch (Throwable $throwable) {
            if (config('app.debug')) {
                return error($throwable->getMessage());
            }
            return error('Please check the file and try again');
        }
    }

    /**
     * @throws Exception
     */
    public function confirm($file, $provider)
    {
        try {
            $store = activeStore();
            $temporaryFile = TemporaryFile::whereFolder($file)->first();
            $path = $this->getTemporaryFilePath($temporaryFile);

            $reviewImport = ReviewImport::create([
                'store_id' => $store->id,
                'file_name' => $temporaryFile->name,
                'file_path' => $path,
                'status' => ReviewImport::STATUS_PENDING,
                'from' => now(),
            ]);

            $importer = $this->getImporter($provider);

            Excel::import(new $importer($store, $reviewImport), $path);

            return success(trans('Review importing started successfully'));
        }catch (Throwable $throwable) {
            return error($throwable->getMessage());
        }
    }

    public function deleteFile(Request $request)
    {
        //TODO: Delete the temporary file
    }

    /**
     * @throws Exception
     */
    private function getImporter($provider, $type = 'Import')
    {
        $importer = str($provider)
            ->replace(['.', ' '], '')
            ->lower()
            ->ucfirst()
            ->prepend('App\Imports\\')
            ->append($type)
            ->value();

        if (!class_exists($importer)) {
            throw new Exception('Invalid provider');
        }

        return $importer;
    }
}
