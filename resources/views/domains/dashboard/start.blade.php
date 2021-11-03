@extends ('layouts.in')

@section ('body')

<div class="flex text-center mt-10">
    <div class="hidden xl:flex justify-center flex-col flex-1 sm:px-10 lg:px-5 pb-10 lg:pb-0">
        <div class="font-medium text-lg">{{ __('dashboard-start.title') }}</div>

        <div class="mt-3 lg:text-justify text-gray-700">
            <p>{{ __('dashboard-start.intro') }}</p>
        </div>
    </div>

    @if ($AUTH->plarformsPivot()->count())

    <div class="flex-1 box py-16 lg:ml-5 mb-5 lg:mb-0">
        @icon('shuffle', 'block w-12 h-12 text-theme-17 mx-auto')

        <div class="text-xl font-medium mt-10">{{ __('dashboard-start.order-sync.title') }}</div>
        <div class="text-gray-600 px-10 mx-auto mt-2">{{ __('dashboard-start.order-sync.intro') }}</div>

        <a href="{{ route('order.sync') }}" class="btn btn-rounded-primary py-3 px-4 mx-auto mt-8">{{ __('dashboard-start.order-sync.button') }}</a>
    </div>

    <div class="flex-1 box py-16 lg:ml-5 mb-5 lg:mb-0">
        @icon('refresh-cw', 'block w-12 h-12 text-theme-17 mx-auto')

        <div class="text-xl font-medium mt-10">{{ __('dashboard-start.sync.title') }}</div>
        <div class="text-gray-600 px-10 mx-auto mt-2">{{ __('dashboard-start.sync.intro') }}</div>

        <a href="{{ route('dashboard.sync') }}" class="btn btn-rounded-primary py-3 px-4 mx-auto mt-8">{{ __('dashboard-start.sync.button') }}</a>
    </div>

    @else

    <div class="flex-1 box py-16 lg:ml-5 mb-5 lg:mb-0">
        @icon('key', 'block w-12 h-12 text-theme-17 mx-auto')

        <div class="text-xl font-medium mt-10">{{ __('dashboard-start.account.title') }}</div>
        <div class="text-gray-600 px-10 mx-auto mt-2">{{ __('dashboard-start.account.intro') }}</div>

        <a href="{{ route('user.update.platform') }}" class="btn btn-rounded-primary py-3 px-4 mx-auto mt-8">{{ __('dashboard-start.account.button') }}</a>
    </div>

    @endif

    <div class="flex-1 box py-16 lg:ml-5 mb-5 lg:mb-0">
        @icon('book-open', 'block w-12 h-12 text-theme-17 mx-auto')

        <div class="text-xl font-medium mt-10">{{ __('dashboard-start.wallet.title') }}</div>
        <div class="text-gray-600 px-10 mx-auto mt-2">{{ __('dashboard-start.wallet.intro') }}</div>

        <a href="{{ route('wallet.index') }}" class="btn btn-rounded-primary py-3 px-4 mx-auto mt-8">{{ __('dashboard-start.wallet.button') }}</a>
    </div>
</div>

@stop