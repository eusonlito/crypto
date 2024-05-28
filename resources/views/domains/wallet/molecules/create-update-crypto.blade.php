<div class="box mt-5">
    <div class="px-5 py-3 border-b border-gray-200">
        <h2 class="font-medium text-base">
            {{ __('wallet-create.sell_stop_title') }}
        </h2>
    </div>

    <div class="p-3">
        <div class="xl:flex">
            <div class="flex-auto p-2">
                <label for="wallet-sell_stop_percent" class="form-label">{{ __('wallet-create.sell_stop_percent') }}</label>
                <input type="number" name="sell_stop_percent" step="0.1" max="100" min="0" class="form-control form-control-lg" id="wallet-sell_stop_percent" value="@numberString($REQUEST->input('sell_stop_percent'))" data-percent-to-value="wallet-sell_stop_amount" data-percent-to-value-reference="wallet-amount" data-percent-to-value-operation="value">
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-sell_stop_amount" class="form-label">{{ __('wallet-create.sell_stop_amount') }}</label>
                <input type="number" name="sell_stop_amount" step="0.000000001" class="form-control form-control-lg" id="wallet-sell_stop_amount" value="@numberString($REQUEST->input('sell_stop_amount'))" readonly>
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-sell_stop_reference" class="form-label">{{ __('wallet-create.sell_stop_reference') }}</label>
                <input type="number" name="sell_stop_reference" step="0.000000001" class="form-control form-control-lg" id="wallet-sell_stop_reference" value="@numberString($REQUEST->input('sell_stop_reference'))">
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-sell_stop_max_percent" class="form-label">{{ __('wallet-create.sell_stop_max_percent') }}</label>
                <input type="number" name="sell_stop_max_percent" step="0.0001" class="form-control form-control-lg" id="wallet-sell_stop_max_percent" value="@value($REQUEST->input('sell_stop_max_percent'), 2)" data-percent-to-value="wallet-sell_stop_max_exchange" data-percent-to-value-reference="wallet-sell_stop_reference">
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-sell_stop_min_percent" class="form-label">{{ __('wallet-create.sell_stop_min_percent') }}</label>
                <input type="number" name="sell_stop_min_percent" step="0.0001" class="form-control form-control-lg" id="wallet-sell_stop_min_percent" value="@value($REQUEST->input('sell_stop_min_percent'), 2)" data-percent-to-value="wallet-sell_stop_min_exchange" data-percent-to-value-reference="wallet-sell_stop_max_exchange" data-percent-to-value-operation="substract">
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-sell_stop_max_exchange" class="form-label">{{ __('wallet-create.sell_stop_max_exchange') }}</label>
                <input type="number" name="sell_stop_max_exchange" step="0.000000001" class="form-control form-control-lg" id="wallet-sell_stop_max_exchange" value="@numberString($REQUEST->input('sell_stop_max_exchange'))" readonly>
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-sell_stop_min_exchange" class="form-label">{{ __('wallet-create.sell_stop_min_exchange') }}</label>
                <input type="number" name="sell_stop_min_exchange" step="0.000000001" class="form-control form-control-lg" id="wallet-sell_stop_min_exchange" value="@numberString($REQUEST->input('sell_stop_min_exchange'))" readonly>
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-sell_stop_max_value" class="form-label">{{ __('wallet-create.sell_stop_max_value') }}</label>
                <input type="number" name="sell_stop_max_value" step="0.000000001" class="form-control form-control-lg" id="wallet-sell_stop_max_value" value="@numberString($REQUEST->input('sell_stop_max_value'))" data-total data-total-amount="wallet-sell_stop_amount" data-total-value="wallet-sell_stop_max_exchange" data-total-change="wallet-sell_stop_max_percent" readonly>
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-sell_stop_min_value" class="form-label">{{ __('wallet-create.sell_stop_min_value') }}</label>
                <input type="number" name="sell_stop_min_value" step="0.000000001" class="form-control form-control-lg" id="wallet-sell_stop_min_value" value="@numberString($REQUEST->input('sell_stop_min_value'))" data-total data-total-amount="wallet-sell_stop_amount" data-total-value="wallet-sell_stop_min_exchange" data-total-change="wallet-sell_stop_min_percent" readonly>
            </div>
        </div>

        <div class="xl:flex">
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
</div>

