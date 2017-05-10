<form action="" class="form-inline" role="search">
    @stack('search-form-hidden')
    <div class="form-group" style="width:70%; margin-right: 5%;">
        <input type="text" name="s" class="form-control" placeholder="Search on..." value="{!! Request::get('s') !!}" style="width: 100%;">
    </div>
    <button type="submit" class="btn btn-default" style="width: 25%; float: right;">Search</button>
</form>
