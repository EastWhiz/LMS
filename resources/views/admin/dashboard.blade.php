@extends('admin.layouts.app')

@push('libraries_top')
    <link rel="stylesheet" href="/assets/admin/vendor/owl.carousel/owl.carousel.min.css">
    <link rel="stylesheet" href="/assets/admin/vendor/owl.carousel/owl.theme.min.css">

@endpush

@section('content')


    <section class="section">
        <div class="row">
            <div class="col-12 mb-4">
                <div class="hero text-white hero-bg-image hero-bg" data-background="{{ !empty(getPageBackgroundSettings('admin_dashboard')) ? getPageBackgroundSettings('admin_dashboard') : '' }}">
                    <div class="hero-inner">
                        <h2>{{trans('admin/main.welcome')}}, {{ $authUser->full_name }}!</h2>

                        <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between">
                            @can('admin_general_dashboard_quick_access_links')
                                <div>
                                    <p class="lead">{{trans('admin/main.welcome_card_text')}}</p>

                                    <div class="mt-2 mb-2 d-flex flex-column flex-md-row">
                                        <a href="/admin/comments/webinars" class="mt-2 mt-md-0 btn btn-outline-white btn-lg btn-icon icon-left ml-0 ml-md-2"><i class="far fa-comment"></i>{{trans('admin/main.comments')}} </a>
                                        <a href="/admin/supports" class="mt-2 mt-md-0 btn btn-outline-white btn-lg btn-icon icon-left ml-0 ml-md-2"><i class="far fa-envelope"></i>{{trans('admin/main.tickets')}}</a>
                                        <a href="/admin/reports/webinars" class="mt-2 mt-md-0 btn btn-outline-white btn-lg btn-icon icon-left ml-0 ml-md-2"><i class="fas fa-info"></i>{{trans('admin/main.reports')}}</a>
                                    </div>
                                </div>
                            @endcan

                            @can('admin_clear_cache')
                                <div class="w-xs-to-lg-100">
                                    <p class="lead d-none d-lg-block">&nbsp;</p>

                                    @include('admin.includes.delete_button',[
                                             'url' => '/admin/clear-cache',
                                             'btnClass' => 'btn btn-outline-white btn-lg btn-icon icon-left mt-2 w-100',
                                             'btnText' => trans('admin/main.clear_all_cache'),
                                             'hideDefaultClass' => true
                                          ])
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            
        </div>

        <div class="row">

            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <a href="/admin/webinars?type=video" class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-video"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Videos</h4>
                        </div>
                        <div class="card-body">
                           @php
                               $total = \App\Models\Webinar::count();
                               echo $total;
                           @endphp
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <a href="/admin/comments/webinars" class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="fas fa-home"></i></div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Orgnization</h4>
                        </div>
                        <div class="card-body">
                            @php
                                $total = App\Models\User::where("role_name", "organization")->count();
                                echo $total;
                            @endphp
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <a href="/admin/supports" class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-users"></i></div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Employees</h4>
                        </div>
                        <div class="card-body">
                            @php
                                $total = App\Models\User::where("role_name", "user")->count();
                                echo $total;
                            @endphp
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <a class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-eye"></i></div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Quiz</h4>
                        </div>
                        <div class="card-body">
                            @php
                                $total = App\Models\QUiz::count();
                                echo $total;
                            @endphp
                        </div>
                    </div>
                </a>
            </div>

        </div>

        <div class="row">
            <div class="col-md-12">
                <canvas id="resChart"></canvas>
            </div>
        </div>

    </section>
@endsection
@php
        $userLists = \App\Models\User::where("role_name", "user")->get();
        $results = array();
        foreach($userLists as $k=>$urs){
            $r  = $urs->getActiveQuizzesResults();
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
            $results[] = array("user"=>$urs->full_name, "result"=>$result);
        }

        function DescSort($val1,$val2){
            if ($val1['result'] == $val2['result']) return 0;
            return ($val1['result'] < $val2['result']) ? 1 : -1;
        }

        usort($results,'DescSort');

        $res = array_slice($results,0,7);

        $result_array = array();
        $users = array();
        foreach($res as $k=>$v){
            $result_array[] = $v["result"];
            $users[] = $v["user"];
        }
        
        $result_array[] = 800;
    @endphp
@push('scripts_bottom')
    <script src="/assets/default/vendors/chartjs/chart.min.js"></script>
    <script src="/assets/admin/vendor/owl.carousel/owl.carousel.min.js"></script>
    
    <script>
        var xValues = @json($users);
        var yValues = @json($result_array);
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
                text: "Employees Scores"
                }
            }
        });
    </script>

    <script src="/assets/admin/js/dashboard.min.js"></script>
@endpush
