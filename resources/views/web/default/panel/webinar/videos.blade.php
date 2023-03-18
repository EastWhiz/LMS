@extends(getTemplate() .'.panel.layouts.panel_layout')


@section('content')
    <section class="">
        <h3 class="mb-20" style="text-transform: uppercase;" >{{$pageTitle}}</h3>
        <div class="row">
            @foreach ($videos as $webinar)
                <div class="col-lg-4">
                    @include('web.default.includes.webinar.grid-card',['webinar' => $webinar])
                </div>
            @endforeach
        </div>
    </section>

@endsection

@push('scripts_bottom')
    
@endpush
