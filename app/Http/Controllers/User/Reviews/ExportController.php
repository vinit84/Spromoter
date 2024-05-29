<?php

namespace App\Http\Controllers\User\Reviews;

use App\Exports\ReviewExport as ReviewExporter;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Reviews\Exports\StoreExportRequest;
use App\Models\ReviewExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\SendReviewExportEmailJob;

class ExportController extends Controller
{
    public function index(Request $request)
    {
        return view('user.reviews.export.index', [
            'type' => $request->get('type', 'csv'),
        ]);
    }

    public function create(Request $request)
    {
        return view('user.reviews.export.create', [
            'type' => $request->get('type', 'csv'),
        ]);
    }

    public function store(StoreExportRequest $request)
    {
        $request->validated([
            'type' => 'in:csv,xlsx'
        ]);

        $store = activeStore();
        $export = ReviewExport::create([
            'store_id' => $store->id,
            'type' => $request->type,
            'file_name' => str($this->filename($request->type))->remove('reviews/')->__toString(),
            'file_path' => $this->filename($request->type),
        ]);

        $writerType = strtoupper($request->type);

        Excel::queue(
            export: new ReviewExporter($store),
            filePath: $this->filename($request->validated('type')),
            writerType: constant("Maatwebsite\Excel\Excel::$writerType"),
        )->chain([
            new SendReviewExportEmailJob($export, $request->validated('email'))
        ]);

        return success(trans('Your review export is being generated. You will receive an email once it is ready to download.'), route('user.reviews.export.index'));
    }

    protected function filename($type): string
    {
        return 'reviews/Review_' . date('YmdHis') . '.' . $type;
    }

    public function download(Request $request, ReviewExport $export)
    {
        // Check if the file exists
        if (!Storage::exists($export->file_path)) {
            abort(404, 'File not found');
        }

        return response()->download(Storage::path($export->file_path), $export->file_name);
    }
}
