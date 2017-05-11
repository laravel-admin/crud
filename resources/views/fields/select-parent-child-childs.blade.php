@foreach($childs as $child)
    <option value="{{ $option->id }}" {{ $field->value($model) == $child->id ? 'selected="selected"' : '' }}>
        {{ $prefix.$child->name }}
    </option>
    @if(count($child->option_childs($current_id)))
        @include('crud::fields.select-parent-child-childs',['prefix' => $prefix . '-> ', 'childs' => $child->option_childs($current_id)])
    @endif
@endforeach
