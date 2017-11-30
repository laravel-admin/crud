@extends($layout)

@section('content')

<form action="{{ route("{$route}store", $model->id) }}" method="post" class="form-horizontal">

	{{ csrf_field() }}

	<div class="container">

		<div class="page-header">
			<div class="pull-right">
				<a href="{{ route("{$parent_route}edit", $model->id) }}" class="btn btn-default">Back</a>
				<button type="submit" class="btn btn-primary">Save</button>
			</div>
			<h1>{{ ucfirst($plural_name) }}</h1>
		</div>

		@if (!empty($submenu))
			@include('crud::templates.submenu')
	    	<div class="col-md-10">
		@else
			<div class="col-xs-12">
		@endif

		<div class="panel panel-default">
			<table class="table table-hover">
				<thead>
					<tr>
						<th width="25">&nbsp;</th>
						@foreach ($fields->headings() as $heading) <th>	{{ $heading }} </th> @endforeach
					</tr>
				</thead>
				<tbody>
					@foreach ($records as $record)
					<tr>
						<td>
							<input type="checkbox" id="record-{{ $record->id }}" name="items[{{ $record->id }}]" value="{{ $record->id }}" {{ $checked->contains($record->id) ? 'checked="checked"' : '' }} />
						</td>
						@foreach ($fields->values($record) as $item) <td><label for="record-{{ $record->id }}">{{ $item }}</label></td> @endforeach
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>

	</div>
	
</form>

@stop
