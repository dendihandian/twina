@if (Session::has('success'))
    <div class="alert alert-success alert-dismissible rounded-0 mb-0 fade show" role="alert">
        <span>{{ Session::get('success') }}</span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@elseif(Session::has('error'))
    <div class="alert alert-danger alert-dismissible rounded-0 mb-0 fade show" role="alert">
        <span>{{ Session::get('error') }}</span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif