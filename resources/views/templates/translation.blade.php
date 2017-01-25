@extends($layout)

@section('content')

<div class="container">
    <form action="{{ route("{$route}update", [$model->id, $translation]) }}" method="post" class="form-horizontal">
        {!! csrf_field() !!}
        {!! method_field('put') !!}

        <div class="page-header">

            <div class="pull-right">
				@if ($languages = config('translatable.labels'))
				<div class="btn-group">
				  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				    {{ $languages[$languages] }} <span class="caret"></span>
				  </button>
				  <ul class="dropdown-menu">@foreach ($languages as $key=>$value)
				    <li><a href="{{ route("{$route}show", [$model->id, $key]) }}">{{ $value }}</a></li>
					@endforeach</ul>
				</div>
				@endif
                <button type="submit" class="btn btn-primary">Save</button>

            </div>

            <h1>Edit {{ ucfirst($singular_name) }}</h1>
        </div>

        <div class="row">
			@if (!empty($submenu))
				@include('crud::templates.submenu')
            	<div class="col-md-9">
			@else
				<div class="col-xs-12">
			@endif
                <div class="panel panel-default">
                    <div class="panel-heading">Settings</div>
                    <div class="panel-body">
                        @foreach ($fields->values() as $field)
                            @if ($field && $view = $field->view()) @include($view, compact($field, $model)) @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>

@stop
