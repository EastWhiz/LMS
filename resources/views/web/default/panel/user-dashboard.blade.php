@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/chartjs/chart.min.css"/>
    <link rel="stylesheet" href="/assets/default/vendors/apexcharts/apexcharts.css"/>
@endpush

@section('content')
    <section class="">
        <div class=" dashboard-banner-container position-relative px-15 px-ld-35 panel-shadow rounded-sm mt-0">
            <h2 class="font-30 text-primary line-height-1">
                <span class="d-block">{{ trans('panel.hi') }} {{ $authUser->full_name }},</span>
            </h2>
        </div>
    </section>
    <style>
        .margin {
        margin: 25px;
        }
        .semi-donut {
        --percentage: 0;
        --fill: #ff0;
        width: 200px;
        height: 100px;
        position: relative;
        color: #fff;
        font-size: 22px;
        font-weight: 600;
        overflow: hidden;
        color: var(--fill);
        display: flex;
        align-items: flex-end;
        justify-content: center;
        box-sizing: border-box;
        }
        .semi-donut:after {
        content: "";
        width: 200px;
        height: 200px;
        border: 30px solid;
        border-color: rgba(0, 0, 0, 0.15) rgba(0, 0, 0, 0.15) var(--fill) var(--fill);
        position: absolute;
        border-radius: 50%;
        left: 0;
        top: 0;
        box-sizing: border-box;
        transform: rotate(calc( 1deg * ( -45 + var(--percentage) * 1.8 ) ));
        -webkit-animation: fillAnimation 1s ease-in;
                animation: fillAnimation 1s ease-in;
        }
    </style>
    @php
        $r = DB::table("quizzes_results")->where("user_id", auth()->user()->id)->get();
        $total = 0;
        $result = 0;
        foreach($r as $k=>$v){
            $rs = ($v->results!="") ? json_decode($v->results, true) : array();
            foreach($rs as $kk=>$vv){
                if (is_numeric($kk)){
                    if ($vv["status"]){
                        $result +=1;
                    }
                    $total+=1;
                }
            }
        }
        $per = 0;
        if ($total > 0){
            $per = round( ($result / $total) * 100 );
        }
    @endphp
    <section class="dashboard">
        <div class="row">
            <div class="col-md-4">
                <div class="semi-donut margin" 
                    style="--percentage : {{$per}}; --fill: green ;">
                    {{$result}}
                </div>
            </div>
            <div class="col-md-6">
                <canvas id="resChart"></canvas>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-6 mt-35">
                <div class="bg-white noticeboard rounded-sm panel-shadow py-10 py-md-20 px-15 px-md-30">
                    <h3 class="font-16 text-dark-blue font-weight-bold">{{ trans('panel.noticeboard') }}</h3>
                    @foreach($authUser->getUnreadNoticeboards() as $getUnreadNoticeboard)
                        <div class="noticeboard-item py-15">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h4 class="js-noticeboard-title font-weight-500 text-secondary">{!! truncate($getUnreadNoticeboard->title,150) !!}</h4>
                                    <div class="font-12 text-gray mt-5">
                                        <span class="mr-5">{{ trans('public.created_by') }} {{ $getUnreadNoticeboard->sender }}</span>
                                        |
                                        <span class="js-noticeboard-time ml-5">{{ dateTimeFormat($getUnreadNoticeboard->created_at,'j M Y | H:i') }}</span>
                                    </div>
                                </div>

                                <div>
                                    <button type="button" data-id="{{ $getUnreadNoticeboard->id }}" class="js-noticeboard-info btn btn-sm btn-border-white">{{ trans('panel.more_info') }}</button>
                                    <input type="hidden" class="js-noticeboard-message" value="{{ $getUnreadNoticeboard->message }}">
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>

            <div class="col-12 col-lg-6 mt-35">
                <div class="bg-white monthly-sales-card rounded-sm panel-shadow py-10 py-md-20 px-15 px-md-30">
                    <div class="">
                        <h3 class="font-16 text-dark-blue font-weight-bold">Our Company</h3>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="text-left">#</th>
                                    <th class="text-left">User</th>
                                    <th class="text-right">Score</th>
                                </tr>
                                @php
                                    $organ_id = auth()->user()->organ_id;
                                    $u_id = auth()->user()->id;
                                    $users = DB::table("users")->where([
                                        ["organ_id", "=", $organ_id],
                                        ["id", "!=", $u_id]
                                    ])->get();
                                @endphp
                                <tbody>
                                    @foreach($users as $k=>$u)
                                        @php
                                            $r = DB::table("quizzes_results")->where("user_id", $u->id)->get();
                                            $result = 0;
                                            foreach($r as $k=>$v){
                                                $rs = ($v->results!="") ? json_decode($v->results, true) : array();
                                                foreach($rs as $kk=>$vv){
                                                    if (is_numeric($kk)){
                                                        if ($vv["status"]){
                                                            $result +=1;
                                                        }
                                                    }
                                                }
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{$k+1}}</td>
                                            <td>{{$u->full_name}}</td>
                                            <td class="text-right">{{$result}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <div class="d-none" id="iNotAvailableModal">
        <div class="offline-modal">
            <h3 class="section-title after-line">{{ trans('panel.offline_title') }}</h3>
            <p class="mt-20 font-16 text-gray">{{ trans('panel.offline_hint') }}</p>

            <div class="form-group mt-15">
                <label>{{ trans('panel.offline_message') }}</label>
                <textarea name="message" rows="4" class="form-control ">{{ $authUser->offline_message }}</textarea>
                <div class="invalid-feedback"></div>
            </div>

            <div class="mt-30 d-flex align-items-center justify-content-end">
                <button type="button" class="js-save-offline-toggle btn btn-primary btn-sm">{{ trans('public.save') }}</button>
                <button type="button" class="btn btn-danger ml-10 close-swl btn-sm">{{ trans('public.close') }}</button>
            </div>
        </div>
    </div>

    <div class="d-none" id="noticeboardMessageModal">
        <div class="text-center">
            <h3 class="modal-title font-20 font-weight-500 text-dark-blue"></h3>
            <span class="modal-time d-block font-12 text-gray mt-25"></span>
            <p class="modal-message font-weight-500 text-gray mt-4"></p>
        </div>
    </div>

@endsection
@php
    $r = DB::table("quizzes_results")->where("user_id", auth()->user()->id)->orderBy("created_at", "desc")->get();
    $total = 0;
    $result = 0;
    $res = array();
    $dates = array();
    foreach($r as $k=>$v){
        $rs = ($v->results!="") ? json_decode($v->results, true) : array();
        foreach($rs as $kk=>$vv){
            if (is_numeric($kk)){
                if ($vv["status"]){
                    $result +=1;
                }
                $total+=1;
            }
        }
        $res[] = $result;
        $dates[] = date("m-d", $v->created_at);
    }
    $res = array_slice($res,-7,7,false);
    $res[] = 800;
@endphp
@push('scripts_bottom')
    <script src="/assets/default/vendors/apexcharts/apexcharts.min.js"></script>
    <script src="/assets/default/vendors/chartjs/chart.min.js"></script>
    <script>
        var xValues = @json($dates);
        var yValues = @json($res);
        new Chart("resChart", {
            type: "bar",
            data: {
                labels: xValues,
                datasets: [{
                data: yValues
                }]
            },
            options: {
                legend: {display: false},
                title: {
                display: true,
                text: "Employee Score Trend"
                }
            }
        });
    </script>
    <script>
        var offlineSuccess = '{{ trans('panel.offline_success') }}';
        var $chartDataMonths = @json($monthlyChart['months']);
        var $chartData = @json($monthlyChart['data']);
    </script>

    <script src="/assets/default/js/panel/dashboard.min.js"></script>
@endpush