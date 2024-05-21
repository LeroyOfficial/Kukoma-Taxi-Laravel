<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

@php
    $page_title = 'Signup';
@endphp
@include('components.new.css')

<body>
    @include('components.new.nav')

    <div id="page-hero" class="page-hero text-center text-white d-flex align-items-center justify-content-center">
        <div>
            <span class="text-capitalize">
            <a href={{url("/")}}>Home</a>
            <span class="ms-1 color-main">// signup</span>
        </span>
            <h1 class="text-uppercase fw-bold">signup</h1>
        </div>
    </div>

    <div class="section">
        <div>
            <div class="text-center py-4">
                <i class="far fa-user color-main fs-1"></i>
                <h2 class="text-capitalize">Signup</h2>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <x-validation-errors class="mb-4" />

                <div class="row row-cols-1 row-cols-md-2 py-4">
                    <div class="mb-4">
                        <label class="form-label text-capitalize fw-bold" for="name">First Name</label>
                        <input class="form-control" type="text" required="" id="name" name="first_name" placeholder="Name">
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-capitalize fw-bold" for="name">Last Name</label>
                        <input class="form-control" type="text" required="" id="name" name="last_name" placeholder="Name">
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-capitalize fw-bold" for="phone">Phone Number</label>
                        <input class="form-control" type="text" required="" id="phone" name="phone" placeholder="PHone Number">
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-capitalize fw-bold" for="email">Email</label>
                        <input class="form-control" type="email" required="" id="email" name="email" placeholder="Email">
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-capitalize fw-bold" for="password">Password</label>
                        <input class="form-control" type="password" required="" id="password" name="password" placeholder="Password">
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-capitalize fw-bold" for="password-confirmation">Password</label>
                        <input class="form-control" type="password" required="" id="password-confirmation" name="password_confirmation" placeholder="Password Confirmation">
                    </div>
                </div>
                <div class="text-center py-4">
                    <button class="btn btn-main fs-5 text-uppercase rounded-pill py-2 px-5" type="submit">signup</button>
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

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <x-label for="name" value="{{ __('Name') }}" />
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-label for="terms">
                        <div class="flex items-center">
                            <x-checkbox name="terms" id="terms" required />

                            <div class="ml-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button class="ml-4">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout> --}}
