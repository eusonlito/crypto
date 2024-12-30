@php ($prefix = 'wallet-update-buy-stop-modal-'.$row->id)

<div id="{{ $prefix }}" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" id="{{ $prefix }}-dialog">
        <div class="modal-content">
            <form action="{{ route('wallet.update.buy-stop', $row->id) }}" method="POST" data-change-event-change data-wallet>
                <input type="hidden" name="_action" value="updateBuyStop" />

                <div class="modal-header" data-draggable="#{{ $prefix }}-dialog">
                    <h2 class="font-medium text-base mr-auto">{{ $row->name }} - {{ __('wallet-update-buy-stop.title') }}</h2>
                </div>

                <div class="modal-body">
                    <div class="lg:flex">
                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-amount" class="form-label">{{ __('wallet-update-buy-stop.amount') }}</label>
                            <input type="number" step="any" name="amount" class="form-control" id="{{ $prefix }}-amount" value="@numberString($row->amount)" readonly>
                        </div>

                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-buy_exchange" class="form-label">{{ __('wallet-update-buy-stop.buy_exchange') }}</label>
                            <input type="number" step="any" name="buy_exchange" class="form-control" id="{{ $prefix }}-buy_exchange" value="@numberString($row->buy_exchange)" readonly>
                        </div>

                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-current_exchange" class="form-label">{{ __('wallet-update-buy-stop.current_exchange') }}</label>
                            <input type="number" step="any" name="current_exchange" class="form-control" id="{{ $prefix }}-current_exchange" value="@numberString($row->current_exchange)" readonly>
                        </div>
                    </div>

                    <div class="lg:flex">
                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-buy_value" class="form-label">{{ __('wallet-update-buy-stop.buy_value') }}</label>
                            <input type="number" step="any" name="buy_value" class="form-control" id="{{ $prefix }}-buy_value" value="@numberString($row->buy_value)" readonly>
                        </div>

                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-current_value" class="form-label">{{ __('wallet-update-buy-stop.current_value') }}</label>
                            <input type="number" step="any" name="current_value" class="form-control" id="{{ $prefix }}-current_value" value="@numberString($row->current_value)" readonly>
                        </div>
                    </div>

                    <div class="lg:flex">
                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-buy_stop_reference" class="form-label">{{ __('wallet-update-buy-stop.buy_stop_reference') }}</label>
                            <input type="number" step="any" name="buy_stop_reference" class="form-control" id="{{ $prefix }}-buy_stop_reference" value="@numberString($row->buy_stop_reference)">
                        </div>

                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-buy_stop_amount" class="form-label">{{ __('wallet-update-buy-stop.buy_stop_amount') }}</label>
                            <input type="number" step="any" name="buy_stop_amount" class="form-control" id="{{ $prefix }}-buy_stop_amount" value="@numberString($row->buy_stop_amount)" readonly>
                        </div>
                    </div>

                    <div class="lg:flex">
                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-buy_stop_min_percent" class="form-label">{{ __('wallet-update-buy-stop.buy_stop_min_percent') }}</label>
                            <input type="number" step="any" name="buy_stop_min_percent" class="form-control" id="{{ $prefix }}-buy_stop_min_percent" value="@value($row->buy_stop_min_percent, 2)">
                        </div>

                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-buy_stop_max_percent" class="form-label">{{ __('wallet-update-buy-stop.buy_stop_max_percent') }}</label>
                            <input type="number" step="any" name="buy_stop_max_percent" class="form-control" id="{{ $prefix }}-buy_stop_max_percent" value="@value($row->buy_stop_max_percent, 2)">
                        </div>
                    </div>

                    <div class="lg:flex">
                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-buy_stop_min_exchange" class="form-label">{{ __('wallet-update-buy-stop.buy_stop_min_exchange') }}</label>
                            <input type="number" step="any" name="buy_stop_min_exchange" class="form-control" id="{{ $prefix }}-buy_stop_min_exchange" value="@numberString($row->buy_stop_min_exchange)" readonly>
                        </div>

                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-buy_stop_max_exchange" class="form-label">{{ __('wallet-update-buy-stop.buy_stop_max_exchange') }}</label>
                            <input type="number" step="any" name="buy_stop_max_exchange" class="form-control" id="{{ $prefix }}-buy_stop_max_exchange" value="@numberString($row->buy_stop_max_exchange)" readonly>
                        </div>
                    </div>

                    <div class="lg:flex">
                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-buy_stop_min_value" class="form-label">{{ __('wallet-update-buy-stop.buy_stop_min_value') }}</label>
                            <input type="number" step="any" name="buy_stop_min_value" class="form-control" id="{{ $prefix }}-buy_stop_min_value" value="@numberString($row->buy_stop_min_value)" readonly>
                        </div>

                        <div class="flex-1 p-1">
                            <label for="{{ $prefix }}-buy_stop_max_value" class="form-label">{{ __('wallet-update-buy-stop.buy_stop_max_value') }}</label>
                            <input type="number" step="any" name="buy_stop_max_value" class="form-control" id="{{ $prefix }}-buy_stop_max_value" value="@numberString($row->buy_stop_max_value)">
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
                                <input type="checkbox" name="buy_stop_ai" value="1" class="form-check-switch" id="{{ $prefix }}-buy_stop_ai" {{ $row->buy_stop_ai ? 'checked' : '' }}>
                                <label for="{{ $prefix }}-buy_stop_ai" class="form-check-label">{{ __('wallet-update-buy-stop.buy_stop_ai') }}</label>
                            </div>
                        </div>
                    </div>

                    <div class="lg:flex">
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

                    <div class="lg:flex">
                        <div class="flex-1 p-1 pt-2">
                            <div class="form-check">
                                <input type="checkbox" name="buy_stop_max_follow" value="1" class="form-check-switch" id="{{ $prefix }}-buy_stop_max_follow" {{ $row->buy_stop_max_follow ? 'checked' : '' }}>
                                <label for="{{ $prefix }}-buy_stop_max_follow" class="form-check-label">{{ __('wallet-update-buy-stop.buy_stop_max_follow') }}</label>
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
