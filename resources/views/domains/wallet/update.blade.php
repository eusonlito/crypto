@extends ('layouts.in')

@section ('body')

<div class="box flex items-center px-5">
    <div class="nav nav-tabs flex-col sm:flex-row justify-center lg:justify-start mr-auto" role="tablist">
        <a href="javascript:;" data-toggle="tab" data-target="#update-data" class="py-4 sm:mr-8 active" role="tab">{{ $row->name }}</a>

        @if ($orders->isNotEmpty())
        <a href="javascript:;" data-toggle="tab" data-target="#update-order" class="py-4 sm:mr-8" role="tab">{{ __('wallet-update.orders') }}</a>
        @endif

        <a href="{{ route('wallet.update.history', $row->id)}}" class="py-4 sm:mr-8" role="tab">{{ __('wallet-update.history') }}</a>
    </div>
</div>

<div class="tab-content mt-5">
    <div id="update-data" class="tab-pane active" role="tabpanel">
        @include ('domains.wallet.molecules.update-data')
    </div>

    @if ($orders->isNotEmpty())

    <div id="update-order" class="tab-pane" role="tabpanel">
        @include ('domains.wallet.molecules.update-order')
    </div>

    @endif
</div>

@stop
