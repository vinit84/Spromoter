@extends('layouts.frontend.app')

@section('content')
    <section class="section-py first-section-pt help-center-header">
        <h3 class="text-center">{{ $page->title }}</h3>
    </section>

    <section class="section-py">
        <div class="container">
            {!! $page->body !!}
        </div>
    </section>
@endsection

@push('st') @endpush
