@extends ('layouts.in')

@section ('body')

<form method="get">
    <div class="sm:flex sm:space-x-4">
        <div class="flex-grow mt-2 sm:mt-0">
            <input type="search" class="form-control form-control-lg" placeholder="{{ __('order-sync.search') }}" data-table-search="#product-list-table" />
        </div>

        <div class="flex-grow mt-2 sm:mt-0">
            <x-select name="platform_id" value="id" text="name" :options="$platforms->toArray()" :selected="$filters['platform_id']" :placeholder="__('order-sync.platforms-all')" data-change-submit></x-select>
        </div>
    </div>
</form>

<form method="post">
    <input type="hidden" name="_action" value="syncByProducts" />

    <div class="h-600px overflow-y-auto mt-4">
        <table id="product-list-table" class="table table-report pr-4" data-table-sort>
            <thead>
                <tr class="text-right">
                    <th class="text-left">{{ __('order-sync.code') }}</th>
                    <th class="text-left">{{ __('order-sync.name') }}</th>
                    <th class="text-left">{{ __('order-sync.platform') }}</th>
                    <th class="text-center">{{ __('order-sync.enabled') }}</th>
                    <th class="text-center">{{ __('order-sync.favorite') }}</th>
                    <th class="text-center">{{ __('order-sync.select') }}</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($products as $each)

                <tr class="text-right">
                    <td class="text-left">{{ $each->acronym }}</td>
                    <td class="text-left">{{ $each->name }}</td>
                    <td class="text-left"><a href="{{ $each->platform->url.$each->code }}" rel="nofollow noopener noreferrer" target="_blank">{{ $each->platform->name }}</a></td>
                    <td class="text-center">@status($each->enabled)</td>
                    <td class="text-center">
                        <span class="hidden">{{ $each->userPivot ? '1' : '0' }}</span>
                        @icon('star', $each->userPivot ? 'is-favorite' : '')
                    </td>

                    <td class="text-center">
                        <span class="hidden">{{ $each->selected ? '1' : '0' }}</span>
                        <input type="checkbox" name="product_ids[]" value="{{ $each->id }}" {{ $each->selected ? 'checked' : '' }} />
                    </td>
                </tr>

                @endforeach
            </tbody>
        </table>
    </div>

    <div class="box mt-5 p-4 text-right">
        <button type="submit" class="btn btn-primary" data-click-one>{{ __('order-sync.send') }}</button>
    </div>
</form>

@stop