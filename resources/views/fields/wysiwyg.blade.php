<div class="form-group">
	<label for="{{ $field->id() }}" class="col-sm-3 control-label">{{ $field->label() }}</label>
	<div class="col-sm-9">
		@if($field->disabled())
			{!! $field->format($model) !!}
		@else
			<textarea name="{{ $field->id() }}" rows="12" class="form-control tiny-mce {{ $field->css_classes() }}" {{ $field->disabled() ? 'disabled' : '' }}>{!! old($field->id(), $field->format($model)) !!}</textarea>
			@include('crud::fields.field-description')
		@endif
	</div>
</div>
