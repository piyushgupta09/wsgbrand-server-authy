<li class="nav-item dropdown">
    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
        {{ Auth::user()->name }}
    </a>

    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

        @foreach (config('panel.userlinks') as $userlinks)
            <a class="dropdown-item" href="{{ route($userlinks['route']) }}">
                {{ __($userlinks['name']) }}
            </a>
        @endforeach

        <a class="dropdown-item" href="{{ route('web.logout') }}"
        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
            {{ __('Logout') }}
        </a>
        <form id="logout-form" action="{{ route('web.logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
</li>