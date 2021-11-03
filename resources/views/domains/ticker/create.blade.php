@extends ('layouts.in')

@section ('body')

<div class="box p-5">
    <form method="get">
        <x-select name="platform_id" value="id" :text="['name']" :options="$platforms->toArray()" :placeholder="__('ticker-create.platform-placeholder')" :label="__('ticker-create.platform')" :selected="$REQUEST->input('platform_id')" data-change-submit required></x-select>
    </form>
</div>

@if ($products)

<form method="post">
    <input type="hidden" name="_action" value="create" />
    <input type="hidden" name="platform_id" value="{{ $REQUEST->input('platform_id') }}" />

    <div class="box p-5 mt-5">
        <div class="lg:flex">
            <div class="flex-auto p-1 mt-1">
                <x-select name="product_id" value="id" :text="['name']" :options="$products->toArray()" :label="__('ticker-create.product')" :selected="$REQUEST->input('product_id')"></x-select>
            </div>

            <div class="flex-auto p-1 mt-1">
                <label for="ticker-date_at" class="form-label">{{ __('ticker-create.date_at') }}</label>
                <input type="text" name="date_at" class="form-control form-control-lg" id="ticker-date_at" value="{{ $REQUEST->input('date_at') ?: date('Y-m-d H:i:s') }}">
            </div>

            <div class="flex-auto p-1 mt-1">
                <label for="ticker-amount" class="form-label">{{ __('ticker-create.amount') }}</label>
                <input type="number" name="amount" step="0.000000001" class="form-control form-control-lg" id="ticker-amount" value="{{ $REQUEST->input('amount') }}">
            </div>

            <div class="flex-auto p-1 mt-1">
                <label for="ticker-exchange_reference" class="form-label">{{ __('ticker-create.exchange_reference') }}</label>
                <input type="number" name="exchange_reference" step="0.000000001" class="form-control form-control-lg" id="ticker-exchange_reference" value="{{ $REQUEST->input('exchange_reference') }}">
            </div>

            <div class="flex-auto p-1 mt-1">
                <label for="ticker-value_reference" class="form-label">{{ __('ticker-create.value_reference') }}</label>
                <input type="number" name="value_reference" class="form-control form-control-lg" id="ticker-value_reference" data-total data-total-amount="ticker-amount" data-total-value="ticker-exchange_reference" readonly>
            </div>
        </div>

        <div class="flex">
            <div class="flex-initial p-4 pl-0">
                <div class="form-check">
                    <input type="checkbox" name="enabled" value="1" class="form-check-switch" id="ticker-enabled" {{ $REQUEST->input('enabled', true) ? 'checked' : '' }}>
                    <label for="ticker-enabled" class="form-check-label">{{ __('ticker-create.enabled') }}</label>
                </div>
            </div>
        </div>
    </div>

    <div class="box p-5 mt-5">
        <div class="text-right">
            <button type="submit" class="btn btn-primary">{{ __('ticker-create.save') }}</button>
        </div>
    </div>
</form>

@endif

@stop
