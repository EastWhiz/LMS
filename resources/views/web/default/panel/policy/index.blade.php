@extends(getTemplate() .'.panel.layouts.panel_layout')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/chartjs/chart.min.css"/>
    <link rel="stylesheet" href="/assets/default/vendors/apexcharts/apexcharts.css"/>
@endpush

@section('content')
@if(isset($AdminPolicy->id))
    <section class="mb-30">
        <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row mb-10">
            <h1 style="font-size: 20px;color:rgb(8, 133, 12);">General Policy</h1>
            <hr>
        </div>
        <div>
            {!!$AdminPolicy->option_value!!}
        </div>
    </section>
@endif
@if(isset($OrgPolicy->id))
    <section class="">
        <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row mb-10">
            <h1 style="font-size: 20px;color:rgb(8, 133, 12);">Orgnization Policy</h1>
            <hr>
        </div>
        <div>
            {!!$OrgPolicy->option_value!!}
        </div>
    </section>
@endif

@endsection