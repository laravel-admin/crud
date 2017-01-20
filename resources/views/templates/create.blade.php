@extends('layouts.app')

@section('content')

<div class="container">


<form action="{!! route("{$route}store") !!}" method="post" class="form-horizontal">
	{!! csrf_field() !!}

	<div class="page-header">

		<div class="pull-right">
			<a href="{!! route("{$route}index") !!}" class="btn btn-default">Terug</a>
			<button type="submit" class="btn btn-primary">Opslaan</button>
		</div>

		<h1>{{ $singular_name }} toevoegen</h1>
	</div>

	<div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Settings</div>

                <div class="panel-body">

					<div class="form-group">
					  	<label for="title" class="col-sm-3 control-label">Title</label>
					  	<div class="col-sm-9">
							<input type="text" name="title" value="{!! old('title') !!}" class="form-control"/>
					  	</div>
				    </div>

                </div>
            </div>
        </div>
    </div>

</form>

</div>
@stop
