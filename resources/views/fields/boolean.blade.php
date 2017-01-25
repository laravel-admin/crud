<div class="form-group">
	<label for="{{ $field->id() }}" class="col-sm-3 control-label">{{ $field->label() }}</label>
	<div class="col-sm-9">
		<input type="hidden" name="{{ $field->id() }}" value="0" />
		<input type="checkbox" name="{{ $field->id() }}" value="1" {{ $field->value($model) ? 'checked="checked"' : '' }}/>
	</div>
</div>
