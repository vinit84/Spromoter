<?php

namespace App\Http\Controllers\Admin\Settings;

use App\DataTables\Admin\Settings\LanguageDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Settings\Languages\StoreLanguageRequest;
use App\Http\Requests\Admin\Settings\Languages\UpdateLanguageRequest;
use App\Models\Language;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class LanguageController extends Controller
{
    public function __construct()
    {
        // Protect all methods with permission
        $this->middleware('permission:language-create')->only('create', 'store');
        $this->middleware('permission:language-read')->only('index', 'show');
        $this->middleware('permission:language-update')->only('edit', 'update', 'translations', 'translationsUpdate');
        $this->middleware('permission:language-delete')->only('destroy', 'delete');
    }

    /**
     * Display a listing of the languages.
     *
     * @return mixed
     */
    public function index(LanguageDataTable $dataTable)
    {
        return $dataTable->render('admin.settings.languages.index');
    }

    /**
     * Display the form for creating a new language.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('admin.settings.languages.create');
    }

    /**
     * Store a newly created language in storage.
     *
     * @return JsonResponse
     */
    public function store(StoreLanguageRequest $request)
    {
        DB::transaction(function () use ($request) {
            Language::create($request->validated());

            $file = lang_path($request->input('code').'.json');
            if (! file_exists($file)) {
                if (file_exists(lang_path('en.json'))) {
                    copy(lang_path('en.json'), $file);
                } else {
                    File::put($file, '{}');
                }
            }
        });

        cacheForget('languages');

        return success(trans('Language Created Successfully'), route('admin.settings.languages.index'));
    }

    /**
     * Display the specified language.
     *
     * @return Application|Factory|View
     */
    public function edit(Language $language)
    {
        return view('admin.settings.languages.edit', [
            'language' => $language,
        ]);
    }

    /**
     * Update the specified language in storage.
     *
     * @return JsonResponse
     */
    public function update(UpdateLanguageRequest $request, Language $language)
    {
        $language->update($request->validated());

        cacheForget('languages');

        return success(trans('Language Updated Successfully'), route('admin.settings.languages.index'));
    }

    /**
     * Remove the specified language from storage.
     *
     * @return JsonResponse
     */
    public function destroy(Language $language)
    {
        if ($language->is_system) {
            return error('You are not allowed to delete system language.');
        }

        // Delete Language File
        $file = lang_path($language->code.'.json');
        if (file_exists($file)) {
            File::delete($file);
        }

        $language->forceDelete();
        cacheForget('languages');

        return success(trans('Language Deleted Successfully'));
    }

    /**
     * Display the specified language translations page.
     *
     * @return Application|Factory|View
     */
    public function translations(Language $language)
    {
        $file = lang_path($language->code.'.json');

        if (! file_exists($file)) {
            if (file_exists(lang_path('en.json'))) {
                copy(lang_path('en.json'), $file);
            } else {
                File::put($file, '{}');
            }

            $contents = file_get_contents($file);
            $translations = json_decode($contents, true) ?? [];
        } else {
            if ($file && file_exists($file)) {
                $contents = file_get_contents($file);
                $translations = json_decode($contents, true);
            } else {
                $translations = [];
            }
        }
        cacheForget('languages');

        return view('admin.settings.languages.translations', [
            'language' => $language,
            'translations' => $translations,
        ]);
    }

    public function translationsUpdate(Request $request, Language $language)
    {
        $request->validate([
            'translations' => 'required|array|min:1',
            'translations.*' => 'required|string',
            'new-phrases' => 'nullable|array',
            'new-phrases.*.key' => 'required|string',
            'new-phrases.*.value' => 'required|string',
        ], [
            'translations.*.required' => trans(':attribute Is Required'),
        ]);

        $file = lang_path($language->code.'.json');

        if ($file && file_exists($file)) {
            $translations = json_decode(file_get_contents($file), true);
        } else {
            $translations = [];
        }

        $translations = array_merge($translations, $request->input('translations'));

        if ($request->has('new-phrases')) {
            foreach ($request->input('new-phrases') as $phrase) {
                $translations[$phrase['key']] = $phrase['value'];
            }
        }

        ksort($translations);

        File::put($file, json_encode($translations, JSON_PRETTY_PRINT));
        cacheForget('languages');

        return success(trans('Translations Updated Successfully'), route('admin.settings.languages.index'));
    }
}
