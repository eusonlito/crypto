@php ($prefix = 'wallet-update-sell-market-modal-'.$row->id)

<div id="{{ $prefix }}" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="{{ $prefix }}-dialog">
        <div class="modal-content">
            <form action="{{ route('wallet.update.sell-market', $row->id) }}" method="POST" data-change-event-change data-wallet>
                <input type="hidden" name="_action" value="updateSellMarket" />

                <div class="modal-header" data-draggable="#{{ $prefix }}-dialog">
                    <h2 class="font-medium text-base mr-auto">{{ __('wallet-update-sell-market.title') }}</h2>
                </div>

                <div class="modal-body">
                    <div class="p-2">
                        <label for="{{ $prefix }}-wallet-amount" class="form-label">{{ __('wallet-update.amount') }}</label>
                        <input type="number" step="any" min="0" name="amount" class="form-control form-control-lg" id="{{ $prefix }}-wallet-amount" value="@numberString($row->amount)" required>
                    </div>

                    <div class="xl:flex">
                        <div class="flex-auto p-2">
                            <label for="{{ $prefix }}-wallet-buy_exchange" class="form-label">{{ __('wallet-update.buy_exchange') }}</label>
                            <input type="number" step="any" name="buy_exchange" class="form-control form-control-lg" id="{{ $prefix }}-wallet-buy_exchange" value="@numberString($row->buy_exchange)" readonly>
                        </div>

                        <div class="flex-auto p-2">
                            <label for="{{ $prefix }}-wallet-current_exchange" class="form-label">{{ __('wallet-update.current_exchange') }}</label>
                            <input type="number" step="any" name="current_exchange" class="form-control form-control-lg" id="{{ $prefix }}-wallet-current_exchange" value="@numberString($row->current_exchange)" readonly>
                        </div>

                        <div class="flex-auto p-2">
                            <label for="{{ $prefix }}-wallet-exchange_difference" class="form-label">{{ __('wallet-update.exchange_difference') }}</label>
                            <input type="number" step="any" name="exchange_difference" class="form-control form-control-lg" id="{{ $prefix }}-wallet-exchange_difference" value="@numberString($row->current_exchange - $row->buy_exchange)" readonly>
                        </div>
                    </div>

                    <div class="xl:flex">
                        <div class="flex-auto p-2">
                            <label for="{{ $prefix }}-wallet-buy_value" class="form-label">{{ __('wallet-update.buy_value') }}</label>
                            <input type="number" step="any" name="buy_value" class="form-control form-control-lg" id="{{ $prefix }}-wallet-buy_value" value="@numberString($row->buy_value)" readonly>
                        </div>

                        <div class="flex-auto p-2">
                            <label for="{{ $prefix }}-wallet-current_value" class="form-label">{{ __('wallet-update.current_value') }}</label>
                            <input type="number" step="any" name="current_value" class="form-control form-control-lg" id="{{ $prefix }}-wallet-current_value" value="@numberString($row->current_value)" readonly>
                        </div>

                        <div class="flex-auto p-2">
                            <label for="{{ $prefix }}-wallet-value_difference" class="form-label">{{ __('wallet-update.value_difference') }}</label>
                            <input type="number" step="any" name="value_difference" class="form-control form-control-lg" id="{{ $prefix }}-wallet-value_difference" value="@numberString($row->current_value - $row->buy_value)" readonly>
                        </div>
                    </div>

                    <div class="p-2">
                        <div class="form-check">
                            <input type="checkbox" name="sell_market_accept" value="1" class="form-check-switch" id="{{ $prefix }}-wallet-sell_market_accept" required>
                            <label for="{{ $prefix }}-wallet-sell_market_accept" class="form-check-label">{{ __('wallet-update-sell-market.accept') }}</label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer text-right">
                    <button type="button" data-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">{{ __('wallet-update-sell-market.cancel') }}</button>
                    <button type="submit" class="btn btn-primary w-20">{{ __('wallet-update-sell-market.send') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
