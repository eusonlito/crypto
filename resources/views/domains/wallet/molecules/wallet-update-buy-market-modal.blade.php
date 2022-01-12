@php ($prefix = 'wallet-update-buy-market-modal-'.$row->id)

<div id="{{ $prefix }}" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="{{ $prefix }}-dialog">
        <div class="modal-content">
            <form action="{{ route('wallet.update.buy-market', $row->id) }}" method="POST" data-change-event-change>
                <input type="hidden" name="_action" value="updateBuyMarket" />

                <div class="modal-header" data-draggable="#{{ $prefix }}-dialog">
                    <h2 class="font-medium text-base mr-auto">{{ __('wallet-update-buy-market.title') }}</h2>
                </div>

                <div class="modal-body">
                    <div class="lg:flex">
                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-amount" class="form-label">{{ __('wallet-update-buy-market.amount') }}</label>
                            <input type="number" name="amount" step="0.000000001" class="form-control" id="{{ $prefix }}-amount" value="@numberString($row->amount)" readonly>
                        </div>

                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-buy_exchange" class="form-label">{{ __('wallet-update-buy-market.buy_exchange') }}</label>
                            <input type="number" name="buy_exchange" step="0.000000001" class="form-control" id="{{ $prefix }}-buy_exchange" value="@numberString($row->buy_exchange)" readonly>
                        </div>

                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-current_exchange" class="form-label">{{ __('wallet-update-buy-market.current_exchange') }}</label>
                            <input type="number" name="current_exchange" step="0.000000001" class="form-control" id="{{ $prefix }}-current_exchange" value="@numberString($row->current_exchange)" readonly>
                        </div>
                    </div>

                    <div class="lg:flex">
                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-buy_value" class="form-label">{{ __('wallet-update-buy-market.buy_value') }}</label>
                            <input type="number" name="buy_value" step="0.000000001" class="form-control" id="{{ $prefix }}-buy_value" value="@numberString($row->buy_value)" readonly>
                        </div>

                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-current_value" class="form-label">{{ __('wallet-update-buy-market.current_value') }}</label>
                            <input type="number" name="current_value" step="0.000000001" class="form-control" id="{{ $prefix }}-current_value" value="@numberString($row->current_value)" readonly>
                        </div>
                    </div>

                    <div class="lg:flex">
                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-buy_market_amount" class="form-label">{{ __('wallet-update-buy-market.buy_market_amount') }}</label>
                            <input type="number" name="buy_market_amount" step="0.000000001" class="form-control" id="{{ $prefix }}-buy_market_amount" value="@numberString($row->buy_market_amount)">
                        </div>

                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-buy_market_reference" class="form-label">{{ __('wallet-update-buy-market.buy_market_reference') }}</label>
                            <input type="number" name="buy_market_reference" step="0.000000001" class="form-control" id="{{ $prefix }}-buy_market_reference" value="@numberString($row->buy_market_reference)">
                        </div>

                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-buy_market_percent" class="form-label">{{ __('wallet-update-buy-market.buy_market_percent') }}</label>
                            <input type="number" name="buy_market_percent" step="0.0001" class="form-control" id="{{ $prefix }}-buy_market_percent" value="@value($row->buy_market_percent, 2)" data-percent-to-value="{{ $prefix }}-buy_market_exchange" data-percent-to-value-reference="{{ $prefix }}-buy_market_reference">
                        </div>
                    </div>

                    <div class="lg:flex">
                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-buy_market_exchange" class="form-label">{{ __('wallet-update-buy-market.buy_market_exchange') }}</label>
                            <input type="number" name="buy_market_exchange" step="0.000000001" class="form-control" id="{{ $prefix }}-buy_market_exchange" value="@numberString($row->buy_market_exchange)" readonly>
                        </div>

                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-buy_market_value" class="form-label">{{ __('wallet-update-buy-market.buy_market_value') }}</label>
                            <input type="number" name="buy_market_value" step="0.000000001" class="form-control" id="{{ $prefix }}-buy_market_value" value="@numberString($row->buy_market_value)" data-total data-total-amount="{{ $prefix }}-buy_market_amount" data-total-value="{{ $prefix }}-buy_market_exchange" data-total-change="{{ $prefix }}-buy_market_percent" readonly>
                        </div>
                    </div>

                    <div class="lg:flex">
                        <div class="flex-1 p-1 pt-2">
                            <div class="form-check">
                                <input type="checkbox" name="buy_market" value="1" class="form-check-switch" id="{{ $prefix }}-buy_market" {{ $row->buy_market ? 'checked' : '' }}>
                                <label for="{{ $prefix }}-buy_market" class="form-check-label">{{ __('wallet-update-buy-market.buy_market') }}</label>
                            </div>
                        </div>

                        <div class="flex-1 p-1 pt-2">
                            <div class="form-check">
                                <input type="checkbox" name="buy_market_at" value="1" class="form-check-switch" id="{{ $prefix }}-buy_market_at" {{ $row->buy_market_at ? 'checked' : '' }}>
                                <label for="{{ $prefix }}-buy_market_at" class="form-check-label">{{ __('wallet-update-buy-market.buy_market_at') }}</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer text-right">
                    <button type="button" data-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">{{ __('wallet-update-buy-market.cancel') }}</button>
                    <button type="submit" class="btn btn-primary w-20">{{ __('wallet-update-buy-market.send') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
