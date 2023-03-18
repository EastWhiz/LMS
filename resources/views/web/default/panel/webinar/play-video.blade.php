@extends(getTemplate() .'.panel.layouts.panel_layout')


@section('content')
<style>
.videoWrapper {
  position: relative;
  padding-bottom: 56.25%;
  /* 16:9 */
  padding-top: 25px;
  height: 0;
}

.videoWrapper iframe, .videoWrapper video {
  position: absolute;
  top: 0;
  left: 5%;
  width: 90%;
  height: 90%;
}
</style>
    <section class="">
        <h3 class="mb-20" style="text-transform: uppercase;" >{{$pageTitle}}</h3>
        <div class="row">
           <div class="col-md-12">
            <div class="videoWrapper">
                @php
                    $video_id = $video->id;
                @endphp
                @if (!is_null($source) and $source->isVideo())
                    @php
                        $s = $source->storage;
                        $file = $source->file;
                    @endphp
                    @if ($s=="youtube")
                        @php
                            preg_match_all("#(?<=v=|v\/|vi=|vi\/|youtu.be\/)[a-zA-Z0-9_-]{11}#", $file, $match);
                        @endphp
                        @if (isset($match[0][0]))
                        <iframe  width="560" height="315" src="https://www.youtube.com/embed/{{$match[0][0]}}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                        @endif
                    @elseif ($s=="upload")
                        @php
                            $exp = explode(".", $file);
                            $extension = end($exp)
                        @endphp
                        <video width="320" height="240" id="myVideo" controls>
                            <source src="{{$file}}" type="video/{{$extension}}">
                            Your browser does not support the video tag.
                        </video>
                        @if($quiz!="")
                            <script type='text/javascript'>
                                document.getElementById('myVideo').addEventListener('ended',myHandler,false);
                                function myHandler(e) {
                                    setTimeout(() => {
                                        window.location = "{{$quiz}}";    
                                    }, 1000);
                                    
                                }
                            </script>
                        @endif
                    @endif
                @endif
                
                </div>
           </div>
        </div>
    </section>

@endsection

@push('scripts_bottom')
    
@endpush
