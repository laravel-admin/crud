@extends('admin::master')

@section('content')
<main>
	<div class="container">

		<form action="{!! route("{$route}store") !!}" method="post" class="form-horizontal">
			{!! csrf_field() !!}

			<div class="page-header">

				<div class="float-md-right">
					<a href="{!! route("{$route}index") !!}" class="btn btn-default">Terug</a>
					<button type="submit" class="btn btn-success">Opslaan</button>
				</div>

				<h1>{{ ucfirst($singular_name ) }} toevoegen</h1>
			</div>

			<div class="row">
				<div class="col-xs-12 col-md-12">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title">Settings</h4>
						</div>
						<div class="card-block">
							@foreach ($fields->values() as $field)
								@if ($view = $field->view()) @include($view, compact($field, $model)) @endif
							@endforeach
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</main>
@stop
