@extends($layout)

@section('content')

<div class="container">
	<div class="col-md-10 col-md-push-1">

		<div class="page-header">
			<div class="pull-right">
				<a href="{{ route("{$route}create") }}" class="btn btn-primary">Create</a>
			</div>
			<h1>{{ ucfirst($plural_name) }}</h1>
		</div>

		<div class="panel panel-default">
			<table class="table">
				<thead>
					<tr>
						@foreach ($fields->headings() as $heading) <th>	{{ $heading }} </th> @endforeach
					</tr>
				</thead>
				<tbody>
				@foreach ($records as $record)
					<tr data-url="{{ route("{$route}edit", $record->id) }}">
						@foreach ($fields->values($record) as $item) <td>	{{ $item }} </td> @endforeach
					</tr>
				@endforeach
				</tbody>
			</table>
			<div class="panel-footer">

			</div>
		</div>

	</div>
</div>

@stop
