@extends($layout)

@section('content')

<div class="container">

	<form action="{{ route("{$route}update", $model->id) }}" method="post" class="form-horizontal" enctype="multipart/form-data">

		{!! csrf_field() !!}
        {!! method_field('put') !!}

        <div class="page-header">

            <div class="pull-right">
                <a href="{!! route("{$route}index") !!}" class="btn btn-default">Back</a>
            </div>

            <h1>Show {{ $singular_name }}</h1>
        </div>

        <div class="row">
            @if (!empty($submenu))
				@include('crud::templates.submenu')
            	<div class="col-md-10">
			@else
				<div class="col-xs-12">
			@endif
            
                <div class="panel panel-default">
                    <div class="panel-heading"><h3 class="panel-title">General settings</h3></div>
                    <div class="panel-body">
                        @foreach ($fields->values() as $field)
                        @if ($view = $field->view()) 
                        @if ($field && $view = $field->view()) 
                        @if (is_array($field) && is_array($model))
                            @include($view, compact((array)$field, (array)$model))
                        @else
                            @include($view)
                        @endif
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
