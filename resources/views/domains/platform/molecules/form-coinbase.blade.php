<div class="box p-5">
    <div class="p-2">
        <label for="coinbase-key" class="form-label">{{ __('platform-form.coinbase.key') }}</label>
        <input type="text" name="coinbase[key]" value="{{ $REQUEST->input('binance.key') }}" class="form-control form-control-lg" id="coinbase-key" value="">
    </div>

    <div class="p-2">
        <label for="coinbase-secret" class="form-label mt-3">{{ __('platform-form.coinbase.secret') }}</label>
        <input type="text" name="coinbase[secret]" value="{{ $REQUEST->input('binance.secret') }}" class="form-control form-control-lg" id="coinbase-secret" value="">
    </div>

    @if ($row->userPivot)

    <div class="form-check mt-3 p-2">
        <input type="checkbox" name="coinbase[delete]" value="1" id="coinbase-delete" class="form-check-input">
        <label class="form-check-label" for="coinbase-delete">{{ __('platform-form.coinbase.delete') }}</label>
    </div>

    @endif
</div>

<a href="https://help.coinbase.com/en/exchange/managing-my-account/how-to-create-an-api-key" class="block alert alert-dark-soft show p-5 mt-5 font-medium text-white" target="_blank">{{ __('platform-form.coinbase.faq') }}</a>