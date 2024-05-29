<div class="d-flex align-items-center">
    @can('language-update')
        <a href="{{ route('admin.settings.languages.edit', $id) }}" class="btn btn-sm btn-icon" title="{{ trans('Edit') }}">
            <i class="ti ti-edit"></i>
        </a>

        <a href="{{ route('admin.settings.languages.translations', $id) }}" class="btn btn-sm btn-icon " title="{{ trans('Translate') }}">
            <i class="ti ti-language"></i>
        </a>
    @endcan

    @can('language-delete')
        <a class="btn btn-sm btn-icon confirm-delete" href="{{ route('admin.settings.languages.destroy', $id) }}" title="{{ trans('Delete') }}">
            <i class="ti ti-trash"></i>
        </a>
    @endcan
</div>
