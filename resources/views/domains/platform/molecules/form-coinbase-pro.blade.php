<div class="box p-5">
    <div class="p-2">
        <label for="coinbase-pro-key" class="form-label">{{ __('platform-form.coinbase-pro.key') }}</label>
        <input type="text" name="coinbase-pro[key]" value="{{ $REQUEST->input('coinbase-pro.key') }}" class="form-control form-control-lg" id="coinbase-pro-key" value="">
    </div>

    <div class="p-2">
        <label for="coinbase-pro-secret" class="form-label mt-3">{{ __('platform-form.coinbase-pro.secret') }}</label>
        <input type="text" name="coinbase-pro[secret]" value="{{ $REQUEST->input('coinbase-pro.secret') }}" class="form-control form-control-lg" id="coinbase-pro-secret" value="">
    </div>

    <div class="p-2">
        <label for="coinbase-pro-passphrase" class="form-label mt-3">{{ __('platform-form.coinbase-pro.passphrase') }}</label>
        <input type="text" name="coinbase-pro[passphrase]" value="{{ $REQUEST->input('coinbase-pro.passphrase') }}" class="form-control form-control-lg" id="coinbase-pro-passphrase" value="">
    </div>

    @if ($row->userPivot)

    <div class="form-check mt-3 p-2">
        <input type="checkbox" name="coinbase-pro[delete]" value="1" id="coinbase-pro-delete" class="form-check-input">
        <label class="form-check-label" for="coinbase-pro-delete">{{ __('platform-form.coinbase-pro.delete') }}</label>
    </div>

    @endif
</div>

<a href="https://help.coinbase.com/en/pro/other-topics/api/how-do-i-create-an-api-key-for-coinbase-pro" class="block alert alert-dark-soft show p-5 mt-5 font-medium text-white" target="_blank">{{ __('platform-form.coinbase-pro.faq') }}</a>