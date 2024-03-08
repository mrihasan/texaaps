@foreach($table as $skill)
    <!-- loop through skill properties -->
    [
    @foreach($skill as $key => $value)
        @if($value)
            {{ "'".$key."'=>'".$value."'," }}
        @else
            {{--$n=NULL;--}}
            {{ "'".$key."'=>NULL," }}
        @endif

    @endforeach
    ],
    <br/>
@endforeach