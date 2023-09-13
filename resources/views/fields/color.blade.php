<div class="form-group">
	<label for="{{ $field->id() }}" class="col-sm-3 control-label">{{ $field->label() }}</label>
	<div class="col-sm-9">
        <div class="input-group colorpicker-component">
            <input type="text" name="{{ $field->id() }}" class="form-control {{ $field->css_classes() }}" value="{!! old($field->id(), $field->format($model)) !!}" {{ $field->disabled() ? 'disabled' : '' }}/>
            <span class="input-group-addon"><i></i></span>
        </div>
		@include('crud::fields.field-description')
    </div>
</div>
