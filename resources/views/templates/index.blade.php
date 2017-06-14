@extends($layout)

@section('content')

<div class="container">

	<div class="page-header">

	    <div class="pull-right">
			@if($handle_bulk && !empty($submenu_bulk))
			<div class="btn-group">
				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Bulk actions <span class="caret"></span>
		        </button>
				<ul class="dropdown-menu bulk-actions">
					@foreach ($submenu_bulk as $item)
                        @if($item['allow_action'])
                            <li><a href="{!! route("{$route}store") !!}?action=set&amp;type={{ $item['id'] }}">{{ $item['label_action'] }}</a></li>
                        @endif
					@endforeach
                    @if($allow_delete)
					<li role="separator" class="divider"></li>
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

				<div class="panel panel-default">

					<table class="table table-striped table-hover table-heading">
						<thead>
							<tr>
								@if($handle_bulk)<th width="25"><input type="checkbox" class="check-all" data-scope=".check-item"></th>@endif
								@foreach ($fields->headings() as $heading) <th>	{{ $heading['label'] }} <a href="{{ $fields->getOrderLink($heading['id']) }}" class="fa fa-fw fa-sort text-muted"></a> </th> @endforeach
							</tr>
						</thead>
						<tbody>
						@foreach ($records as $record)
						<tr data-href="{{ route("{$route}edit", $record->id) }}" @if(!is_null($record->is_active) && !$record->is_active)class="danger"@endif>
							@if($handle_bulk)<td><input type="checkbox" name="record[{!! $record->id !!}]" value="{!! $record->id !!}" class="check-item"></td>@endif
							@foreach ($fields->values($record) as $item) <td> {{ $item }} </td> @endforeach
						</tr>
						@endforeach
						</tbody>
					</table>

					<div class="panel-footer">
						{{ $records->appends(Request::except('page'))->render() }}
					</div>

				</div>

			</div>

		</form>

	</div>

</div>

@stop
