@extends ('layouts.in')

@section ('body')

<form method="get">
    <div class="sm:flex sm:space-x-4">
        <div class="flex-grow mt-2 sm:mt-0">
            <input type="search" class="form-control form-control-lg" placeholder="{{ __('wallet-index.search') }}" data-table-search="#wallet-list-table" />
        </div>

        <div class="flex-grow mt-2 sm:mt-0">
            <select name="enabled" class="form-select form-select-lg bg-white" data-change-submit>
                <option value="">{{ __('wallet-index.enabled-all') }}</option>
                <option value="1" {{ ($filters['enabled'] === '1') ? 'selected' : '' }}>{{ __('wallet-index.enabled-yes') }}</option>
                <option value="0" {{ ($filters['enabled'] === '0') ? 'selected' : '' }}>{{ __('wallet-index.enabled-no') }}</option>
            </select>
        </div>

        <div class="flex-grow mt-2 sm:mt-0">
            <select name="visible" class="form-select form-select-lg bg-white" data-change-submit>
                <option value="">{{ __('wallet-index.visible-all') }}</option>
                <option value="1" {{ ($filters['visible'] === '1') ? 'selected' : '' }}>{{ __('wallet-index.visible-yes') }}</option>
                <option value="0" {{ ($filters['visible'] === '0') ? 'selected' : '' }}>{{ __('wallet-index.visible-no') }}</option>
            </select>
        </div>

        <div class="flex-grow mt-2 sm:mt-0">
            <x-select name="platform_id" value="id" text="name" :options="$platforms->toArray()" :selected="$filters['platform_id']" :placeholder="__('wallet-index.platforms-all')" data-change-submit></x-select>
        </div>
    </div>
</form>

@include ('domains.wallet.molecules.list')

<div class="mt-5 text-right">
    <a href="{{ route('wallet.create') }}" class="btn btn-primary">{{ __('wallet-index.create') }}</a>
</div>

@stop
