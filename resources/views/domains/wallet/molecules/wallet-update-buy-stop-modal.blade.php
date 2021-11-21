@php ($prefix = 'wallet-update-buy-stop-modal-'.$row->id)

<div id="{{ $prefix }}" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('wallet.update.buy-stop', $row->id) }}" method="POST">
                <input type="hidden" name="_action" value="updateBuyStop" />

                <div class="modal-header">
                    <h2 class="font-medium text-base mr-auto">{{ __('wallet-update-buy-stop.title') }}</h2>
                </div>

                <div class="modal-body">
                    <div class="lg:flex">
                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-buy_stop_amount" class="form-label">{{ __('wallet-update-buy-stop.buy_stop_amount') }}</label>
                            <input type="number" name="buy_stop_amount" step="0.000000001" class="form-control" id="{{ $prefix }}-buy_stop_amount" value="@numberString($row->buy_stop_amount)">
                        </div>

                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-amount" class="form-label">{{ __('wallet-update-buy-stop.amount') }}</label>
                            <input type="number" name="amount" step="0.000000001" class="form-control" id="{{ $prefix }}-amount" value="@numberString($row->amount)" readonly>
                        </div>

                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-buy_exchange" class="form-label">{{ __('wallet-update-buy-stop.buy_exchange') }}</label>
                            <input type="number" name="buy_exchange" step="0.000000001" class="form-control" id="{{ $prefix }}-buy_exchange" value="@numberString($row->buy_exchange)" readonly>
                        </div>
                    </div>

                    <div class="lg:flex">
                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-buy_stop_min" class="form-label">{{ __('wallet-update-buy-stop.buy_stop_min') }}</label>
                            <input type="number" name="buy_stop_min" step="0.000000001" class="form-control" id="{{ $prefix }}-buy_stop_min" value="@numberString($row->buy_stop_min)" data-value-to-percent="{{ $prefix }}-buy_stop_min_percent" data-value-to-percent-reference="{{ $prefix }}-buy_exchange">
                        </div>

                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-buy_stop_min_percent" class="form-label">{{ __('wallet-update-buy-stop.buy_stop_min_percent') }}</label>
                            <input type="number" name="buy_stop_min_percent" step="0.0001" class="form-control" id="{{ $prefix }}-buy_stop_min_percent" value="@value($row->buy_stop_min_percent, 2)" data-percent-to-value="{{ $prefix }}-buy_stop_min" data-percent-to-value-reference="{{ $prefix }}-buy_exchange" data-percent-to-value-operation="substract">
                        </div>

                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-buy_stop_min_value" class="form-label">{{ __('wallet-update-buy-stop.buy_stop_min_value') }}</label>
                            <input type="number" name="buy_stop_min_value" step="0.000000001" class="form-control" id="{{ $prefix }}-buy_stop_min_value" value="@numberString($row->buy_stop_min_value)" data-total data-total-amount="{{ $prefix }}-buy_stop_amount" data-total-value="{{ $prefix }}-buy_stop_min" data-total-change="{{ $prefix }}-buy_stop_min_percent" readonly>
                        </div>
                    </div>

                    <div class="lg:flex">
                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-buy_stop_max" class="form-label">{{ __('wallet-update-buy-stop.buy_stop_max') }}</label>
                            <input type="number" name="buy_stop_max" step="0.000000001" class="form-control" id="{{ $prefix }}-buy_stop_max" value="@numberString($row->buy_stop_max)" data-value-to-percent="{{ $prefix }}-buy_stop_max_percent" data-value-to-percent-reference="{{ $prefix }}-buy_stop_min">
                        </div>

                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-buy_stop_max_percent" class="form-label">{{ __('wallet-update-buy-stop.buy_stop_max_percent') }}</label>
                            <input type="number" name="buy_stop_max_percent" step="0.0001" class="form-control" id="{{ $prefix }}-buy_stop_max_percent" value="@value($row->buy_stop_max_percent, 2)" data-percent-to-value="{{ $prefix }}-buy_stop_max" data-percent-to-value-reference="{{ $prefix }}-buy_stop_min">
                        </div>

                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-buy_stop_max_value" class="form-label">{{ __('wallet-update-buy-stop.buy_stop_max_value') }}</label>
                            <input type="number" name="buy_stop_max_value" step="0.000000001" class="form-control" id="{{ $prefix }}-buy_stop_max_value" value="@numberString($row->buy_stop_max_value)" data-total data-total-amount="{{ $prefix }}-buy_stop_amount" data-total-value="{{ $prefix }}-buy_stop_max" data-total-change="{{ $prefix }}-buy_stop_max_percent" readonly>
                        </div>
                    </div>

                    <div class="lg:flex">
                        <div class="flex-1 p-1 pt-2">
                            <div class="form-check">
                                <input type="checkbox" name="buy_stop" value="1" class="form-check-switch" id="{{ $prefix }}-buy_stop" {{ $row->buy_stop ? 'checked' : '' }}>
                                <label for="{{ $prefix }}-buy_stop" class="form-check-label">{{ __('wallet-update-buy-stop.buy_stop') }}</label>
                            </div>
                        </div>

                        <div class="flex-1 p-1 pt-2">
                            <div class="form-check">
                                <input type="checkbox" name="buy_stop_min_at" value="1" class="form-check-switch" id="{{ $prefix }}-buy_stop_min_at" {{ $row->buy_stop_min_at ? 'checked' : '' }}>
                                <label for="{{ $prefix }}-buy_stop_min_at" class="form-check-label">{{ __('wallet-update-buy-stop.buy_stop_min_at') }}</label>
                            </div>
                        </div>

                        <div class="flex-1 p-1 pt-2">
                            <div class="form-check">
                                <input type="checkbox" name="buy_stop_max_at" value="1" class="form-check-switch" id="{{ $prefix }}-buy_stop_max_at" {{ $row->buy_stop_max_at ? 'checked' : '' }}>
                                <label for="{{ $prefix }}-buy_stop_max_at" class="form-check-label">{{ __('wallet-update-buy-stop.buy_stop_max_at') }}</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer text-right">
                    <button type="button" data-dismiss="modal" class="btn btn-outline-secondary w-20 mr-1">{{ __('wallet-update-buy-stop.cancel') }}</button>
                    <button type="submit" class="btn btn-primary w-20">{{ __('wallet-update-buy-stop.send') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>