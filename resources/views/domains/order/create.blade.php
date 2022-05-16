@extends ('layouts.in')

@section ('body')

<div class="box p-5">
    <form method="get">
        <x-select name="wallet_id" value="id" :text="['title']" :options="$wallets->toArray()" :placeholder="__('order-create.wallet-placeholder')" :label="__('order-create.wallet')" :selected="$REQUEST->input('wallet_id')" data-change-submit required></x-select>
    </form>
</div>

@if ($wallet)

<form method="post">
    <input type="hidden" name="_action" value="create" />
    <input type="hidden" name="wallet_id" value="{{ $wallet->id }}" />

    <div class="box p-5 mt-5">
        <div class="lg:flex">
            <div class="flex-auto p-1 mt-1">
                <label for="order-created_at" class="form-label">{{ __('order-create.created_at') }}</label>
                <input type="text" name="created_at" class="form-control form-control-lg" id="order-created_at" value="{{ $REQUEST->input('created_at') }}" required>
            </div>

            <div class="flex-auto p-1 mt-1">
                <label for="order-amount" class="form-label">{{ __('order-create.amount') }}</label>
                <input type="number" name="amount" step="0.000000001" class="form-control form-control-lg" id="order-amount" value="{{ $REQUEST->input('amount') }}" required>
            </div>

            <div class="flex-auto p-1 mt-1">
                <label for="order-price" class="form-label">{{ __('order-create.price') }}</label>
                <input type="number" name="price" step="0.000000001" class="form-control form-control-lg" id="order-price" value="{{ $REQUEST->input('price') }}" required>
            </div>

            <div class="flex-auto p-1 mt-1">
                <label for="order-fee" class="form-label">{{ __('order-create.fee') }}</label>
                <input type="number" name="fee" step="0.000000001" class="form-control form-control-lg" id="order-fee" value="{{ $REQUEST->input('fee') }}">
            </div>

            <div class="flex-auto p-1 mt-1">
                <x-select name="type" :options="$types" :placeholder="__('order-create.type-placeholder')" :label="__('order-create.type')" :selected="$REQUEST->input('type')" value-only required></x-select>
            </div>

            <div class="flex-auto p-1 mt-1">
                <x-select name="side" :options="$sides" :placeholder="__('order-create.side-placeholder')" :label="__('order-create.side')" :selected="$REQUEST->input('side')" value-only required></x-select>
            </div>
        </div>
    </div>

    <div class="box p-5 mt-5">
        <div class="text-right">
            <button type="submit" class="btn btn-primary">{{ __('order-create.save') }}</button>
        </div>
    </div>
</form>

@endif

@stop
