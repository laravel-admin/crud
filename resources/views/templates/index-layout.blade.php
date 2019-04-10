@extends($layout)

@section('content')

<div class="container">

	<div class="page-header">

	    <div class="pull-right">
			@if($handle_bulk)
			<div class="btn-group">
				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Bulk actions <span class="caret"></span>
		        </button>
				<ul class="dropdown-menu bulk-actions">
					@if(!empty($submenu_bulk))	
						@foreach ($submenu_bulk as $item)
							@if($item['allow_action'])
								<li><a href="{!! route("{$route}store") !!}?action=set&amp;type={{ $item['id'] }}">{{ $item['label_action'] }}</a></li>
							@endif
						@endforeach
						<li role="separator" class="divider"></li>
					@endif
					@if($allow_delete)
					<li><a href="{!! route("{$route}store") !!}?action=delete" class="text-danger">Delete</a></li>
                    @endif
				</ul>
			</div>
			@endif
            @if($allow_create)
			<a class="btn btn-primary" href="{!! URL::route("{$route}create") !!}">Add {!! $singular_name !!}</a>
            @endif
		</div>

	    <h1>{!! $records->total() !!} {!! ucfirst($plural_name) !!} found
	        <small>page {!! $records->currentPage() !!} of {!! $records->lastPage() !!}</small>
	    </h1>

	</div>

	<div class="row">

		@include('crud::templates.index-submenu')

		<form action="{!! route("{$route}store") !!}" method="post" id="main-form">

			{!! csrf_field() !!}

			<div class="col-md-9">

				@yield('records')

			</div>

		</form>

	</div>

</div>

@stop