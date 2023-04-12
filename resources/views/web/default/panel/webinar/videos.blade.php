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
                        @php
                            $user = auth()->user();
                        @endphp
                        @foreach ($videos as $webinar)
                            @php
                                $chk = DB::table("watch_video")->where([
                                    ["webinar_id", "=", $webinar->id],
                                    ["user_id", $user->id]
                                ])->first();
                            @endphp
                            @php
                            $q = App\Models\Quiz::where("webinar_id",  $webinar->id)->first();
                            $r = $q->quizResults;
                            $result = 0;
                            if (isset($q->quizResults)){
                                foreach($q->quizResults as $k=>$v){
                                    $rs = ($v->results!="") ? json_decode($v->results, true) : array();

                                    foreach($rs as $kk=>$vv){
                                        if (is_numeric($kk)){
                                            if ($vv["status"]){
                                                $result +=1;
                                            }
                                        }
                                    }
                                }
                            }
                            @endphp
                            <tr>
                                <td>{{$webinar->title}}</td>
                                <td style="width:120px">
                                    @if(isset($chk->id))
                                        {{date("Y-m-d",strtotime($chk->created_at))}}
                                    @endif
                                </td>
                                <td style="width:100px">{{$result}}</td>
                                <td style="width:120px">
                                    @if(isset($chk->id))
                                        <a href="{{url("panel/video/$webinar->slug")}}" class="w">REWatch</a>
                                    @else
                                        <a href="{{url("panel/video/$webinar->slug")}}" class="w">Watch Now</a>
                                    @endif
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
