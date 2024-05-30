<form id="wallet-form" method="post" data-change-event-change data-wallet>
    <input type="hidden" name="_action" value="update" />

    <div class="box p-5">
        <div class="grid grid-cols-12 gap-2">
            <div class="col-span-12 mb-2 xl:col-span-4">
                <x-select name="platform_id" value="id" :text="['name']" :options="$platforms->toArray()" :label="__('wallet-update.platform')" :selected="$row->platform_id" readonly></x-select>
            </div>

            <div class="col-span-12 mb-2 xl:col-span-4">
                <x-select name="product_id" value="id" :text="['acronym', 'name']" :options="$products->toArray()" :label="__('wallet-update.product')" :selected="$row->product_id"></x-select>
            </div>

            <div class="col-span-12 xl:col-span-4">
                <div class="flex">
                    <div class="flex-1 mb-2 px-2">
                        <label class="form-label hidden xl:block">&nbsp;</label>

                        <a href="?_action=updateSync" class="btn form-select-lg block" title="{{ __('wallet-update.sync') }}">
                            @icon('refresh-cw')
                        </a>
                    </div>

                    <div class="flex-1 mb-2 px-2">
                        <label class="form-label hidden xl:block">&nbsp;</label>

                        <a href="{{ route('wallet.simulator', ['id' => $row->id]) }}" class="btn form-select-lg block truncate" title="{{ __('wallet-update.simulator') }}">
                            @icon('activity')
                        </a>
                    </div>

                    <div class="flex-1 mb-2 px-2">
                        <label class="form-label hidden xl:block">&nbsp;</label>

                        <a href="{{ $row->platform->url.$row->product->code }}" rel="nofollow noopener noreferrer" target="_blank" class="btn form-select-lg block truncate">
                            {{ $row->platform->name }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-span-12 mb-2 lg:col-span-4">
                <label for="wallet-address" class="form-label">{{ __('wallet-update.address') }}</label>
                <input type="text" name="address" class="form-control form-control-lg" id="wallet-address" value="{{ $row->address }}" />
            </div>

            <div class="col-span-12 mb-2 lg:col-span-4">
                <label for="wallet-name" class="form-label">{{ __('wallet-update.name') }}</label>
                <input type="text" name="name" class="form-control form-control-lg" id="wallet-name" value="{{ $row->name }}" />
            </div>

            <div class="col-span-12 mb-2 lg:col-span-4">
                <label for="wallet-order" class="form-label">{{ __('wallet-update.order') }}</label>
                <input type="number" step="any" name="order" class="form-control form-control-lg" id="wallet-order" value="{{ $row->order }}">
            </div>

            <div class="col-span-12 mb-2 lg:col-span-2">
                <label for="wallet-amount" class="form-label">{{ __('wallet-update.amount') }}</label>
                <input type="number" step="any" name="amount" class="form-control form-control-lg" id="wallet-amount" value="@numberString($REQUEST->input('amount'))" />
            </div>

            <div class="col-span-12 mb-2 lg:col-span-3">
                <label for="wallet-buy_exchange" class="form-label">{{ __('wallet-update.buy_exchange') }}</label>

                <div class="input-group">
                    <input type="number" step="any" name="buy_exchange" class="form-control form-control-lg" id="wallet-buy_exchange" value="@numberString($REQUEST->input('buy_exchange'))" {{ $row->crypto ? 'required' : 'readonly' }}>
                    <button type="button" class="input-group-text input-group-text-lg" tabindex="-1" title="{{ __('wallet-update.exchange-from-order-status') }}" data-wallet-order-status data-wallet-order-status-link="{{ route('order.status', ['wallet_id' => $row->id]) }}" data-wallet-order-status-target="#wallet-buy_exchange">@icon('shuffle', 'w-5 h-5')</button>
                </div>
            </div>

            <div class="col-span-12 mb-2 lg:col-span-3">
                <label for="wallet-current_exchange" class="form-label">{{ __('wallet-update.current_exchange') }}</label>
                <input type="number" step="any" name="current_exchange" class="form-control form-control-lg" id="wallet-current_exchange" value="@numberString($row->current_exchange)" readonly>
            </div>

            <div class="col-span-12 mb-2 lg:col-span-2">
                <label for="wallet-buy_value" class="form-label">{{ __('wallet-update.buy_value') }}</label>
                <input type="number" step="any" name="buy_value" class="form-control form-control-lg" id="wallet-buy_value" value="@numberString($row->buy_value)" readonly>
            </div>

            <div class="col-span-12 mb-2 lg:col-span-2">
                <label for="wallet-current_value" class="form-label">{{ __('wallet-update.current_value') }}</label>
                <input type="number" step="any" name="current_value" class="form-control form-control-lg" id="wallet-current_value" value="@numberString($row->current_value)" readonly>
            </div>

            <div class="col-span-12 mb-2 lg:col-span-2">
                <div class="form-check">
                    <input type="checkbox" name="enabled" value="1" class="form-check-switch" id="wallet-enabled" {{ $REQUEST->input('enabled') ? 'checked' : '' }}>
                    <label for="wallet-enabled" class="form-check-label">{{ __('wallet-update.enabled') }}</label>
                </div>
            </div>

            <div class="col-span-12 mb-2 lg:col-span-2">
                <div class="form-check">
                    <input type="checkbox" name="visible" value="1" class="form-check-switch" id="wallet-visible" {{ $REQUEST->input('visible') ? 'checked' : '' }}>
                    <label for="wallet-visible" class="form-check-label">{{ __('wallet-update.visible') }}</label>
                </div>
            </div>
        </div>
    </div>

    @includeWhen ($row->crypto, 'domains.wallet.molecules.create-update-crypto')

    <div class="box p-5 mt-5">
        <div class="text-right">
            <a href="javascript:;" data-toggle="modal" data-target="#delete-modal" class="btn btn-outline-danger mr-5">{{ __('wallet-update.delete.button') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('wallet-update.save') }}</button>
        </div>
    </div>
</form>
