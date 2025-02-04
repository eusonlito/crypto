<div class="box mt-5">
    <div class="px-5 py-3 border-b border-gray-200">
        <h2 class="font-medium text-base">
            {{ __('wallet-update.buy_stop_title') }}
        </h2>
    </div>

    <div class="p-3">
        <div class="xl:flex">
            <div class="flex-auto p-2">
                <label for="wallet-buy_stop_reference" class="form-label">{{ __('wallet-update.buy_stop_reference') }}</label>
                <input type="number" step="any" name="buy_stop_reference" class="form-control form-control-lg" id="wallet-buy_stop_reference" value="@numberString($REQUEST->input('buy_stop_reference'))">
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-buy_stop_max_value" class="form-label">{{ __('wallet-update.buy_stop_max_value') }}</label>
                <input type="number" step="any" name="buy_stop_max_value" class="form-control form-control-lg" id="wallet-buy_stop_max_value" value="@numberString($REQUEST->input('buy_stop_max_value'))">
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-buy_stop_amount" class="form-label">{{ __('wallet-update.buy_stop_amount') }}</label>
                <input type="number" step="any" name="buy_stop_amount" class="form-control form-control-lg" id="wallet-buy_stop_amount" value="@numberString($REQUEST->input('buy_stop_amount'))" readonly>
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-buy_stop_min_percent" class="form-label">{{ __('wallet-update.buy_stop_min_percent') }}</label>
                <input type="number" step="any" name="buy_stop_min_percent" class="form-control form-control-lg" id="wallet-buy_stop_min_percent" value="@value($REQUEST->input('buy_stop_min_percent'), 2)">
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-buy_stop_max_percent" class="form-label">{{ __('wallet-update.buy_stop_max_percent') }}</label>
                <input type="number" step="any" name="buy_stop_max_percent" class="form-control form-control-lg" id="wallet-buy_stop_max_percent" value="@value($REQUEST->input('buy_stop_max_percent'), 2)">
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-buy_stop_min_exchange" class="form-label">{{ __('wallet-update.buy_stop_min_exchange') }}</label>
                <input type="number" step="any" name="buy_stop_min_exchange" class="form-control form-control-lg" id="wallet-buy_stop_min_exchange" value="@numberString($REQUEST->input('buy_stop_min_exchange'))" readonly>
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-buy_stop_max_exchange" class="form-label">{{ __('wallet-update.buy_stop_max_exchange') }}</label>
                <input type="number" step="any" name="buy_stop_max_exchange" class="form-control form-control-lg" id="wallet-buy_stop_max_exchange" value="@numberString($REQUEST->input('buy_stop_max_exchange'))" readonly>
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-buy_stop_min_value" class="form-label">{{ __('wallet-update.buy_stop_min_value') }}</label>
                <input type="number" step="any" name="buy_stop_min_value" class="form-control form-control-lg" id="wallet-buy_stop_min_value" value="@numberString($REQUEST->input('buy_stop_min_value'))" readonly>
            </div>
        </div>

        <div class="xl:flex">
            <div class="flex-initial p-4">
                <div class="form-check">
                    <input type="checkbox" name="buy_stop" value="1" class="form-check-switch" id="wallet-buy_stop" {{ $REQUEST->input('buy_stop') ? 'checked' : '' }}>
                    <label for="wallet-buy_stop" class="form-check-label">{{ __('wallet-update.buy_stop') }}</label>
                </div>
            </div>

            <div class="flex-initial p-4">
                <div class="form-check">
                    <input type="checkbox" name="buy_stop_max_follow" value="1" class="form-check-switch" id="wallet-buy_stop_max_follow" {{ $REQUEST->input('buy_stop_max_follow') ? 'checked' : '' }}>
                    <label for="wallet-buy_stop_max_follow" class="form-check-label">{{ __('wallet-update.buy_stop_max_follow') }}</label>
                </div>
            </div>

            <div class="flex-initial p-4">
                <div class="form-check">
                    <input type="checkbox" name="buy_stop_ai" value="1" class="form-check-switch" id="wallet-buy_stop_ai" {{ $REQUEST->input('buy_stop_ai') ? 'checked' : '' }}>
                    <label for="wallet-buy_stop_ai" class="form-check-label">{{ __('wallet-update.buy_stop_ai') }}</label>
                </div>
            </div>

            <div class="flex-initial p-4">
                <div class="form-check">
                    <input type="checkbox" name="buy_stop_min_at" value="1" class="form-check-switch" id="wallet-buy_stop_min_at" {{ $REQUEST->input('buy_stop_min_at') ? 'checked' : '' }}>
                    <label for="wallet-buy_stop_min_at" class="form-check-label">{{ __('wallet-update.buy_stop_min_at') }}</label>
                </div>
            </div>

            <div class="flex-initial p-4">
                <div class="form-check">
                    <input type="checkbox" name="buy_stop_max_at" value="1" class="form-check-switch" id="wallet-buy_stop_max_at" {{ $REQUEST->input('buy_stop_max_at') ? 'checked' : '' }}>
                    <label for="wallet-buy_stop_max_at" class="form-check-label">{{ __('wallet-update.buy_stop_max_at') }}</label>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="box mt-5">
    <div class="px-5 py-3 border-b border-gray-200">
        <h2 class="font-medium text-base">
            {{ __('wallet-update.sell_stop_title') }}
        </h2>
    </div>

    <div class="p-3">
        <div class="xl:flex">
            <div class="flex-auto p-2">
                <label for="wallet-sell_stop_reference" class="form-label">{{ __('wallet-update.sell_stop_reference') }}</label>
                <input type="number" step="any" name="sell_stop_reference" class="form-control form-control-lg" id="wallet-sell_stop_reference" value="@numberString($REQUEST->input('sell_stop_reference'))">
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-sell_stop_percent" class="form-label">{{ __('wallet-update.sell_stop_percent') }}</label>
                <input type="number" step="any" name="sell_stop_percent" max="100" min="0" class="form-control form-control-lg" id="wallet-sell_stop_percent" value="@numberString($REQUEST->input('sell_stop_percent'))">
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-sell_stop_amount" class="form-label">{{ __('wallet-update.sell_stop_amount') }}</label>
                <input type="number" step="any" name="sell_stop_amount" class="form-control form-control-lg" id="wallet-sell_stop_amount" value="@numberString($REQUEST->input('sell_stop_amount'))" readonly>
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-sell_stop_max_percent" class="form-label">{{ __('wallet-update.sell_stop_max_percent') }}</label>
                <input type="number" step="any" name="sell_stop_max_percent" class="form-control form-control-lg" id="wallet-sell_stop_max_percent" value="@value($REQUEST->input('sell_stop_max_percent'), 2)">
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-sell_stop_min_percent" class="form-label">{{ __('wallet-update.sell_stop_min_percent') }}</label>
                <input type="number" step="any" name="sell_stop_min_percent" class="form-control form-control-lg" id="wallet-sell_stop_min_percent" value="@value($REQUEST->input('sell_stop_min_percent'), 2)">
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-sell_stop_max_exchange" class="form-label">{{ __('wallet-update.sell_stop_max_exchange') }}</label>
                <input type="number" step="any" name="sell_stop_max_exchange" class="form-control form-control-lg" id="wallet-sell_stop_max_exchange" value="@numberString($REQUEST->input('sell_stop_max_exchange'))" readonly>
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-sell_stop_min_exchange" class="form-label">{{ __('wallet-update.sell_stop_min_exchange') }}</label>
                <input type="number" step="any" name="sell_stop_min_exchange" class="form-control form-control-lg" id="wallet-sell_stop_min_exchange" value="@numberString($REQUEST->input('sell_stop_min_exchange'))" readonly>
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-sell_stop_max_value" class="form-label">{{ __('wallet-update.sell_stop_max_value') }}</label>
                <input type="number" step="any" name="sell_stop_max_value" class="form-control form-control-lg" id="wallet-sell_stop_max_value" value="@numberString($REQUEST->input('sell_stop_max_value'))" readonly>
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-sell_stop_min_value" class="form-label">{{ __('wallet-update.sell_stop_min_value') }}</label>
                <input type="number" step="any" name="sell_stop_min_value" class="form-control form-control-lg" id="wallet-sell_stop_min_value" value="@numberString($REQUEST->input('sell_stop_min_value'))" readonly>
            </div>
        </div>

        <div class="xl:flex">
            <div class="flex-initial p-4">
                <div class="form-check">
                    <input type="checkbox" name="sell_stop" value="1" class="form-check-switch" id="wallet-sell_stop" {{ $REQUEST->input('sell_stop') ? 'checked' : '' }}>
                    <label for="wallet-sell_stop" class="form-check-label">{{ __('wallet-update.sell_stop') }}</label>
                </div>
            </div>

            <div class="flex-initial p-4">
                <div class="form-check">
                    <input type="checkbox" name="sell_stop_ai" value="1" class="form-check-switch" id="wallet-sell_stop_ai" {{ $REQUEST->input('sell_stop_ai') ? 'checked' : '' }}>
                    <label for="wallet-sell_stop_ai" class="form-check-label">{{ __('wallet-update.sell_stop_ai') }}</label>
                </div>
            </div>

            <div class="flex-initial p-4">
                <div class="form-check">
                    <input type="checkbox" name="sell_stop_max_at" value="1" class="form-check-switch" id="wallet-sell_stop_max_at" {{ $REQUEST->input('sell_stop_max_at') ? 'checked' : '' }}>
                    <label for="wallet-sell_stop_max_at" class="form-check-label">{{ __('wallet-update.sell_stop_max_at') }}</label>
                </div>
            </div>

            <div class="flex-initial p-4">
                <div class="form-check">
                    <input type="checkbox" name="sell_stop_min_at" value="1" class="form-check-switch" id="wallet-sell_stop_min_at" {{ $REQUEST->input('sell_stop_min_at') ? 'checked' : '' }}>
                    <label for="wallet-sell_stop_min_at" class="form-check-label">{{ __('wallet-update.sell_stop_min_at') }}</label>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="box mt-5">
    <div class="px-5 py-3 border-b border-gray-200">
        <h2 class="font-medium text-base">
            {{ __('wallet-update.sell_stoploss_title') }}
        </h2>
    </div>

    <div class="p-3">
        <div class="xl:flex">
            <div class="flex-auto p-2">
                <label for="wallet-sell_stoploss_percent" class="form-label">{{ __('wallet-update.sell_stoploss_percent') }}</label>
                <input type="number" step="any" name="sell_stoploss_percent" class="form-control form-control-lg" id="wallet-sell_stoploss_percent" value="@value($REQUEST->input('sell_stoploss_percent'), 2)">
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-sell_stoploss_exchange" class="form-label">{{ __('wallet-update.sell_stoploss_exchange') }}</label>
                <input type="number" step="any" name="sell_stoploss_exchange" class="form-control form-control-lg" id="wallet-sell_stoploss_exchange" value="@numberString($REQUEST->input('sell_stoploss_exchange'))" readonly>
            </div>

            <div class="flex-auto p-2">
                <label for="wallet-sell_stoploss_value" class="form-label">{{ __('wallet-update.sell_stoploss_value') }}</label>
                <input type="number" step="any" name="sell_stoploss_value" class="form-control form-control-lg" id="wallet-sell_stoploss_value" value="@numberString($REQUEST->input('sell_stoploss_value'))" readonly>
            </div>
        </div>

        <div class="xl:flex">
            <div class="flex-initial p-4">
                <div class="form-check">
                    <input type="checkbox" name="sell_stoploss" value="1" class="form-check-switch" id="wallet-sell_stoploss" {{ $REQUEST->input('sell_stoploss') ? 'checked' : '' }}>
                    <label for="wallet-sell_stoploss" class="form-check-label">{{ __('wallet-update.sell_stoploss') }}</label>
                </div>
            </div>

            <div class="flex-initial p-4">
                <div class="form-check">
                    <input type="checkbox" name="sell_stoploss_at" value="1" class="form-check-switch" id="wallet-sell_stoploss_at" {{ $REQUEST->input('sell_stoploss_at') ? 'checked' : '' }}>
                    <label for="wallet-sell_stoploss_at" class="form-check-label">{{ __('wallet-update.sell_stoploss_at') }}</label>
                </div>
            </div>
        </div>
    </div>
</div>
