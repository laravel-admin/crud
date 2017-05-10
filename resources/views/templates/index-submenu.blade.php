<div class="col-md-3">

	<div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">{!! ucfirst($singular_name) !!} opties</h3></div>
        <div class="list-group">
			@if(!empty($submenu))
				@foreach ($submenu as $item)
					<a class="list-group-item" href="{{ $item['url'] }}">{{ $item['title'] }}</a>
				@endforeach
			@endif
			<a class="list-group-item" href="{!! URL::route("{$route}index") !!}">All {!! $plural_name !!}</a>
			@if($handle_bulk && !empty($submenu_bulk))
				@foreach ($submenu_bulk as $item)
					<a class="list-group-item" href="{!! URL::route("{$route}index") !!}?filter={{ $item['id'] }}&amp;set={{ $item['set'] }}">Filter: {{ $item['label_filter'] }}</a>
				@endforeach
			@endif
        </div>
    </div>

    @include('crud::templates.index-search')

</div>
