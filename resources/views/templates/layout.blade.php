@extends($layout)

@section('content')

<div class="container">

    <form action="" method="post" class="form-horizontal">
	
		{!! csrf_field() !!}
        {!! method_field('put') !!}

        <div class="page-header">

			@if ($translation)

				<div class="pull-right">

					@if ($languages = config('translatable.labels'))

						<div class="btn-group">
							
							<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								{{ strtoupper($translation) }} <span class="caret"></span>
							</button>
							
							<ul class="dropdown-menu">
								@foreach ($languages as $key=>$value)
									<li><a href="{{ route("{$route}show", [$model->$foreign_key, $key]) }}">{{ strtoupper($key) }}</a></li>
								@endforeach
							</ul>

						</div>

						@if($model->slug)
							
							<div class="btn-group">
							
								<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Copy widgets from <span class="caret"></span>
								</button>
							
								<ul class="dropdown-menu">
									@foreach ($languages as $key=>$value) @continue ($key == $translation)
										<li><a href="{{ route("{$route}show", [$model->$foreign_key, $translation]) }}?copy={{ $key }}" data-confirm="Beware, you are now overwriting all widgets from this language!">{{ $value }} widget setup</a></li>
									@endforeach
								</ul>
							
							</div>

						@endif

					@endif

					<a href="#" class="btn btn-primary" id="btnSaveLayout">Save</a>

				</div>
			
			@endif

            <h1>Edit {{ $singular_name }}</h1>
			<p><strong>Admin {{ $select_parent_name }}:</strong> {{ $parent_name }}</p>
			
        </div>

        <div class="row">

			@if (!empty($submenu))
				@include('crud::templates.submenu')
            	<div class="col-md-10">
			@else
				<div class="col-xs-12">
			@endif
			
				<layout
					title="{{ $singular_name }}"
					language="{{ $languages[$translation] }}" 
					hastranslation="{{ $has_translation ? 'true' : 'false' }}" 
					:locale="{{ $translation ? 'true' : 'false' }}"
					controller="{{ URL::current() }}"
					:layoutdata="{{ json_encode($model->$field ?: []) }}"
					:layoutsettings="{{ json_encode($settings ?: []) }}" 
					ref="layoutsetup"
				></layout>

            </div>
		
		</div>
		
    </form>

</div>

@stop

@include('crud::templates.tinymce')
