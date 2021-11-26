<div class="box p-5">
    <div class="p-2">
        <label for="user-email" class="form-label">{{ __('user-update.email') }}</label>
        <input type="email" name="email" class="form-control form-control-lg" id="user-email" value="{{ $REQUEST->input('email') }}" required>
    </div>

    <div class="p-2">
        <label for="user-password" class="form-label">{{ __('user-update.password') }}</label>

        <div class="input-group">
            <input type="password" name="password" class="form-control form-control-lg" id="user-password">
            <button type="button" class="input-group-text input-group-text-lg" title="{{ __('common.show') }}" data-password-show="#user-password" tabindex="-1">@icon('eye', 'w-5 h-5')</button>
        </div>
    </div>

    <div class="p-2">
        <label for="user-email" class="form-label">{{ __('user-update.investment') }}</label>
        <input type="number" name="investment" class="form-control form-control-lg" id="user-investment" value="{{ $REQUEST->input('investment') }}">
    </div>
</div>