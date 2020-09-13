@if (Session::has('success'))
    <div class="alert alert-success">
        <div class="container">
            <div class="alert-icon">
                <i class="material-icons">check</i>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="material-icons">clear</i></span>
            </button>
            <span>{{ Session::get('success') }}</span>
        </div>
    </div>
    {{-- <div class="alert alert-success alert-dismissible rounded-0 mb-0 fade show" role="alert">
        <span>{{ Session::get('success') }}</span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div> --}}
@elseif(Session::has('info'))
    <div class="alert alert-info">
        <div class="container">
            <div class="alert-icon">
                <i class="material-icons">info_outline</i>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="material-icons">clear</i></span>
            </button>
            <span>{{ Session::get('info') }}</span>
        </div>
    </div>
    {{-- <div class="alert alert-info alert-dismissible rounded-0 mb-0 fade show" role="alert">
        <span>{{ Session::get('info') }}</span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div> --}}
@elseif(Session::has('warning'))
    <div class="alert alert-warning">
        <div class="container">
            <div class="alert-icon">
                <i class="material-icons">warning</i>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="material-icons">clear</i></span>
            </button>
            <span>{{ Session::get('warning') }}</span>
        </div>
    </div>
    {{-- <div class="alert alert-warning alert-dismissible rounded-0 mb-0 fade show" role="alert">
        <span>{{ Session::get('warning') }}</span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div> --}}
@elseif(Session::has('error'))
    <div class="alert alert-danger">
        <div class="container">
            <div class="alert-icon">
                <i class="material-icons">error_outline</i>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="material-icons">clear</i></span>
            </button>
            <span>{{ Session::get('error') }}</span>
        </div>
    </div>
    {{-- <div class="alert alert-danger alert-dismissible rounded-0 mb-0 fade show" role="alert">
        <span>{{ Session::get('error') }}</span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div> --}}
@endif