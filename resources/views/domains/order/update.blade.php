@extends ('layouts.in')

@section ('body')

<div class="box p-5">
    <form method="get">
        <x-select name="wallet_id" value="id" :text="['title']" :options="$wallets->toArray()" :label="__('order-update.wallet')" :selected="$REQUEST->input('wallet_id')" data-change-submit required></x-select>
    </form>
</div>

<form method="post">
    <input type="hidden" name="_action" value="update" />
    <input type="hidden" name="wallet_id" value="{{ $wallet->id }}" />

    <div class="box p-5 mt-5">
        <div class="lg:flex">
            <div class="flex-auto p-1 mt-1">
                <label for="order-created_at" class="form-label">{{ __('order-update.created_at') }}</label>
                <input type="text" name="created_at" class="form-control form-control-lg" id="order-created_at" value="{{ $REQUEST->input('created_at') }}" required>
            </div>

            <div class="flex-auto p-1 mt-1">
                <label for="order-amount" class="form-label">{{ __('order-update.amount') }}</label>
                <input type="number" name="amount" step="0.000000001" class="form-control form-control-lg" id="order-amount" value="{{ $REQUEST->input('amount') }}" required>
            </div>

            <div class="flex-auto p-1 mt-1">
                <label for="order-price" class="form-label">{{ __('order-update.price') }}</label>
                <input type="number" name="price" step="0.000000001" class="form-control form-control-lg" id="order-price" value="{{ $REQUEST->input('price') }}" required>
            </div>

            <div class="flex-auto p-1 mt-1">
                <label for="order-fee" class="form-label">{{ __('order-update.fee') }}</label>
                <input type="number" name="fee" step="0.000000001" class="form-control form-control-lg" id="order-fee" value="{{ $REQUEST->input('fee') }}">
            </div>

            <div class="flex-auto p-1 mt-1">
                <x-select name="type" :options="$types" :placeholder="__('order-update.type-placeholder')" :label="__('order-update.type')" :selected="$REQUEST->input('type')" value-only required></x-select>
            </div>

            <div class="flex-auto p-1 mt-1">
                <x-select name="side" :options="$sides" :placeholder="__('order-update.side-placeholder')" :label="__('order-update.side')" :selected="$REQUEST->input('side')" value-only required></x-select>
            </div>
        </div>
    </div>

    <div class="box p-5 mt-5">
        <div class="text-right">
            <button type="submit" class="btn btn-primary">{{ __('order-update.save') }}</button>
        </div>
    </div>
</form>

@stop
