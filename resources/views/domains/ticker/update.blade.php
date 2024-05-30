@extends ('layouts.in')

@section ('body')

<form id="ticker-form" method="post">
    <input type="hidden" name="_action" value="update" />

    <div class="box p-5">
        <div class="flex">
            <div class="flex-auto p-1 mt-2 md:mt-0">
                <x-select name="platform_id" value="id" :text="['name']" :options="$platforms->toArray()" :label="__('ticker-update.platform')" :selected="$row->platform_id" disabled></x-select>
            </div>

            <div class="flex-auto p-1 mt-2 md:mt-0">
                <x-select name="product_id" value="id" :text="['name']" :options="$products->toArray()" :label="__('ticker-update.product')" :selected="$row->product_id"></x-select>
            </div>
        </div>

        <div class="lg:flex">
            <div class="flex-auto p-1 mt-2">
                <label for="ticker-date_at" class="form-label">{{ __('ticker-update.date_at') }}</label>
                <input type="text" name="date_at" class="form-control form-control-lg" id="ticker-date_at" value="{{ $row->date_at }}">
            </div>

            <div class="flex-auto p-1 mt-2">
                <label for="ticker-amount" class="form-label">{{ __('ticker-update.amount') }}</label>
                <input type="number" step="any" name="amount" class="form-control form-control-lg" id="ticker-amount" value="@numberString($row->amount)">
            </div>

            <div class="flex-auto p-1 mt-2">
                <label for="ticker-exchange_reference" class="form-label">{{ __('ticker-update.exchange_reference') }}</label>
                <input type="number" step="any" name="exchange_reference" class="form-control form-control-lg" id="ticker-exchange_reference" value="@numberString($row->exchange_reference)">
            </div>

            <div class="flex-auto p-1 mt-2">
                <label for="ticker-exchange_current" class="form-label">{{ __('ticker-update.exchange_current') }}</label>
                <input type="number" step="any" name="exchange_current" class="form-control form-control-lg" id="ticker-exchange_current" value="@numberString($row->exchange_current)" readonly>
            </div>

            <div class="flex-auto p-1 mt-2">
                <label for="ticker-value_reference" class="form-label">{{ __('ticker-update.value_reference') }}</label>
                <input type="number" step="any" name="value_reference" class="form-control form-control-lg" id="ticker-value_reference" value="@numberString($row->value_reference)" data-total data-total-amount="ticker-amount" data-total-value="ticker-exchange_reference" readonly>
            </div>

            <div class="flex-auto p-1 mt-2">
                <label for="ticker-value_current" class="form-label">{{ __('ticker-update.value_current') }}</label>
                <input type="number" step="any" name="value_current" class="form-control form-control-lg" id="ticker-value_current" value="@numberString($row->value_current)" data-total data-total-amount="ticker-amount" data-total-value="ticker-exchange_current" readonly>
            </div>
        </div>

        <div class="lg:flex">
            <div class="flex-auto p-1 mt-2">
                <label for="ticker-exchange_min" class="form-label">{{ __('ticker-update.exchange_min') }}</label>
                <input type="number" step="any" name="exchange_min" class="form-control form-control-lg" id="ticker-exchange_min" value="@numberString($row->exchange_min)" readonly>
            </div>

            <div class="flex-auto p-1 mt-2">
                <label for="ticker-value_min" class="form-label">{{ __('ticker-update.value_min') }}</label>
                <input type="number" step="any" name="value_min" class="form-control form-control-lg" id="ticker-value_min" value="@numberString($row->value_min)" data-total data-total-amount="ticker-amount" data-total-value="ticker-exchange_min" readonly>
            </div>

            <div class="flex-auto p-1 mt-2">
                <label for="ticker-exchange_max" class="form-label">{{ __('ticker-update.exchange_max') }}</label>
                <input type="number" step="any" name="exchange_max" class="form-control form-control-lg" id="ticker-exchange_max" value="@numberString($row->exchange_max)" readonly>
            </div>

            <div class="flex-auto p-1 mt-2">
                <label for="ticker-value_max" class="form-label">{{ __('ticker-update.value_max') }}</label>
                <input type="number" step="any" name="value_max" class="form-control form-control-lg" id="ticker-value_max" value="@numberString($row->value_max)" data-total data-total-amount="ticker-amount" data-total-value="ticker-exchange_max" readonly>
            </div>
        </div>

        <div class="flex">
            <div class="flex-initial p-4 pl-0">
                <div class="form-check">
                    <input type="checkbox" name="enabled" value="1" class="form-check-switch" id="ticker-enabled" {{ $row->enabled ? 'checked' : '' }}>
                    <label for="ticker-enabled" class="form-check-label">{{ __('ticker-update.enabled') }}</label>
                </div>
            </div>
        </div>
    </div>

    <div class="box p-5 mt-5">
        <div class="text-right">
            <a href="javascript:;" data-toggle="modal" data-target="#delete-modal" class="btn btn-outline-danger mr-5">{{ __('ticker-update.delete.button') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('ticker-update.save') }}</button>
        </div>
    </div>
</form>

<div id="delete-modal" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body p-0">
                <form method="post">
                    <input type="hidden" name="_action" value="delete" />
                    <div class="p-5 text-center">
                        @icon('x-circle', 'w-16 h-16 text-theme-24 mx-auto mt-3')
                        <div class="text-3xl mt-5">{{ __('ticker-update.delete.title') }}</div>
                        <div class="text-gray-600 mt-2">{{ __('ticker-update.delete.message') }}</div>
                    </div>

                    <div class="px-5 pb-8 text-center">
                        <button type="button" data-dismiss="modal" class="btn btn-outline-secondary w-24 mr-1">{{ __('ticker-update.delete.cancel') }}</button>
                        <button type="submit" class="btn btn-danger w-24">{{ __('ticker-update.delete.delete') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@stop
