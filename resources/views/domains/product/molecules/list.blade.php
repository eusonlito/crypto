<div class="overflow-auto md:overflow-visible header-sticky">
    <table id="product-list-table" class="table table-report sm:mt-2 font-medium" data-table-sort>
        <thead>
            <tr class="text-right">
                <th class="text-center">#</th>
                <th class="text-left">{{ __('product-index.code') }}</th>
                <th class="text-left">{{ __('product-index.name') }}</th>
                <th>{{ __('product-index.platform') }}</th>
                <th class="text-center">{{ __('product-index.tracking') }}</th>
                <th class="text-center">{{ __('product-index.enabled') }}</th>
                <th class="text-center">{{ __('product-index.favorite') }}</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($list as $i => $row)

            @php ($link = route('exchange.detail', $row->id))

            <tr class="text-right">
                <td class="text-center"><a href="{{ $link }}" class="block">{{ $i + 1 }}</a></td>
                <td class="text-left"><a href="{{ $link }}" class="block">{{ $row->acronym }}</a></td>
                <td class="text-left whitespace-nowrap whitespace-nowrap"><a href="{{ $link }}" class="block">{{ $row->name }}</a></td>
                <td><a href="{{ $row->platform->url.$row->code }}" rel="nofollow noopener noreferrer" target="_blank">{{ $row->platform->name }}</a></td>
                <td><a href="{{ route('product.update.boolean', [$row->id, 'tracking']) }}" data-link-boolean="tracking" class="block text-center">@status($row->tracking)</a></td>
                <td><a href="{{ route('product.update.boolean', [$row->id, 'enabled']) }}" data-link-boolean="enabled" class="block text-center">@status($row->enabled)</a></td>
                <td class="text-center">
                    <span class="hidden">{{ $row->userPivot ? '1' : '0' }}</span>

                    <a href="{{ route('product.favorite', ['id' => $row->id]) }}" data-product-favorite>
                        @icon('star', $row->userPivot ? 'is-favorite' : '')
                    </a>
                </td>
            </tr>

            @endforeach
        </tbody>
    </table>
</div>
