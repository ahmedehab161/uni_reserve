<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>University Hall Booking</title>
    <link rel="stylesheet" href="front-end/assets/style.css">
</head>
<body>

<nav>
    <a href="landing.html">Home</a>
    <a href="index.html">Book Hall</a>
    <a href="{{ ('login') }}">profile</a>
    <a href="{{ route('register') }}">Register</a>
    @if(!Auth::check())
    <a href="{{ route('login') }}">Login</a>
    @else
    <form method="POST" action="{{ route('logout') }}">
    @csrf
        <x-dropdown-link :href="route('logout')"
            onclick="event.preventDefault();
                this.closest('form').submit();">
            {{ __('Log Out') }}
        </x-dropdown-link>
    </form>
    @endif
</nav>

@yield('index_page')

<footer>
    <p>© 2025 University Hall Booking System</p>
</footer>

</body>
</html>
 