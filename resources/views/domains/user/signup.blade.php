@extends ('layouts.out')

@section ('body')

<div class="container sm:px-10">
    <div class="block xl:grid grid-cols-2 gap-4">
        <div class="hidden xl:flex flex-col min-h-screen">
        </div>

        <div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0">
            <div class="my-auto mx-auto xl:ml-20 bg-white xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4">
                <h2 class="font-bold text-2xl xl:text-3xl text-center xl:text-left">
                    {{ __('user-signup.title') }}
                </h2>

                <div class="mt-2 text-gray-500 xl:hidden text-center">{{ __('user-signup.email') }}</div>

                <x-message type="error" />

                <form method="post">
                    <input type="hidden" name="_action" value="signup" />
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                    <div class="mt-8">
                        <input type="email" name="email" value="{{ $REQUEST->input('email') }}" class="login__input form-control py-3 px-4 border-gray-300 block mt-4" placeholder="{{ __('user-signup.email') }}" required>
                        <input type="password" name="password" class="login__input form-control py-3 px-4 border-gray-300 block mt-4" minlength="8" placeholder="{{ __('user-signup.password') }}" required>
                        <input type="password" name="password_confirmation" class="login__input form-control py-3 px-4 border-gray-300 block mt-4" minlength="8" placeholder="{{ __('user-signup.password-confirm') }}" required>
                        <input type="password" name="code" class="login__input form-control py-3 px-4 border-gray-300 block mt-4" placeholder="{{ __('user-signup.code') }}" required>
                    </div>

                    <div class="mt-5 xl:mt-8 text-center xl:text-left">
                        <button type="submit" class="btn btn-primary py-3 px-4 w-full">{{ __('user-signup.submit') }}</button>
                        <a href="{{ route('user.auth.credentials') }}" class="btn btn-outline-secondary py-3 px-4 mt-5 w-full">{{ __('user-signup.auth') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@stop
