@if(is_null($max_childs) || $count<=$max_childs)
    @foreach($childs as $child)
        <option value="{{ $child->id }}" {{ $field->current_id($model) == $child->id ? 'disabled="disabled"' : '' }} {{ $field->value($model) == $child->id ? 'selected="selected"' : '' }}>
            {{ $prefix.$child->name }}
        </option>
        @if($childs = $child->childs)
            @include('crud::fields.select-parent-child-childs',['prefix' => $prefix . ' --> ', 'max_childs' => $max_childs, 'count' => $count+1, 'childs' => $childs])
        @endif
        @if($childs = $child->children)
            @include('crud::fields.select-parent-child-childs',['prefix' => $prefix . ' --> ', 'max_childs' => $max_childs, 'count' => $count+1, 'childs' => $childs])
        @endif
    @endforeach
@endif
