@extends($layout)

@section('content')

<div class="container">
    <form action="{{ route("{$route}update", [$model->$foreign_key, $translation]) }}" method="post" class="form-horizontal">
        {!! csrf_field() !!}
        {!! method_field('put') !!}

        <div class="page-header">
            <div class="pull-right">
				@if ($languages = config('translatable.labels'))
				<div class="btn-group">
				  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				    {{ strtoupper($translation) }} <span class="caret"></span>
				  </button>
				  <ul class="dropdown-menu">@foreach ($languages as $key=>$value)
				    <li><a href="{{ route("{$route}show", [$model->$foreign_key, $key]) }}">{{ strtoupper($key) }}</a></li>
					@endforeach</ul>
				</div>
				@endif
                <button type="submit" class="btn btn-primary">Save</button>

            </div>

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
                <div class="panel panel-default">
                    <div class="panel-heading"><h3 class="panel-title">{{ $languages[$translation] }} translation</h3></div>
                    <div class="panel-body">
                        @foreach ($fields->values() as $field)
                            @if ($field && $view = $field->view()) 
                              @if (is_array($field) && is_array($model))
                                  @include($view, compact((array)$field, (array)$model))
                              @else
                                  @include($view)
                              @endif
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>

@stop

@include('crud::templates.tinymce')
