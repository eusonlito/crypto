@php ($prefix = 'wallet-update-sell-stop-modal-'.$row->id)

<div id="{{ $prefix }}" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('wallet.update.sell-stop', $row->id) }}" method="POST" data-change-event-change>
                <input type="hidden" name="_action" value="updateSellStop" />

                <div class="modal-header">
                    <h2 class="font-medium text-base mr-auto">{{ __('wallet-update-sell-stop.title') }}</h2>
                </div>

                <div class="modal-body">
                    <div class="lg:flex">
                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-amount" class="form-label">{{ __('wallet-update-sell-stop.amount') }}</label>
                            <input type="number" name="amount" step="0.000000001" class="form-control" id="{{ $prefix }}-amount" value="@numberString($row->amount)" readonly>
                        </div>

                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-buy_exchange" class="form-label">{{ __('wallet-update-sell-stop.buy_exchange') }}</label>
                            <input type="number" name="buy_exchange" step="0.000000001" class="form-control" id="{{ $prefix }}-buy_exchange" value="@numberString($row->buy_exchange)" readonly>
                        </div>

                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-current_exchange" class="form-label">{{ __('wallet-update-sell-stop.current_exchange') }}</label>
                            <input type="number" name="current_exchange" step="0.000000001" class="form-control" id="{{ $prefix }}-current_exchange" value="@numberString($row->current_exchange)" readonly>
                        </div>
                    </div>

                    <div class="lg:flex">
                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-buy_value" class="form-label">{{ __('wallet-update-sell-stop.buy_value') }}</label>
                            <input type="number" name="buy_value" step="0.000000001" class="form-control" id="{{ $prefix }}-buy_value" value="@numberString($row->buy_value)" readonly>
                        </div>

                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-current_value" class="form-label">{{ __('wallet-update-sell-stop.current_value') }}</label>
                            <input type="number" name="current_value" step="0.000000001" class="form-control" id="{{ $prefix }}-current_value" value="@numberString($row->current_value)" readonly>
                        </div>
                    </div>

                    <div class="lg:flex">
                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-sell_stop_amount" class="form-label">{{ __('wallet-update-sell-stop.sell_stop_amount') }}</label>
                            <input type="number" name="sell_stop_amount" step="0.000000001" class="form-control" id="{{ $prefix }}-sell_stop_amount" value="@numberString($row->sell_stop_amount)">
                        </div>

                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-sell_stop_reference" class="form-label">{{ __('wallet-update-sell-stop.sell_stop_reference') }}</label>
                            <input type="number" name="sell_stop_reference" step="0.000000001" class="form-control" id="{{ $prefix }}-sell_stop_reference" value="@numberString($row->sell_stop_reference)">
                        </div>
                    </div>

                    <div class="lg:flex">
                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-sell_stop_max_percent" class="form-label">{{ __('wallet-update-sell-stop.sell_stop_max_percent') }}</label>
                            <input type="number" name="sell_stop_max_percent" step="0.0001" class="form-control" id="{{ $prefix }}-sell_stop_max_percent" value="@value($row->sell_stop_max_percent, 2)" data-percent-to-value="{{ $prefix }}-sell_stop_max_exchange" data-percent-to-value-reference="{{ $prefix }}-sell_stop_reference">
                        </div>

                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-sell_stop_min_percent" class="form-label">{{ __('wallet-update-sell-stop.sell_stop_min_percent') }}</label>
                            <input type="number" name="sell_stop_min_percent" step="0.0001" class="form-control" id="{{ $prefix }}-sell_stop_min_percent" value="@value($row->sell_stop_min_percent, 2)" data-percent-to-value="{{ $prefix }}-sell_stop_min_exchange" data-percent-to-value-reference="{{ $prefix }}-sell_stop_max_exchange" data-percent-to-value-operation="substract">
                        </div>
                    </div>

                    <div class="lg:flex">
                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-sell_stop_max_exchange" class="form-label">{{ __('wallet-update-sell-stop.sell_stop_max_exchange') }}</label>
                            <input type="number" name="sell_stop_max_exchange" step="0.000000001" class="form-control" id="{{ $prefix }}-sell_stop_max_exchange" value="@numberString($row->sell_stop_max_exchange)" readonly>
                        </div>

                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-sell_stop_min_exchange" class="form-label">{{ __('wallet-update-sell-stop.sell_stop_min_exchange') }}</label>
                            <input type="number" name="sell_stop_min_exchange" step="0.000000001" class="form-control" id="{{ $prefix }}-sell_stop_min_exchange" value="@numberString($row->sell_stop_min_exchange)" readonly>
                        </div>
                    </div>

                    <div class="lg:flex">
                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-sell_stop_max_value" class="form-label">{{ __('wallet-update-sell-stop.sell_stop_max_value') }}</label>
                            <input type="number" name="sell_stop_max_value" step="0.000000001" class="form-control" id="{{ $prefix }}-sell_stop_max_value" value="@numberString($row->sell_stop_max_value)" data-total data-total-amount="{{ $prefix }}-sell_stop_amount" data-total-value="{{ $prefix }}-sell_stop_max_exchange" data-total-change="{{ $prefix }}-sell_stop_max_percent" readonly>
                        </div>

                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-sell_stop_min_value" class="form-label">{{ __('wallet-update-sell-stop.sell_stop_min_value') }}</label>
                            <input type="number" name="sell_stop_min_value" step="0.000000001" class="form-control" id="{{ $prefix }}-sell_stop_min_value" value="@numberString($row->sell_stop_min_value)" data-total data-total-amount="{{ $prefix }}-sell_stop_amount" data-total-value="{{ $prefix }}-sell_stop_min_exchange" data-total-change="{{ $prefix }}-sell_stop_min_percent" readonly>
                        </div>
                    </div>

                    <div class="lg:flex">
                        <div class="flex-1 p-1 pt-2">
                            <div class="form-check">
                                <input type="checkbox" name="sell_stop" value="1" class="form-check-switch" id="{{ $prefix }}-sell_stop" {{ $row->sell_stop ? 'checked' : '' }}>
                                <label for="{{ $prefix }}-sell_stop" class="form-check-label">{{ __('wallet-update-sell-stop.sell_stop') }}</label>
                            </div>
                        </div>

                        <div class="flex-1 p-1 pt-2">
                            <div class="form-check">
                                <input type="checkbox" name="sell_stop_max_at" value="1" class="form-check-switch" id="{{ $prefix }}-sell_stop_max_at" {{ $row->sell_stop_max_at ? 'checked' : '' }}>
                                <label for="{{ $prefix }}-sell_stop_max_at" class="form-check-label">{{ __('wallet-update-sell-stop.sell_stop_max_at') }}</label>
                            </div>
                        </div>

                        <div class="flex-1 p-1 pt-2">
                            <div class="form-check">
                                <input type="checkbox" name="sell_stop_min_at" value="1" class="form-check-switch" id="{{ $prefix }}-sell_stop_min_at" {{ $row->sell_stop_min_at ? 'checked' : '' }}>
                                <label for="{{ $prefix }}-sell_stop_min_at" class="form-check-label">{{ __('wallet-update-sell-stop.sell_stop_min_at') }}</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer text-right">
                    <button type="button" data-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">{{ __('wallet-update-sell-stop.cancel') }}</button>
                    <button type="submit" class="btn btn-primary w-20">{{ __('wallet-update-sell-stop.send') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
