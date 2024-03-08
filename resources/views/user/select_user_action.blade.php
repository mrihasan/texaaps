
    <li style="text-align: center">
        <a href="{{ url('user/' . $row_id) }}">
            <button class="btn btn-success btn-xs" title="Show">
                <span class="far fa-eye" aria-hidden="true"></span></button>
        </a>
    </li>

@can('UserAccess')
<li style="text-align: center">
        <a href="{{ url('user/' . $row_id . '/edit') }}">
            <button class="btn btn-info btn-xs" title="Edit" style="margin-top: 5px">
                <span class="far fa-edit" aria-hidden="true"></span></button>
        </a>
</li>
@endcan
@can('UserDelete')
<li style="text-align: center">
        {!! Form::open([
            'method'=>'DELETE',
            'url' => ['user', $row_id],
            'style' => 'display:inline'
        ]) !!}
        {!! Form::button('<span class="fa fa-trash " aria-hidden="true" title="Delete" />', array(
                'type' => 'submit',
                'class' => 'btn btn-danger btn-xs',
                'style' => ' margin:5px',
                'title' => 'Delete',
                'onclick'=>'return confirm("Confirm delete?")'
        ))!!}
        {!! Form::close() !!}
</li>
@endcan
