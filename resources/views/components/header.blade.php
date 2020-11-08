<header>
    <nav class="navbar navbar-expand-md navbar-dark bg-teal py-2 px-4 py-md-0">
        <a class="navbar-brand text-white" href="{{ route('landing_page') }}">
            <i class="fab fa-twitter"></i>&nbsp;Twina
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-between" id="navbarSupportedContent">
            <ul class="navbar-nav">
                <li class="nav-item nav-item-teal py-2 px-4 px-md-1"><a href="{{ route('highlighted') }}" class="nav-link text-white">Highlighted Topic</a></li>
                <li class="nav-item nav-item-teal py-2 px-4 px-md-1"><a href="{{ route('public_topics') }}" class="nav-link text-white">Public Topics</a></li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item nav-item-teal py-2 px-4 px-md-1">
                    <a href="{{ route('login') }}" class="nav-link text-white">Login</a>
                </li>
                <li class="nav-item nav-item-teal py-2 px-4 px-md-1">
                    <a href="{{ route('register') }}" class="nav-link text-white">Register</a>
                </li>
            </ul>
        </div>
    </nav>
</header>
