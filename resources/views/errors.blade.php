@if ($notification = Session::get('warning'))
    <div class="alert alert-warning alert-block mt-2">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $notification }}</strong>
    </div>
@endif

@if ($notification = Session::get('success'))
    <div class="alert alert-success alert-block mt-2">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $notification }}</strong>
    </div>
@endif


@if ($notification = Session::get('error'))
    <div class="alert alert-danger alert-block mt-2">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $notification }}</strong>
    </div>
@endif

@if ($notification = Session::get('info'))
    <div class="alert alert-info alert-block mt-2">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $notification }}</strong>
    </div>
@endif
