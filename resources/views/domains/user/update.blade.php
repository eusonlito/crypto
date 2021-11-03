@extends ('layouts.in')

@section ('body')

<form method="post">
    <input type="hidden" name="_action" value="update" />

    <div class="box flex items-center px-5">
        <div class="nav nav-tabs flex-col sm:flex-row justify-center lg:justify-start mr-auto" role="tablist">
            <a href="javascript:;" data-toggle="tab" data-target="#update-data" class="py-4 sm:mr-8 active" role="tab">{{ __('user-update.data') }}</a>
            <a href="javascript:;" data-toggle="tab" data-target="#update-tfa" class="py-4 sm:mr-8" role="tab">{{ __('user-update.tfa') }}</a>
        </div>
    </div>

    <div class="tab-content mt-5">
        <div id="update-data" class="tab-pane active" role="tabpanel">
            @include ('domains.user.molecules.update-data')
        </div>

        <div id="update-tfa" class="tab-pane" role="tabpanel">
            @include ('domains.user.molecules.update-tfa')
        </div>
    </div>

    <div class="box p-5 mt-5">
        <div class="p-2">
            <label for="user-password_current" class="form-label">{{ __('user-update.password_current') }}</label>
            <input type="password" name="password_current" class="form-control form-control-lg" id="user-password_current" required>
        </div>
    </div>

    <div class="box p-5 mt-5">
        <div class="text-right">
            <button type="submit" class="btn btn-primary">{{ __('user-update.save') }}</button>
        </div>
    </div>
</form>

@stop