<div class="box mt-5">
    <div class="px-5 py-3 border-b border-gray-200">
        <h2 class="font-medium text-base">
            {{ __('wallet-create.buy_stop_title') }}
        </h2>
    </div>

    <div class="p-3">
        <div class="xl:flex">
            <div class="flex-auto p-2">
                <label for="wallet-buy_stop_amount" class="form-label">{{ __('wallet-create.buy_stop_amount') }}</label>
                <input type="number" name="buy_stop_amount" step="0.000000001" class="form-control form-control-lg" id="wallet-buy_stop_amount" value="@numberString($REQUEST->input('buy_stop_amount'))">
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-buy_stop_reference" class="form-label">{{ __('wallet-create.buy_stop_reference') }}</label>
                <input type="number" name="buy_stop_reference" step="0.000000001" class="form-control form-control-lg" id="wallet-buy_stop_reference" value="@numberString($REQUEST->input('buy_stop_reference'))">
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-buy_stop_min_percent" class="form-label">{{ __('wallet-create.buy_stop_min_percent') }}</label>
                <input type="number" name="buy_stop_min_percent" step="0.0001" class="form-control form-control-lg" id="wallet-buy_stop_min_percent" value="@value($REQUEST->input('buy_stop_min_percent'), 2)" data-percent-to-value="wallet-buy_stop_min_exchange" data-percent-to-value-reference="wallet-buy_stop_reference" data-percent-to-value-operation="substract">
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-buy_stop_max_percent" class="form-label">{{ __('wallet-create.buy_stop_max_percent') }}</label>
                <input type="number" name="buy_stop_max_percent" step="0.0001" class="form-control form-control-lg" id="wallet-buy_stop_max_percent" value="@value($REQUEST->input('buy_stop_max_percent'), 2)" data-percent-to-value="wallet-buy_stop_max_exchange" data-percent-to-value-reference="wallet-buy_stop_min_exchange">
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-buy_stop_min_exchange" class="form-label">{{ __('wallet-create.buy_stop_min_exchange') }}</label>
                <input type="number" name="buy_stop_min_exchange" step="0.000000001" class="form-control form-control-lg" id="wallet-buy_stop_min_exchange" value="@numberString($REQUEST->input('buy_stop_min_exchange'))" readonly>
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-buy_stop_max_exchange" class="form-label">{{ __('wallet-create.buy_stop_max_exchange') }}</label>
                <input type="number" name="buy_stop_max_exchange" step="0.000000001" class="form-control form-control-lg" id="wallet-buy_stop_max_exchange" value="@numberString($REQUEST->input('buy_stop_max_exchange'))" readonly>
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-buy_stop_min_value" class="form-label">{{ __('wallet-create.buy_stop_min_value') }}</label>
                <input type="number" name="buy_stop_min_value" step="0.000000001" class="form-control form-control-lg" id="wallet-buy_stop_min_value" value="@numberString($REQUEST->input('buy_stop_min_value'))" data-total data-total-amount="wallet-buy_stop_amount" data-total-value="wallet-buy_stop_min_exchange" data-total-change="wallet-buy_stop_min_percent" readonly>
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-buy_stop_max_value" class="form-label">{{ __('wallet-create.buy_stop_max_value') }}</label>
                <input type="number" name="buy_stop_max_value" step="0.000000001" class="form-control form-control-lg" id="wallet-buy_stop_max_value" value="@numberString($REQUEST->input('buy_stop_max_value'))" data-total data-total-amount="wallet-buy_stop_amount" data-total-value="wallet-buy_stop_max_exchange" data-total-change="wallet-buy_stop_max_percent" readonly>
            </div>
        </div>

        <div class="xl:flex">
            <div class="flex-initial p-4">
                <div class="form-check">
                    <input type="checkbox" name="buy_stop" value="1" class="form-check-switch" id="wallet-buy_stop" {{ $REQUEST->input('buy_stop') ? 'checked' : '' }}>
                    <label for="wallet-buy_stop" class="form-check-label">{{ __('wallet-create.buy_stop') }}</label>
                </div>
            </div>

            <div class="flex-initial p-4">
                <div class="form-check">
                    <input type="checkbox" name="buy_stop_max_follow" value="1" class="form-check-switch" id="wallet-buy_stop_max_follow" {{ $REQUEST->input('buy_stop_max_follow') ? 'checked' : '' }}>
                    <label for="wallet-buy_stop_max_follow" class="form-check-label">{{ __('wallet-create.buy_stop_max_follow') }}</label>
                </div>
            </div>

            <div class="flex-initial p-4">
                <div class="form-check">
                    <input type="checkbox" name="buy_stop_min_at" value="1" class="form-check-switch" id="wallet-buy_stop_min_at" {{ $REQUEST->input('buy_stop_min_at') ? 'checked' : '' }}>
                    <label for="wallet-buy_stop_min_at" class="form-check-label">{{ __('wallet-create.buy_stop_min_at') }}</label>
                </div>
            </div>

            <div class="flex-initial p-4">
                <div class="form-check">
                    <input type="checkbox" name="buy_stop_max_at" value="1" class="form-check-switch" id="wallet-buy_stop_max_at" {{ $REQUEST->input('buy_stop_max_at') ? 'checked' : '' }}>
                    <label for="wallet-buy_stop_max_at" class="form-check-label">{{ __('wallet-create.buy_stop_max_at') }}</label>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="box mt-5">
    <div class="px-5 py-3 border-b border-gray-200">
        <h2 class="font-medium text-base">
            {{ __('wallet-create.sell_stoploss_title') }}
        </h2>
    </div>

    <div class="p-3">
        <div class="xl:flex">
            <div class="flex-auto p-2">
                <label for="wallet-sell_stoploss_percent" class="form-label">{{ __('wallet-create.sell_stoploss_percent') }}</label>
                <input type="number" name="sell_stoploss_percent" step="0.0001" class="form-control form-control-lg" id="wallet-sell_stoploss_percent" value="@value($REQUEST->input('sell_stoploss_percent'), 2)" data-percent-to-value="wallet-sell_stoploss_exchange" data-percent-to-value-reference="wallet-buy_exchange" data-percent-to-value-operation="substract">
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-sell_stoploss_exchange" class="form-label">{{ __('wallet-create.sell_stoploss_exchange') }}</label>
                <input type="number" name="sell_stoploss_exchange" step="0.000000001" class="form-control form-control-lg" id="wallet-sell_stoploss_exchange" value="@numberString($REQUEST->input('sell_stoploss_exchange'))" readonly>
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-sell_stoploss_value" class="form-label">{{ __('wallet-create.sell_stoploss_value') }}</label>
                <input type="number" name="sell_stoploss_value" step="0.000000001" class="form-control form-control-lg" id="wallet-sell_stoploss_value" value="@numberString($REQUEST->input('sell_stoploss_value'))" data-total data-total-amount="wallet-amount" data-total-value="wallet-sell_stoploss_exchange" data-total-change="wallet-sell_stoploss_percent" readonly>
            </div>
        </div>

        <div class="xl:flex">
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
</div>
