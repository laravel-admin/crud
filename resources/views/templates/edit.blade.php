@extends('admin::master')

@section('content')
<main>
    <div class="container">
        <form action="{{ route("{$route}update", $model->id) }}" method="post" class="form-horizontal">
            {!! csrf_field() !!}
            {!! method_field('put') !!}

            <div class="page-header">

                <div class="float-md-right">
                    <a href="{!! route("{$route}index") !!}" class="btn btn-default">Terug</a>
                    <button type="submit" class="btn btn-danger" form="destroy_entry">Verwijder</button>
                    <button type="submit" class="btn btn-success">Opslaan</button>

                </div>

                <h1>{{ ucfirst($singular_name) }} wijzigen</h1>
            </div>

            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Settings</h4>
                        </div>
                        <div class="card-block">
                            @foreach ($fields->values() as $field)
                                @if ($field && $view = $field->view()) @include($view, compact($field, $model)) @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <form action="{{ route("{$route}destroy", $model->id) }}" method="post" id="destroy_entry">
            {{ csrf_field() }}
            {{ method_field('delete') }}
        </form>
    </div>
</main>



</div>
@stop
