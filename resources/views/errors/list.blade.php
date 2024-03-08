{{--@if($errors->any())--}}
    {{--<div class="alert alert-custom alert-{{ Session::get('notify_type') }}">--}}
        {{--<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>--}}
        {{--<span class="text-semibold">{{ Session::get('notify_message') }}</span>--}}
    {{--</div>--}}
{{--@endif--}}


@if ($errors->any())
    <ul class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
    </ul>
    @endif