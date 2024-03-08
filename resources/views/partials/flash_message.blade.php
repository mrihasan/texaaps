
@if (Session::has('flash_message'))
    {{--<div class="panel panel-primary">--}}
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{Session::get('flash_message')}}
            </div>
        {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
@endif
@if (Session::has('flash_error'))
    {{--<div class="panel panel-primary">--}}
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{Session::get('flash_error')}}
            </div>
        {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
@endif
{{--script added at footer--}}
{{--<script>$('div.alert').delay(3000).slideUp(300);</script>--}}