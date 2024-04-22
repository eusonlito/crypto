<div class="box p-5 mt-5">
    <input type="search" class="form-control form-control-lg" placeholder="{{ __('common.filter') }}" data-table-search="#order-list-table" />
</div>

@include ('domains.order.molecules.list', ['list' => $orders])
