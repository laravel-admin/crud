<div class="form-group">
	<label for="{{ $field->id() }}" class="col-sm-3 control-label">{{ $field->label() }}</label>
	<div class="col-sm-9">
        <select name="{{ $field->id() }}" class="form-control {{ $field->css_classes() }}">
            @foreach ($field->options($model) as $option)
                <option value="{{ $option->id }}" {{ $field->current_id($model) == $option->id ? 'disabled="disabled"' : '' }} {{ $field->value($model) == $option->id ? 'selected="selected"' : '' }}>
                    {{ $option->name }}
                </option>
                @if(count($option->childs))
                    @include('crud::fields.select-parent-child-childs',['prefix' => ' - ', 'childs' => $option->childs])
                @endif
            @endforeach
        </select>
	</div>
</div>