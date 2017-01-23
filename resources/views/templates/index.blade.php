@extends('admin::master')

@section('content')
	<main>
		<div class="container-fluid">
			<div class="col-md-8">
				<table class="table">
					<thead>
						<tr>
							@foreach ($fields->headings() as $heading) <th>	{{ $heading }} </th> @endforeach
						</tr>
					</thead>
					<tbody>
					@foreach ($records as $record)
						<tr>
							@foreach ($fields->values($record) as $item) <td>	{{ $item }} </td> @endforeach
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</main>
@stop
