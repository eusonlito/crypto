@extends ('layouts.in')

@section ('body')

<form method="get" class="mb-4">
    <div class="sm:flex sm:space-x-4">
        <div class="flex-grow mt-2 sm:mt-0">
            <input type="search" class="form-control form-control-lg" placeholder="{{ __('forecast-index.search') }}" data-table-search="#forecast-list-table" />
        </div>

        <div class="flex-grow mt-2 sm:mt-0">
            <x-select name="selected" :options="$selected_options" :selected="$filters['selected']" :placeholder="__('forecast-index.selected-all')" data-change-submit></x-select>
        </div>

        <div class="flex-grow mt-2 sm:mt-0">
            <x-select name="side" :options="$side_options" :selected="$filters['side']" :placeholder="__('forecast-index.side-all')" data-change-submit></x-select>
        </div>

        <div class="flex-grow mt-2 sm:mt-0">
            <x-select name="wallet_id" value="id" :text="['platform.name', 'name']" :options="$wallets->toArray()" :selected="$filters['wallet_id']" :placeholder="__('forecast-index.wallets-all')" data-change-submit></x-select>
        </div>
    </div>
</form>

@include ('domains.forecast.molecules.list')

@stop