@extends(getTemplate() .'.panel.layouts.panel_layout')


@section('content')
   <style>
    .table th, .table td{font-size:14px;}
    .w{font-size:14px;color:blueviolet; font-weight: bold}
   </style>
    <section class="">
        <h3 class="mb-20" style="text-transform: uppercase;" >{{$pageTitle}}</h3>
        <div class="row">
            <div class="col-md-9">
                <table class="table">
                    <tr>
                        <th class="text-left">Name</th>
                        <th class="text-left">Date Taken</th>
                        <th class="text-left">Score</th>
                        <th class="text-left">Watch</th>
                    </tr>
                    <tbody>
                        @foreach ($videos as $webinar)
                            <tr>
                                <td>{{$webinar->title}}</td>
                                <td style="width:120px"></td>
                                <td style="width:100px"></td>
                                <td style="width:120px">
                                    <a href="{{url("panel/video/$webinar->slug")}}" class="w">Watch Now</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-md-3">

            </div>
        </div>
    </section>

@endsection

@push('scripts_bottom')
    
@endpush
