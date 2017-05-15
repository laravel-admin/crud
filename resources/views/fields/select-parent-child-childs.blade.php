@foreach($childs as $child)
    <option value="{{ $child->id }}" {{ $field->current_id($model) == $child->id ? 'disabled="disabled"' : '' }} {{ $field->value($model) == $child->id ? 'selected="selected"' : '' }}>
        {{ $current_id . $prefix.$child->name }}
    </option>
    @if(count($child->option_childs($current_id)))
        @include('crud::fields.select-parent-child-childs',['prefix' => $prefix . ' - ', 'childs' => $child->option_childs($current_id)])
    @endif
@endforeach
