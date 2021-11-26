<div class="box p-5">
    <div class="p-2">
        <label for="binance-key" class="form-label">{{ __('platform-form.binance.key') }}</label>
        <input type="password" name="binance[key]" value="{{ $REQUEST->input('binance.key') }}" class="form-control form-control-lg" id="binance-key" value="">
    </div>

    <div class="p-2">
        <label for="binance-secret" class="form-label mt-3">{{ __('platform-form.binance.secret') }}</label>
        <input type="password" name="binance[secret]" value="{{ $REQUEST->input('binance.secret') }}" class="form-control form-control-lg" id="binance-secret" value="">
    </div>

    @if ($row->userPivot)

    <div class="form-check mt-3 p-2">
        <input type="checkbox" name="binance[delete]" value="1" id="binance-delete" class="form-check-input">
        <label class="form-check-label" for="binance-delete">{{ __('platform-form.binance.delete') }}</label>
    </div>

    @endif
</div>

<a href="https://www.binance.com/es/support/faq/360002502072" class="block alert alert-dark-soft show p-5 mt-5 font-medium text-white" target="_blank">{{ __('platform-form.binance.faq') }}</a>
