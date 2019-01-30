<div class="col-sm-2">

	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Options</h3>
		</div>
		<div class="list-group">
			@foreach ($submenu as $item)
				@php
					$type = (isset($item['type'])) ? $item['type'] : 'primary';
					$target = (isset($item['target'])) ? $item['type'] : '_self';
				@endphp
				<a class="list-group-item list-group-item-{{ $type }}" href="{{ $item['url'] }}" target="{{ $target }}">{{ $item['title'] }}</a>
			@endforeach
		</div>
	</div>

	@if($additional_submenu)
		<div class="panel panel-default">
			<div class="list-group">
				@foreach ($additional_submenu as $item)
					@php
						$type = (isset($item['type'])) ? $item['type'] : 'primary';
						$target = (isset($item['target'])) ? $item['type'] : '_self';
					@endphp
					<a class="list-group-item list-group-item-{{ $type }}" href="{{ $item['url'] }}" target="{{ $target }}">{{ $item['title'] }}</a>
				@endforeach
			</div>
		</div>
	@endif

</div>
