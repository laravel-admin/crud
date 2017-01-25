<div class="col-sm-3">

	<div class="panel panel-default">
		<div class="panel-heading">
			Options
		</div>
		<div class="list-group">@foreach ($submenu as $item)
			<a class="list-group-item" href="{{ $item['url'] }}">{{ $item['title'] }}</a>
		@endforeach </div>
	</div>

</div>
