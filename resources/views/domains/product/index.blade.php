@extends ('layouts.in')

@section ('body')

<form method="get">
    <div class="sm:flex sm:space-x-4">
        <div class="flex-grow mt-2 sm:mt-0">
            <input type="search" class="form-control form-control-lg" placeholder="{{ __('product-index.search') }}" data-table-search="#product-list-table" />
        </div>

        <div class="flex-grow mt-2 sm:mt-0">
            <select name="favorite" class="form-select form-select-lg bg-white" data-change-submit>
                <option value="">{{ __('product-index.favorite-all') }}</option>
                <option value="1" {{ ($filters['favorite'] === '1') ? 'selected' : '' }}>{{ __('product-index.favorite-yes') }}</option>
            </select>
        </div>

        <div class="flex-grow mt-2 sm:mt-0">
            <select name="enabled" class="form-select form-select-lg bg-white" data-change-submit>
                <option value="">{{ __('product-index.enabled-all') }}</option>
                <option value="1" {{ ($filters['enabled'] === '1') ? 'selected' : '' }}>{{ __('product-index.enabled-yes') }}</option>
                <option value="0" {{ ($filters['enabled'] === '0') ? 'selected' : '' }}>{{ __('product-index.enabled-no') }}</option>
            </select>
        </div>

        <div class="flex-grow mt-2 sm:mt-0">
            <x-select name="platform_id" value="id" text="name" :options="$platforms->toArray()" :selected="$filters['platform_id']" :placeholder="__('product-index.platforms-all')" data-change-submit></x-select>
        </div>
    </div>
</form>

@include ('domains.product.molecules.list')

@stop