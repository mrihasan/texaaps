<a href="{{ url('product/' . $product->id ) }}" class="btn btn-success btn-xs"
   title="Show"><span class="far fa-eye" aria-hidden="true"></span></a>
@can('ProductMgtAccess')

<a href="{{ url('product/' . $product->id . '/edit') }}" class="btn btn-info btn-xs" title="Edit">
    <span class="far fa-edit" aria-hidden="true"></span></a>

@endcan
@can('ProductMgDelete')
{!! Form::open([
    'method'=>'DELETE',
    'url' => ['product', $product->id],
    'style' => 'display:inline'
]) !!}
{!! Form::button('<span class="far fa-trash-alt" aria-hidden="true" title="Delete" />', array(
        'type' => 'submit',
        'class' => 'btn btn-danger btn-xs',
        'title' => 'Delete',
        'onclick'=>'return confirm("Confirm delete?")'
))!!}
{!! Form::close() !!}
@endcan