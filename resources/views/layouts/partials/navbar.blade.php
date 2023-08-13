<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Modern App') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                       <li><a href="{{ route('home.index') }}" class="nav-link px-2 ">Home</a></li>
                        @auth
                        @hasanyrole('Super-Admin|Admin')
                        <li><a href="{{ route('users.index') }}" class="nav-link px-2 ">Users</a></li>
                        <li><a href="{{ route('roles.index') }}" class="nav-link px-2">Roles</a></li>
                        @endrole
                        <li><a href="{{ route('posts.index') }}" class="nav-link px-2">Posts</a></li>
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest

                        @php $locale = session('locale'); 
                            if(empty($locale)) $locale = config('app.locale');
                            $activeLang =  \App\Dexlib\Locale::getActiveLang();
                        @endphp
                        <li class="nav-item dropdown">
                            <a id="langnavbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{$activeLang[$locale]}}
                                <span class="caret"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="langnavbarDropdown">
                                @foreach($activeLang as $key => $value)
                                    <a class="dropdown-item" href="/lang/{{$key}}">{{  $value }}</a>
                                @endforeach 
                            </div>
                        </li> 
                    </ul>
                </div>
            </div>
        </nav>