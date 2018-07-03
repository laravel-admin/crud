<div class="form-group">
	<label for="{{ $field->id() }}" class="col-sm-3 control-label">{{ $field->label() }}</label>
	<div class="col-sm-9">
        <select name="{{ $field->id() }}" multiple class="form-control {{ $field->css_classes() }}">
			@foreach ($field->options($model) as $key=>$value)
				<option value="{{ $key }}" {{ (in_array($key, $field->format($model)) || collect(old(str_replace('[]','',$field->id())))->contains($key)) ? 'selected="selected"' : '' }}>
					{{ $value }}
				</option>
			@endforeach
		</select>
		@include('crud::fields.field-description')
	</div>
</div>
