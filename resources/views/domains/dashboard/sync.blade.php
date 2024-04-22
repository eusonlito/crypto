@extends ('layouts.in')

@section ('body')

<div class="box mt-5 p-5 py-16">
    <form method="post">
        <input type="hidden" name="_action" value="sync">

        @icon('refresh-cw', 'block w-12 h-12 text-theme-17 mx-auto')

        <div class="text-xl font-medium text-center mt-10">{{ __('dashboard-sync.title') }}</div>
        <div class="text-gray-600 px-10 text-center mx-auto mt-2">{{ __('dashboard-sync.message') }}</div>

        <div class="my-10 max-w-md mx-auto">
            <x-select name="platform_id" value="id" text="name" :options="$platforms->toArray()" :placeholder="__('dashboard-sync.platforms-all')"></x-select>
        </div>

        <button type="submit" class="btn btn-rounded-primary py-3 px-4 block mx-auto" data-click-one>{{ __('dashboard-sync.button') }}</button>
    </form>
</div>

@stop
