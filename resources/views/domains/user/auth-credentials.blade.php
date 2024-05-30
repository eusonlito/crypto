@extends ('layouts.out')

@section ('body')

<div class="container sm:px-10">
    <div class="block xl:grid grid-cols-2 gap-4">
        <div class="hidden xl:flex flex-col min-h-screen">
        </div>

        <div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0">
            <div class="my-auto mx-auto xl:ml-20 bg-white xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4">
                <x-message type="error" />

                <form method="post">
                    <input type="hidden" name="_action" value="authCredentials">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                    <div class="mt-8">
                        <input type="email" class="login__input form-control py-3 px-4 border-gray-300 block" name="email" placeholder="{{ __('user-auth-credentials.email') }}" autofocus required>
                        <input type="password" class="login__input form-control py-3 px-4 border-gray-300 block mt-4" name="password" placeholder="{{ __('user-auth-credentials.password') }}" required>
                    </div>

                    <div class="mt-5 xl:mt-8 text-center xl:text-left">
                        <button type="submit" class="btn btn-primary py-3 px-4 w-full">{{ __('user-auth-credentials.login') }}</button>
                        <a href="{{ route('user.signup') }}" class="btn btn-outline-secondary py-3 px-4 mt-5 w-full">{{ __('user-auth-credentials.signup') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@stop
