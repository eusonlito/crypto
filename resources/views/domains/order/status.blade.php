@extends ('layouts.in')

@section ('body')

<form method="get">
    <button type="submit" class="hidden"></button>

    <div class="sm:flex sm:space-x-4">
        <div class="flex-grow mt-2 sm:mt-0">
            <input type="search" class="form-control form-control-lg" placeholder="{{ __('order-status.search') }}" data-table-search="#order-status-table" />
        </div>

        <div class="flex-grow mt-2 sm:mt-0">
            <input type="text" name="date_start" class="form-control form-control-lg" value="{{ $filters['date_start'] }}" placeholder="{{ __('order-status.date_start') }}" />
        </div>

        <div class="flex-grow mt-2 sm:mt-0">
            <input type="text" name="date_end" class="form-control form-control-lg" value="{{ $filters['date_end'] }}" placeholder="{{ __('order-status.date_end') }}" />
        </div>

        <div class="flex-grow mt-2 sm:mt-0">
            <x-select name="platform_id" value="id" text="name" :options="$platforms->toArray()" :selected="$filters['platform_id']" :placeholder="__('order-status.platforms-all')" data-change-submit></x-select>
        </div>
    </div>
</form>

@include ('domains.order.molecules.status')

@stop