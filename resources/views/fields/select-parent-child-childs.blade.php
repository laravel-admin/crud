@foreach($childs as $child)
    <option value="{{ $child->id }}" {{ $field->current_id($model) == $child->id ? 'disabled="disabled"' : '' }} {{ $field->value($model) == $child->id ? 'selected="selected"' : '' }}>
        {{ $prefix.$child->name }}
    </option>
    @if(count($child->childs))
        @include('crud::fields.select-parent-child-childs',['prefix' => $prefix . ' --> ', 'childs' => $child->childs])
    @endif
    @if(count($child->children))
        @include('crud::fields.select-parent-child-childs',['prefix' => $prefix . ' --> ', 'childs' => $child->children])
    @endif
@endforeach
