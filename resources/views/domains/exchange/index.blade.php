@extends ('layouts.in')

@section ('body')

<form method="get">
    <div class="sm:flex sm:space-x-4">
        <div class="flex-grow mt-2 sm:mt-0">
            <input type="search" class="form-control form-control-lg" placeholder="{{ __('exchange-index.search') }}" data-table-search="#exchange-list-table" />
        </div>

        <div class="flex-grow mt-2 sm:mt-0">
            <select name="top" class="form-select form-select-lg bg-white" data-change-submit>
                <option value="50" {{ ($filters['top'] === '50') ? 'selected' : '' }}>{{ __('exchange-index.top-50') }}</option>
                <option value="100" {{ ($filters['top'] === '100') ? 'selected' : '' }}>{{ __('exchange-index.top-100') }}</option>
                <option value="all" {{ ($filters['top'] === 'all') ? 'selected' : '' }}>{{ __('exchange-index.top-all') }}</option>
            </select>
        </div>

        <div class="flex-grow mt-2 sm:mt-0">
            <x-exchange-select name="time" :selected="$filters['time']" data-change-submit></x-exchange-select>
        </div>

        <div class="flex-grow mt-2 sm:mt-0">
            <x-select name="platform_id" value="id" text="name" :options="$platforms->toArray()" :selected="$filters['platform_id']" :placeholder="__('exchange-index.platforms-all')" data-change-submit></x-select>
        </div>
    </div>
</form>

@include ('domains.exchange.molecules.list')

@stop
