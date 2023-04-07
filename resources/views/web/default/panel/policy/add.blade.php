@extends(getTemplate() .'.panel.layouts.panel_layout')

@push('styles_top')
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
@endpush

@section('content')
    <section>
        <h2 class="section-title">Set Policy</h2>

        <div class="panel-section-card py-20 px-25 mt-20">
            <form action="/panel/policy/add" method="post">
                {{ csrf_field() }}
                <div class="form-group ">
                    <label class="input-label control-label">Description</label>
                    <textarea name="message" class="summernote form-control text-left">{!!$Policy->option_value?? ""!!}</textarea>
                    <div class="invalid-feedback">/div>
                </div>

                <div class="form-group">
                    <button name="submit" class="btn btn-primary btn-sm" type="submit">Save Policy</button>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('scripts_bottom')
    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>
    <script>
        var noticeboard_success_send = '{{ trans('panel.noticeboard_success_send') }}';
    </script>

    <script src="/assets/default/js/panel/noticeboard.min.js"></script>
@endpush
