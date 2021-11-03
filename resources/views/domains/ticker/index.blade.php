@extends ('layouts.in')

@section ('body')

<form method="get">
    <div class="sm:flex sm:space-x-4">
        <div class="flex-grow mt-2 sm:mt-0">
            <input type="search" class="form-control form-control-lg" placeholder="{{ __('ticker-index.search') }}" data-table-search="#ticker-list-table" />
        </div>

        <div class="flex-grow mt-2 sm:mt-0">
            <select name="enabled" class="form-select form-select-lg bg-white" data-change-submit>
                <option value="">{{ __('ticker-index.enabled-all') }}</option>
                <option value="1" {{ ($filters['enabled'] === '1') ? 'selected' : '' }}>{{ __('ticker-index.enabled-yes') }}</option>
                <option value="0" {{ ($filters['enabled'] === '0') ? 'selected' : '' }}>{{ __('ticker-index.enabled-no') }}</option>
            </select>
        </div>

        <div class="flex-grow mt-2 sm:mt-0">
            <x-select name="platform_id" value="id" text="name" :options="$platforms->toArray()" :selected="$filters['platform_id']" :placeholder="__('ticker-index.platforms-all')" data-change-submit></x-select>
        </div>
    </div>
</form>

@include ('domains.ticker.molecules.list')

<div class="mt-5 text-right">
    <a href="{{ route('ticker.create') }}" class="btn btn-primary">{{ __('ticker-index.create') }}</a>
</div>

@stop