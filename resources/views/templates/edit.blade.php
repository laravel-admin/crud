@extends('layouts.app')

@section('content')

<div class="container">


<form action="{!! route("{$route}update", $model->id) !!}" method="post" class="form-horizontal">
	{!! csrf_field() !!}
	{!! method_field('put') !!}

	<div class="page-header">

		<div class="pull-right">
			<a href="{!! route("{$route}index") !!}" class="btn btn-default">Terug</a>
			<button type="submit" class="btn btn-primary">Opslaan</button>

			{{-- <form action="{!! route("{$route}destroy", $model->id) !!}" method="post">
				{!! method_field('delete') !!}
				<button type="submit" class="btn btn-danger">Verwijder</button>
			</form> --}}
		</div>

		<h1>{{ $model->title }} wijzigen</h1>
	</div>

	<div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Wijzig {{ $singular_name }}</div>

                <div class="panel-body">

					@foreach ($fields->values() as $field)
						@if ($view = $field->view()) @include($view, compact($field, $model)) @endif
					@endforeach

                </div>
            </div>
        </div>
    </div>

</form>

</div>
@stop
