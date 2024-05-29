<div class="card">
    @if(isset($title))
        <div class="card-header">
            <h3 class="card-title">{{ $title }}</h3>
        </div>
    @endif

    @if(isset($header))
        {{ $header }}
    @endif

    <div class="card-datatable table-responsive">
        {{ $dataTable->table() }}
    </div>
</div>

@push('styles')

    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-select-bs5/select.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-fixedcolumns-bs5/fixedcolumns.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-fixedheader-bs5/fixedheader.bootstrap5.css') }}" />
@endpush

@push('scripts')
    <script src="{{ asset('assets/plugins/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
@endpush


@push('pageScripts')
    {{ $dataTable->scripts() }}
@endpush
