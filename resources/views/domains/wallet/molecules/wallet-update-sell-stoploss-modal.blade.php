@php ($prefix = 'wallet-update-sell-stoploss-modal-'.$row->id)

<div id="{{ $prefix }}" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="{{ $prefix }}-dialog">
        <div class="modal-content">
            <form action="{{ route('wallet.update.sell-stoploss', $row->id) }}" method="POST" data-change-event-change data-wallet>
                <input type="hidden" name="_action" value="updateSellStopLoss" />

                <div class="modal-header" data-draggable="#{{ $prefix }}-dialog">
                    <h2 class="font-medium text-base mr-auto">{{ $row->name }} - {{ __('wallet-update-sell-stoploss.title') }}</h2>
                </div>

                <div class="modal-body">
                    <div class="p-2">
                        <label for="{{ $prefix }}-wallet-sell_stoploss_percent" class="form-label">{{ __('wallet-update.sell_stoploss_percent') }}</label>
                        <input type="number" step="any" name="sell_stoploss_percent" class="form-control form-control-lg" id="{{ $prefix }}-wallet-sell_stoploss_percent" value="@value($row->sell_stoploss_percent, 2)">
                    </div>

                    <div class="xl:flex">
                        <div class="flex-auto p-2">
                            <label for="{{ $prefix }}-wallet-amount" class="form-label">{{ __('wallet-update.amount') }}</label>
                            <input type="number" step="any" name="amount" class="form-control form-control-lg" id="{{ $prefix }}-wallet-amount" value="@numberString($row->amount)" readonly>
                        </div>

                        <div class="flex-auto p-2">
                            <label for="{{ $prefix }}-wallet-buy_exchange" class="form-label">{{ __('wallet-update.buy_exchange') }}</label>
                            <input type="number" step="any" name="buy_exchange" class="form-control form-control-lg" id="{{ $prefix }}-wallet-buy_exchange" value="@numberString($row->buy_exchange)" readonly>
                        </div>

                        <div class="flex-auto p-2">
                            <label for="{{ $prefix }}-wallet-buy_value" class="form-label">{{ __('wallet-update.buy_value') }}</label>
                            <input type="number" step="any" name="buy_value" class="form-control form-control-lg" id="{{ $prefix }}-wallet-buy_value" value="@numberString($row->buy_value)" readonly>
                        </div>
                    </div>

                    <div class="xl:flex">
                        <div class="flex-auto p-2">
                            <label for="{{ $prefix }}-wallet-sell_stoploss_exchange" class="form-label">{{ __('wallet-update.sell_stoploss_exchange') }}</label>
                            <input type="number" step="any" name="sell_stoploss_exchange" class="form-control form-control-lg" id="{{ $prefix }}-wallet-sell_stoploss_exchange" value="@numberString($row->sell_stoploss_exchange)" readonly>
                        </div>

                        <div class="flex-auto p-2">
                            <label for="{{ $prefix }}-wallet-sell_stoploss_value" class="form-label">{{ __('wallet-update.sell_stoploss_value') }}</label>
                            <input type="number" step="any" name="sell_stoploss_value" class="form-control form-control-lg" id="{{ $prefix }}-wallet-sell_stoploss_value" value="@numberString($row->sell_stoploss_value)" readonly>
                        </div>
                    </div>

                    <div class="p-2">
                        <div class="form-check">
                            <input type="checkbox" name="sell_stoploss" value="1" class="form-check-switch" id="{{ $prefix }}-wallet-sell_stoploss" {{ $row->sell_stoploss ? 'checked' : '' }}>
                            <label for="{{ $prefix }}-wallet-sell_stoploss" class="form-check-label">{{ __('wallet-update.sell_stoploss') }}</label>
                        </div>
                    </div>

                    <div class="p-2">
                        <div class="form-check">
                            <input type="checkbox" name="sell_stoploss_at" value="1" class="form-check-switch" id="{{ $prefix }}-wallet-sell_stoploss_at" {{ $row->sell_stoploss_at ? 'checked' : '' }}>
                            <label for="{{ $prefix }}-wallet-sell_stoploss_at" class="form-check-label">{{ __('wallet-update.sell_stoploss_at') }}</label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer text-right">
                    <button type="button" data-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">{{ __('wallet-update-sell-stoploss.cancel') }}</button>
                    <button type="submit" class="btn btn-primary w-20">{{ __('wallet-update-sell-stoploss.send') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
