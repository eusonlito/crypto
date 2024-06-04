@extends ('layouts.in')

@section ('body')

<div class="box p-5">
    <form method="get">
        <x-select name="platform_id" value="id" :text="['name']" :options="$platforms->toArray()" :placeholder="__('wallet-update.platform-placeholder')" :label="__('wallet-update.platform')" :selected="$REQUEST->input('platform_id')" data-change-submit required></x-select>
    </form>
</div>

@if ($products)

<form method="post">
    <input type="hidden" name="_action" value="create" />

    <div class="box p-5 mt-5">
        <div class="grid grid-cols-12 gap-2">
            <div class="col-span-12 mb-2">
                <x-select name="product_id" value="id" :text="['acronym', 'name']" :options="$products->toArray()" :label="__('wallet-update.product')" :selected="$REQUEST->input('product_id')"></x-select>
            </div>

            <div class="col-span-12 mb-2 lg:col-span-3">
                <label for="wallet-address" class="form-label">{{ __('wallet-update.address') }}</label>
                <input type="text" name="address" class="form-control form-control-lg" id="wallet-address" value="{{ $REQUEST->input('address') }}" required>
            </div>

            <div class="col-span-12 mb-2 lg:col-span-2">
                <label for="wallet-name" class="form-label">{{ __('wallet-update.name') }}</label>
                <input type="text" name="name" class="form-control form-control-lg" id="wallet-name" value="{{ $REQUEST->input('name') }}" required>
            </div>

            <div class="col-span-12 mb-2 lg:col-span-1">
                <label for="wallet-order" class="form-label">{{ __('wallet-update.order') }}</label>
                <input type="number" step="any" name="order" class="form-control form-control-lg" id="wallet-order" value="{{ $REQUEST->input('order') }}">
            </div>

            <div class="col-span-12 mb-2 lg:col-span-2">
                <label for="wallet-amount" class="form-label">{{ __('wallet-update.amount') }}</label>
                <input type="number" step="any" name="amount" class="form-control form-control-lg" id="wallet-amount" value="{{ $REQUEST->input('amount') }}" required>
            </div>

            <div class="col-span-12 mb-2 lg:col-span-2">
                <label for="wallet-buy_exchange" class="form-label">{{ __('wallet-update.buy_exchange') }}</label>
                <input type="number" step="any" name="buy_exchange" class="form-control form-control-lg" id="wallet-buy_exchange" value="{{ $REQUEST->input('buy_exchange') }}" required>
            </div>

            <div class="col-span-12 mb-2 lg:col-span-2">
                <label for="wallet-buy_value" class="form-label">{{ __('wallet-update.buy_value') }}</label>
                <input type="number" step="any" name="buy_value" class="form-control form-control-lg" id="wallet-buy_value" value="" data-total data-total-amount="wallet-amount" data-total-value="wallet-buy_exchange" readonly>
            </div>

            <div class="col-span-12 mb-2 lg:col-span-2">
                <div class="form-check">
                    <input type="checkbox" name="enabled" value="1" class="form-check-switch" id="wallet-enabled" {{ $REQUEST->input('enabled', true) ? 'checked' : '' }}>
                    <label for="wallet-enabled" class="form-check-label">{{ __('wallet-update.enabled') }}</label>
                </div>
            </div>

            <div class="col-span-12 mb-2 lg:col-span-2">
                <div class="form-check">
                    <input type="checkbox" name="visible" value="1" class="form-check-switch" id="wallet-visible" {{ $REQUEST->input('visible', true) ? 'checked' : '' }}>
                    <label for="wallet-visible" class="form-check-label">{{ __('wallet-update.visible') }}</label>
                </div>
            </div>
        </div>
    </div>

    @include ('domains.wallet.molecules.create-update-crypto')

    <div class="box p-5 mt-5">
        <div class="text-right">
            <button type="submit" class="btn btn-primary">{{ __('wallet-update.save') }}</button>
        </div>
    </div>
</form>

@endif

@stop
