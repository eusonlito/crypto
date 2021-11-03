@extends ('layouts.in')

@section ('body')

<form method="post">
    <input type="hidden" name="_action" value="list" />

    <div class="sm:flex sm:space-x-4">
        <div class="flex-grow mt-2 sm:mt-0">
            <select name="favorite" class="form-select form-select-lg bg-white" data-change-submit>
                <option value="">{{ __('forecast-future.favorite-all') }}</option>
                <option value="1" {{ ($filters['favorite'] === '1') ? 'selected' : '' }}>{{ __('forecast-future.favorite-yes') }}</option>
            </select>
        </div>

        <div class="flex mt-2 sm:mt-0">
            <button type="submit" class="btn btn-primary" data-click-one>{{ __('forecast-future.send') }}</button>
        </div>
    </div>
</form>

@if ($list)

@include ('domains.forecast.molecules.future')

@else

<div class="box mt-5 p-5 text-center">{{ __('forecast-future.intro') }}</div>

@endif

@stop