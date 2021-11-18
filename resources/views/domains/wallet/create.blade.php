@extends ('layouts.in')

@section ('body')

<div class="box p-5">
    <form method="get">
        <x-select name="platform_id" value="id" :text="['name']" :options="$platforms->toArray()" :placeholder="__('wallet-create.platform-placeholder')" :label="__('wallet-create.platform')" :selected="$REQUEST->input('platform_id')" data-change-submit required></x-select>
    </form>
</div>

@if ($products)

<form method="post">
    <input type="hidden" name="_action" value="create" />

    <div class="box p-5 mt-5">
        <div class="grid grid-cols-12 gap-2">
            <div class="col-span-12 mb-2">
                <x-select name="product_id" value="id" :text="['name']" :options="$products->toArray()" :label="__('wallet-create.product')" :selected="$REQUEST->input('product_id')"></x-select>
            </div>

            <div class="col-span-12 mb-2 lg:col-span-3">
                <label for="wallet-address" class="form-label">{{ __('wallet-create.address') }}</label>
                <input type="text" name="address" class="form-control form-control-lg" id="wallet-address" value="{{ $REQUEST->input('address') }}">
            </div>

            <div class="col-span-12 mb-2 lg:col-span-2">
                <label for="wallet-name" class="form-label">{{ __('wallet-create.name') }}</label>
                <input type="text" name="name" class="form-control form-control-lg" id="wallet-name" value="{{ $REQUEST->input('name') }}">
            </div>

            <div class="col-span-12 mb-2 lg:col-span-1">
                <label for="wallet-order" class="form-label">{{ __('wallet-create.order') }}</label>
                <input type="number" name="order" step="1" class="form-control form-control-lg" id="wallet-order" value="{{ $REQUEST->input('order') }}">
            </div>

            <div class="col-span-12 mb-2 lg:col-span-2">
                <label for="wallet-amount" class="form-label">{{ __('wallet-create.amount') }}</label>
                <input type="number" name="amount" step="0.000000001" class="form-control form-control-lg" id="wallet-amount" value="{{ $REQUEST->input('amount') }}">
            </div>

            <div class="col-span-12 mb-2 lg:col-span-2">
                <label for="wallet-buy_exchange" class="form-label">{{ __('wallet-create.buy_exchange') }}</label>
                <input type="number" name="buy_exchange" step="0.000000001" class="form-control form-control-lg" id="wallet-buy_exchange" value="{{ $REQUEST->input('buy_exchange') }}">
            </div>

            <div class="col-span-12 mb-2 lg:col-span-2">
                <label for="wallet-buy_value" class="form-label">{{ __('wallet-update.buy_value') }}</label>
                <input type="number" name="buy_value" class="form-control form-control-lg" id="wallet-buy_value" value="" data-total data-total-amount="wallet-amount" data-total-value="wallet-buy_exchange" readonly>
            </div>

            <div class="col-span-12 mb-2 lg:col-span-2">
                <div class="form-check">
                    <input type="checkbox" name="enabled" value="1" class="form-check-switch" id="wallet-enabled" {{ $REQUEST->input('enabled', true) ? 'checked' : '' }}>
                    <label for="wallet-enabled" class="form-check-label">{{ __('wallet-create.enabled') }}</label>
                </div>
            </div>

            <div class="col-span-12 mb-2 lg:col-span-2">
                <div class="form-check">
                    <input type="checkbox" name="visible" value="1" class="form-check-switch" id="wallet-visible" {{ $REQUEST->input('visible', true) ? 'checked' : '' }}>
                    <label for="wallet-visible" class="form-check-label">{{ __('wallet-create.visible') }}</label>
                </div>
            </div>
        </div>
    </div>

    <div class="box p-5 mt-5">
        <div class="lg:flex">
            <div class="flex-auto p-2">
                <label for="wallet-sell_stop_amount" class="form-label">{{ __('wallet-create.sell_stop_amount') }}</label>
                <input type="number" name="sell_stop_amount" step="0.000000001" class="form-control form-control-lg" id="wallet-sell_stop_amount" value="{{ $REQUEST->input('sell_stop_amount') }}">
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-sell_stop_max" class="form-label">{{ __('wallet-create.sell_stop_max') }}</label>
                <input type="number" name="sell_stop_max" step="0.000000001" class="form-control form-control-lg" id="wallet-sell_stop_max" value="{{ $REQUEST->input('sell_stop_max') }}" data-value-to-percent="wallet-sell_stop_max_percent" data-value-to-percent-reference="wallet-buy_exchange">
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-sell_stop_max_percent" class="form-label">{{ __('wallet-create.sell_stop_max_percent') }}</label>
                <input type="number" name="sell_stop_max_percent" step="0.0001" class="form-control form-control-lg" id="wallet-sell_stop_max_percent" value="{{ $REQUEST->input('sell_stop_max_percent') }}" data-percent-to-value="wallet-sell_stop_max" data-percent-to-value-reference="wallet-buy_exchange">
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-sell_stop_max_value" class="form-label">{{ __('wallet-create.sell_stop_max_value') }}</label>
                <input type="number" name="sell_stop_max_value" step="0.000000001" class="form-control form-control-lg" id="wallet-sell_stop_max_value" value="{{ $REQUEST->input('sell_stop_max_value') }}" data-total data-total-amount="wallet-sell_stop_amount" data-total-value="wallet-sell_stop_max" data-total-change="wallet-sell_stop_max_percent" readonly>
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-sell_stop_min" class="form-label">{{ __('wallet-create.sell_stop_min') }}</label>
                <input type="number" name="sell_stop_min" step="0.000000001" class="form-control form-control-lg" id="wallet-sell_stop_min" value="{{ $REQUEST->input('sell_stop_min') }}" data-value-to-percent="wallet-sell_stop_min_percent" data-value-to-percent-reference="wallet-sell_stop_max">
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-sell_stop_min_percent" class="form-label">{{ __('wallet-create.sell_stop_min_percent') }}</label>
                <input type="number" name="sell_stop_min_percent" step="0.0001" class="form-control form-control-lg" id="wallet-sell_stop_min_percent" value="{{ $REQUEST->input('sell_stop_min_percent') }}" data-percent-to-value="wallet-sell_stop_min" data-percent-to-value-reference="wallet-sell_stop_max" data-percent-to-value-operation="substract">
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-sell_stop_min_value" class="form-label">{{ __('wallet-create.sell_stop_min_value') }}</label>
                <input type="number" name="sell_stop_min_value" step="0.000000001" class="form-control form-control-lg" id="wallet-sell_stop_min_value" value="{{ $REQUEST->input('sell_stop_min_value') }}" data-total data-total-amount="wallet-sell_stop_amount" data-total-value="wallet-sell_stop_min" data-total-change="wallet-sell_stop_min_percent" readonly>
            </div>
        </div>

        <div class="lg:flex">
            <div class="flex-initial p-4">
                <div class="form-check">
                    <input type="checkbox" name="sell_stop" value="1" class="form-check-switch" id="wallet-sell_stop" {{ $REQUEST->input('sell_stop') ? 'checked' : '' }}>
                    <label for="wallet-sell_stop" class="form-check-label">{{ __('wallet-create.sell_stop') }}</label>
                </div>
            </div>

            <div class="flex-initial p-4">
                <div class="form-check">
                    <input type="checkbox" name="sell_stop_max_at" value="1" class="form-check-switch" id="wallet-sell_stop_max_at" {{ $REQUEST->input('sell_stop_max_at') ? 'checked' : '' }}>
                    <label for="wallet-sell_stop_max_at" class="form-check-label">{{ __('wallet-create.sell_stop_max_at') }}</label>
                </div>
            </div>

            <div class="flex-initial p-4">
                <div class="form-check">
                    <input type="checkbox" name="sell_stop_min_at" value="1" class="form-check-switch" id="wallet-sell_stop_min_at" {{ $REQUEST->input('sell_stop_min_at') ? 'checked' : '' }}>
                    <label for="wallet-sell_stop_min_at" class="form-check-label">{{ __('wallet-create.sell_stop_min_at') }}</label>
                </div>
            </div>
        </div>
    </div>

    <div class="box p-5 mt-5">
        <div class="lg:flex">
            <div class="flex-auto p-2">
                <label for="wallet-buy_stop_amount" class="form-label">{{ __('wallet-create.buy_stop_amount') }}</label>
                <input type="number" name="buy_stop_amount" step="0.000000001" class="form-control form-control-lg" id="wallet-buy_stop_amount" value="{{ $REQUEST->input('buy_stop_amount') }}">
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-buy_stop_min" class="form-label">{{ __('wallet-create.buy_stop_min') }}</label>
                <input type="number" name="buy_stop_min" step="0.000000001" class="form-control form-control-lg" id="wallet-buy_stop_min" value="{{ $REQUEST->input('buy_stop_min') }}" data-value-to-percent="wallet-buy_stop_min_percent" data-value-to-percent-reference="wallet-buy_exchange">
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-buy_stop_min_percent" class="form-label">{{ __('wallet-create.buy_stop_min_percent') }}</label>
                <input type="number" name="buy_stop_min_percent" step="0.0001" class="form-control form-control-lg" id="wallet-buy_stop_min_percent" value="{{ $REQUEST->input('buy_stop_min_percent') }}" data-percent-to-value="wallet-buy_stop_min" data-percent-to-value-reference="wallet-buy_exchange" data-percent-to-value-operation="substract">
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-buy_stop_min_value" class="form-label">{{ __('wallet-create.buy_stop_min_value') }}</label>
                <input type="number" name="buy_stop_min_value" step="0.000000001" class="form-control form-control-lg" id="wallet-buy_stop_min_value" value="{{ $REQUEST->input('buy_stop_min_value') }}" data-total data-total-amount="wallet-buy_stop_amount" data-total-value="wallet-buy_stop_min" data-total-change="wallet-buy_stop_min_percent" readonly>
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-buy_stop_max" class="form-label">{{ __('wallet-create.buy_stop_max') }}</label>
                <input type="number" name="buy_stop_max" step="0.000000001" class="form-control form-control-lg" id="wallet-buy_stop_max" value="{{ $REQUEST->input('buy_stop_max') }}" data-value-to-percent="wallet-buy_stop_max_percent" data-value-to-percent-reference="wallet-buy_stop_min">
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-buy_stop_max_percent" class="form-label">{{ __('wallet-create.buy_stop_max_percent') }}</label>
                <input type="number" name="buy_stop_max_percent" step="0.0001" class="form-control form-control-lg" id="wallet-buy_stop_max_percent" value="{{ $REQUEST->input('buy_stop_max_percent') }}" data-percent-to-value="wallet-buy_stop_max" data-percent-to-value-reference="wallet-buy_stop_min">
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-buy_stop_max_value" class="form-label">{{ __('wallet-create.buy_stop_max_value') }}</label>
                <input type="number" name="buy_stop_max_value" step="0.000000001" class="form-control form-control-lg" id="wallet-buy_stop_max_value" value="{{ $REQUEST->input('buy_stop_max_value') }}" data-total data-total-amount="wallet-buy_stop_amount" data-total-value="wallet-buy_stop_max" data-total-change="wallet-buy_stop_max_percent" readonly>
            </div>
        </div>

        <div class="lg:flex">
            <div class="flex-initial p-4">
                <div class="form-check">
                    <input type="checkbox" name="buy_stop" value="1" class="form-check-switch" id="wallet-buy_stop" {{ $REQUEST->input('buy_stop') ? 'checked' : '' }}>
                    <label for="wallet-buy_stop" class="form-check-label">{{ __('wallet-create.buy_stop') }}</label>
                </div>
            </div>

            <div class="flex-initial p-4">
                <div class="form-check">
                    <input type="checkbox" name="buy_stop_max_at" value="1" class="form-check-switch" id="wallet-buy_stop_max_at" {{ $REQUEST->input('buy_stop_max_at') ? 'checked' : '' }}>
                    <label for="wallet-buy_stop_max_at" class="form-check-label">{{ __('wallet-create.buy_stop_max_at') }}</label>
                </div>
            </div>

            <div class="flex-initial p-4">
                <div class="form-check">
                    <input type="checkbox" name="buy_stop_min_at" value="1" class="form-check-switch" id="wallet-buy_stop_min_at" {{ $REQUEST->input('buy_stop_min_at') ? 'checked' : '' }}>
                    <label for="wallet-buy_stop_min_at" class="form-check-label">{{ __('wallet-create.buy_stop_min_at') }}</label>
                </div>
            </div>
        </div>
    </div>

    <div class="box p-5 mt-5">
        <div class="lg:flex">
            <div class="flex-auto p-2">
                <label for="wallet-sell_stoploss_exchange" class="form-label">{{ __('wallet-create.sell_stoploss_exchange') }}</label>
                <input type="number" name="sell_stoploss_exchange" step="0.000000001" class="form-control form-control-lg" id="wallet-sell_stoploss_exchange" value="{{ $REQUEST->input('sell_stoploss_exchange') }}" data-value-to-percent="wallet-sell_stoploss_percent" data-value-to-percent-reference="wallet-buy_exchange">
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-sell_stoploss_percent" class="form-label">{{ __('wallet-create.sell_stoploss_percent') }}</label>
                <input type="number" name="sell_stoploss_percent" step="0.0001" class="form-control form-control-lg" id="wallet-sell_stoploss_percent" value="{{ $REQUEST->input('sell_stoploss_percent') }}" data-percent-to-value="wallet-sell_stoploss_exchange" data-percent-to-value-reference="wallet-buy_exchange" data-percent-to-value-operation="substract">
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-sell_stoploss_value" class="form-label">{{ __('wallet-create.sell_stoploss_value') }}</label>
                <input type="number" name="sell_stoploss_value" step="0.000000001" class="form-control form-control-lg" id="wallet-sell_stoploss_value" value="{{ $REQUEST->input('sell_stoploss_value') }}" data-total data-total-amount="wallet-amount" data-total-value="wallet-sell_stoploss_exchange" data-total-change="wallet-sell_stoploss_percent" readonly>
            </div>
        </div>

        <div class="lg:flex">
            <div class="flex-initial p-4">
                <div class="form-check">
                    <input type="checkbox" name="sell_stoploss" value="1" class="form-check-switch" id="wallet-sell_stoploss" {{ $REQUEST->input('sell_stoploss') ? 'checked' : '' }}>
                    <label for="wallet-sell_stoploss" class="form-check-label">{{ __('wallet-create.sell_stoploss') }}</label>
                </div>
            </div>

            <div class="flex-initial p-4">
                <div class="form-check">
                    <input type="checkbox" name="sell_stoploss_at" value="1" class="form-check-switch" id="wallet-sell_stoploss_at" {{ $REQUEST->input('sell_stoploss_at') ? 'checked' : '' }}>
                    <label for="wallet-sell_stoploss_at" class="form-check-label">{{ __('wallet-create.sell_stoploss_at') }}</label>
                </div>
            </div>
        </div>
    </div>

    <div class="box p-5 mt-5">
        <div class="text-right">
            <button type="submit" class="btn btn-primary">{{ __('wallet-create.save') }}</button>
        </div>
    </div>
</form>

@endif

@stop
