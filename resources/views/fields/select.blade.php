<div class="form-group">
	<label for="{{ $field->id() }}" class="col-sm-3 control-label">{{ $field->label() }}</label>
	<div class="col-sm-9">
		<select name="{{ $field->id() }}" class="form-control">
			@foreach ($field->options($model) as $key=>$value)
				<option value="{{ $key }}" {{ $field->value($model) == $key ? 'selected="selected"' : '' }}>
					{{ $value }}
				</option>
			@endforeach
		</select>
	</div>
</div>
