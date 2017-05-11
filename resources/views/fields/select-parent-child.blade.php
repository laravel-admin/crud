<div class="form-group">
	<label for="{{ $field->id() }}" class="col-sm-3 control-label">{{ $field->label() }}</label>
	<div class="col-sm-9">
        <select name="{{ $field->id() }}" class="form-control {{ $field->css_classes() }}">
            @foreach ($field->options($model) as $option)
                <option value="{{ $option->id }}" {{ $field->value($model) == $option->id ? 'selected="selected"' : '' }}>
                    {{ $option->name }}
                </option>
                @if(count($option->option_childs($field->current_id($model))))
                    @include('crud::fields.select-parent-child-childs',['current_id' => $field->current_id($model), 'prefix' => '-> ', 'childs' => $option->option_childs($field->current_id($model))])
                @endif
            @endforeach
        </select>
	</div>
</div>
