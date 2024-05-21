<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

@php
    $page_title = 'Login';
@endphp
@include('components.new.css')

<body>
    @include('components.new.nav')

    <div id="page-hero" class="page-hero text-center text-white d-flex align-items-center justify-content-center">
        <div>
            <span class="text-capitalize">
            <a href={{url("/")}}>Home</a>
            <span class="ms-1 color-main">// login</span>
        </span>
            <h1 class="text-uppercase fw-bold">login</h1>
        </div>
    </div>
    <div class="section">
        <div>
            <div class="text-center py-4">
                <i class="far fa-user color-main fs-1"></i>
                <h2 class="text-capitalize">login</h2>
            </div>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <x-validation-errors class="mb-4" />

                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif
                <div class="row row-cols-1 row-cols-md-2 justify-content-center py-4">
                    <div class="mb-4">
                        <label class="form-label text-capitalize fw-bold" for="email">Email Address</label>
                        <input class="form-control" type="email" id="email" required="" name="email" placeholder="Email Address" :value="old('email')" autofocus autocomplete="username">
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-capitalize fw-bold" id="password" for="password">Password</label>
                        <input class="form-control" type="password" required="" name="password" placeholder="Password" autocomplete="current-password" />
                    </div>
                </div>
                <div class="text-center py-4">
                    <button class="btn btn-main fs-5 text-uppercase rounded-pill py-2 px-5" type="submit">Login</button>
                </div>
            </form>
        </div>
    </div>

    @include('components.new.footer')
</body>

</html>

{{-- <x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-button class="ml-4">
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout> --}}

