<div class="box p-5">
    <div class="p-2">
        <label for="kucoin-key" class="form-label">{{ __('platform-form.kucoin.key') }}</label>
        <input type="password" name="kucoin[key]" value="{{ $REQUEST->input('kucoin.key') }}" class="form-control form-control-lg" id="kucoin-key" value="">
    </div>

    <div class="p-2">
        <label for="kucoin-secret" class="form-label mt-3">{{ __('platform-form.kucoin.secret') }}</label>
        <input type="password" name="kucoin[secret]" value="{{ $REQUEST->input('kucoin.secret') }}" class="form-control form-control-lg" id="kucoin-secret" value="">
    </div>

    <div class="p-2">
        <label for="kucoin-passphrase" class="form-label mt-3">{{ __('platform-form.kucoin.passphrase') }}</label>
        <input type="password" name="kucoin[passphrase]" value="{{ $REQUEST->input('kucoin.passphrase') }}" class="form-control form-control-lg" id="kucoin-passphrase" value="">
    </div>

    @if ($row->userPivot)

    <div class="form-check mt-3 p-2">
        <input type="checkbox" name="kucoin[delete]" value="1" id="kucoin-delete" class="form-check-input">
        <label class="form-check-label" for="kucoin-delete">{{ __('platform-form.kucoin.delete') }}</label>
    </div>

    @endif
</div>

<a href="https://support.kucoin.plus/hc/en-us/articles/360015102174-How-to-Create-an-API-" class="block alert alert-dark-soft show p-5 mt-5 font-medium text-white" target="_blank">{{ __('platform-form.kucoin.faq') }}</a>
