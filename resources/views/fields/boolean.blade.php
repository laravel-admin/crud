<div class="form-group">
	<div class="col-sm-9 col-sm-push-3 checkbox">
		<input type="hidden" name="{{ $field->id() }}" value="0" />
		<label><input type="checkbox" name="{{ $field->id() }}" value="1" {{ ($field->value($model) || old($field->id())) ? 'checked="checked"' : '' }}/> {{ $field->label() }}</label>
    </div>
</div>
