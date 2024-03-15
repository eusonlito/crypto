@extends ('layouts.in')

@section ('body')

<form method="post">
    <input type="hidden" name="_action" value="updatePlatform">

    <div class="box flex items-center px-5">
        <div class="nav nav-tabs flex-col sm:flex-row justify-center lg:justify-start mr-auto" role="tablist">
            @foreach ($platforms as $key => $each)

            <a href="javascript:;" data-toggle="tab" data-target="#platform-{{ $each->code }}" class="py-4 sm:mr-8 {{ ($key === 0) ? 'active' : '' }}" role="tab">
                {{ $each->name.' '.($each->userPivot ? '✓' : '') }}
            </a>

            @endforeach
        </div>
    </div>

    <div class="tab-content mt-5">
        @foreach ($platforms as $key => $each)

        <div id="platform-{{ $each->code }}" class="tab-pane {{ ($key === 0) ? 'active' : '' }}" role="tabpanel">
            @include ('domains.platform.molecules.form-'.$each->code, ['row' => $each])
        </div>

        @endforeach
    </div>

    <div class="box mt-5 p-5 text-right">
        <button type="submit" class="btn btn-primary" data-click-one>{{ __('user-update-platform.save') }}</button>
    </div>
</form>

@stop
