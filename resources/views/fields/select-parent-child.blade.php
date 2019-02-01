<div class="form-group">
	<label for="{{ $field->id() }}" class="col-sm-3 control-label">{{ $field->label() }}</label>
	<div class="col-sm-9">
        <select name="{{ $field->id() }}" class="form-control {{ $field->css_classes() }}">
            @foreach ($field->options($model) as $option)
                <option value="{{ $option->id }}" {{ ($field->current_id($model) == $option->id && !empty($option->id)) ? 'disabled="disabled"' : '' }} {{ $field->value($model) == $option->id ? 'selected="selected"' : '' }}>
                    {{ $option->name }}
                </option>
                @if($childs = $option->childs)
                    @include('crud::fields.select-parent-child-childs',['prefix' => ' --> ', 'max_childs' => $field->max_childs(), 'count' => 1, 'childs' => $childs])
                @endif
                @if($childs = $option->children)
                    @include('crud::fields.select-parent-child-childs',['prefix' => ' --> ', 'max_childs' => $field->max_childs(), 'count' => 1, 'childs' => $childs])
                @endif
            @endforeach
        </select>
		@include('crud::fields.field-description')
	</div>
</div>
