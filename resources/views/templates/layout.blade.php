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
				    Copy from <span class="caret"></span>
				  </button>
				  <ul class="dropdown-menu">@foreach ($languages as $key=>$value) @continue ($key == $translation)
				    <li><a href="{{ route("{$route}show", [$model->$foreign_key, $translation]) }}?copy={{ $key }}">{{ $value }}</a></li>
					@endforeach</ul>
				</div>

				<div class="btn-group">
				  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				    {{ $languages[$translation] }} <span class="caret"></span>
				  </button>
				  <ul class="dropdown-menu">@foreach ($languages as $key=>$value)
				    <li><a href="{{ route("{$route}show", [$model->$foreign_key, $key]) }}">{{ $value }}</a></li>
					@endforeach</ul>
				</div>
				@endif

            </div>
			@endif

            <h1>Layout of {{ ucfirst($singular_name) }}</h1>
        </div>

        <div class="row">

			@if (!empty($submenu))
				@include('crud::templates.submenu')
            	<div class="col-md-9">
			@else
				<div class="col-xs-12">
			@endif

			<layout
				:locale="{{ $translation ? 'true' : 'false' }}"
				controller="{{ URL::current() }}"
				:layoutdata="{{ json_encode($model->layout ?: [])  }}"
				:layoutsettings="{{ json_encode($settings ?: []) }}"
			></layout>

            </div>
        </div>
    </form>

</div>

@stop


@include('crud::templates.tinymce')
