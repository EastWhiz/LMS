@extends(getTemplate() .'.panel.layouts.panel_layout')

@section('content')
   <style>
    .table th, .table td{font-size:13px;padding:10px;}
    .w{font-size:13px;color:rgb(18, 107, 209); font-weight: bold;border:1px solid #7d7d7d; display: inline-block; padding:5px 12px;text-transform: uppercase;}
    .h-table{background:#fff;}
    .h-table thead{color:#000; border:1px solid #666;}
    .h-table th{font-weight: bold;}
    .h-table td{color:#000;}
    .score-p h3{font-size:12px; font-weight: 500; margin: 0px; margin-bottom:10px;}
    .score-p .rec{font-weight: bold;font-size:15px;}
    .score-p .progress{margin:6px 0px; height: 7px;}
    .score-p .progress span{display: block; height: 100%; width:78%; background:green;}
    .score-p .total{display: block; text-align: right; font-size:13px;}
   </style> 
    <section class="">
        <h3 class="mb-20" style="text-transform: uppercase;" >{{$pageTitle}}</h3>
        <div class="row">
            <div class="col-md-9">
                <table class="table h-table">
                    <thead>
                        <tr>
                            <th class="text-left">Name</th>
                            <th class="text-left">Date Taken</th>
                            <th class="text-left">Score</th>
                            <th class="text-left">Watch</th>
                        </tr>
                    </thead>
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
                            $r = (isset($q->quizResults)) ? $q->quizResults : array() ;
                            $tq = 0;
                            $tc = 0;
                            if (isset($q->quizResults)){
                                $tq = $q->quizQuestions->count();
                                foreach($q->quizResults as $k=>$v){
                                    $rs = ($v->results!="") ? json_decode($v->results, true) : array();
                                    
                                    foreach($rs as $kk=>$vv){
                                        if (is_numeric($kk)){
                                            if (isset($vv["status"]) and $vv["status"]){
                                                $tc +=1;
                                            }
                                        }
                                    }
                                }
                            }
                            $result = 0;
                            if ($tq > 0 and $tc > 0){
                                $result = round( ( $tc / $tq ) * 100 );
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
            <div class="col-md-3" style="padding:0px;">
                @php
                    $Quizzes = App\Models\Quiz::where("status", "active")->where("creator_id", 0)->orWhere("creator_id", auth()->user()->orgran_id)->get();
                    $quiz_ids = [];
                    foreach($Quizzes as $q){
                        $quiz_ids[] = $q->id;
                    }
                    $results = App\Models\QuizzesResult::whereIn("quiz_id", $quiz_ids)->where("user_id", Auth()->user()->id)->get();
                    $recPer = 0;
                    $tq = 0;
                    $tc = 0;
                    foreach($results as $rs){
                        $tq += $rs->quiz->quizQuestions->count();
                        if ($rs->results!=""){
                            $res = json_decode($rs->results, true);
                            foreach($res as $k=>$v){
                                if ($k!="attempt_number"){
                                    if (isset($v["status"]) and $v["status"]){
                                        $tc += 1;
                                    }  
                                }
                            }
                        }
                    }
                    if ($tc > 0 and $tq > 0){
                        if ($tc > 0){
                            $recPer = round(( $tc / $tq ) * 100);
                        }
                    }
                @endphp
                <div class="card">
                    <div class="card-body" style="padding:10px;">
                        <div class="score-p">
                            <h3>Average Quiz Score</h3>
                            <span class="rec">{{$recPer}}%</span>
                            <div class="progress">
                                <span style="width:{{$recPer}}%"></span>
                            </div>
                            <span class="total">100%</span>
                        </div>
                    </div>
                </div>
                <div class="card" style="margin-top:20px;">
                    @php
                        $per = 0;
                        $tr = count($results);
                        $tq = count($Quizzes);
                        if ($tr > 0 and $tq > 0){
                            $per = round(( $tr / $tq ) * 100);
                        }
                    @endphp
                    <div class="card-body" style="padding:10px;">
                        <div class="score-p">
                            <h3># of quiz taken</h3>
                            <span class="rec">{{$tr}}</span>
                            <div class="progress">
                                <span style="width:{{$per}}%"></span>
                            </div>
                            <span class="total">{{$tq}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts_bottom')
    
@endpush
