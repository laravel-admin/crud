@extends($layout)

@section('content')

<div class="container">
    <form action="{{ route("{$route}update", $model->id) }}" method="post" class="form-horizontal">
        {!! csrf_field() !!}
        {!! method_field('put') !!}

        <div class="page-header">

            <div class="pull-right">
                <a href="{!! route("{$route}index") !!}" class="btn btn-default">Back</a>
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route("{$route}destroy", $model->id) }}" class="btn btn-danger" form="destroy_entry" data-method="delete" data-confirm="Are you sure to delete this item?">Delete</a>
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
