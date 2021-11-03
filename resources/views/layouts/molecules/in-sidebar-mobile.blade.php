<div class="mobile-menu md:hidden">
    <div class="mobile-menu-bar">
        <a href="javascript:;" id="mobile-menu-toggler">
            @icon('bar-chart-2', 'w-8 h-8 text-white transform -rotate-90')
        </a>
    </div>

    <ul class="border-t border-theme-21 py-5 hidden">
        <li>
            <a href="{{ route('dashboard.index') }}" class="menu {{ (strpos($ROUTE, 'dashboard.index') === 0) ? 'menu--active' : '' }}">
                <div class="menu__icon">@icon('home')</div>
                <div class="menu__title">{{ __('in-sidebar.dashboard') }}</div>
            </a>
        </li>

        <li>
            <a href="{{ route('exchange.index') }}" class="menu {{ (strpos($ROUTE, 'exchange.') === 0) ? 'menu--active' : '' }}">
                <div class="menu__icon">@icon('bar-chart')</div>
                <div class="menu__title">{{ __('in-sidebar.exchange') }}</div>
            </a>
        </li>

        <li>
            <a href="{{ route('wallet.index') }}" class="menu {{ in_array($ROUTE, ['wallet.index', 'wallet.create', 'wallet.update']) ? 'menu--active' : '' }}">
                <div class="menu__icon">@icon('book-open')</div>
                <div class="menu__title">{{ __('in-sidebar.wallets') }}</div>
            </a>
        </li>

        <li>
            <a href="{{ route('ticker.index') }}" class="menu {{ in_array($ROUTE, ['ticker.index', 'ticker.create', 'ticker.update']) ? 'menu--active' : '' }}">
                <div class="menu__icon">@icon('bookmark')</div>
                <div class="menu__title">{{ __('in-sidebar.tickers') }}</div>
            </a>
        </li>

        <li>
            <a href="javascript:;" class="menu {{ (strpos($ROUTE, 'order.') === 0) ? 'menu--active' : '' }}">
                <div class="menu__icon">@icon('shuffle')</div>
                <div class="menu__title">
                    {{ __('in-sidebar.orders') }} @icon('chevron-down', 'menu__sub-icon')
                </div>
            </a>

            <ul class="{{ (strpos($ROUTE, 'order.') === 0) ? 'menu__sub-open' : '' }}">
                <li>
                    <a href="{{ route('order.index') }}" class="menu {{ (strpos($ROUTE, 'order.index') === 0) ? 'menu--active' : '' }}">
                        <div class="menu__icon">@icon('list') </div>
                        <div class="menu__title">{{ __('in-sidebar.orders-list') }}</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('order.status') }}" class="menu {{ (strpos($ROUTE, 'order.status') === 0) ? 'menu--active' : '' }}">
                        <div class="menu__icon">@icon('compass') </div>
                        <div class="menu__title">{{ __('in-sidebar.orders-status') }}</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('order.sync') }}" class="menu {{ (strpos($ROUTE, 'order.sync') === 0) ? 'menu--active' : '' }}">
                        <div class="menu__icon">@icon('refresh-cw') </div>
                        <div class="menu__title">{{ __('in-sidebar.orders-sync') }}</div>
                    </a>
                </li>
            </ul>
        </li>

        <li>
            <a href="javascript:;" class="menu {{ (strpos($ROUTE, 'forecast.') === 0) ? 'menu--active' : '' }}">
                <div class="menu__icon">@icon('trello')</div>
                <div class="menu__title">
                    {{ __('in-sidebar.future') }} @icon('chevron-down', 'menu__sub-icon')
                </div>
            </a>

            <ul class="{{ (strpos($ROUTE, 'forecast.') === 0) ? 'menu__sub-open' : '' }}">
                <li>
                    <a href="{{ route('forecast.index') }}" class="menu {{ (strpos($ROUTE, 'forecast.index') === 0) ? 'menu--active' : '' }}">
                        <div class="menu__icon">@icon('list') </div>
                        <div class="menu__title">{{ __('in-sidebar.future-list') }}</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('forecast.future') }}" class="menu {{ (strpos($ROUTE, 'forecast.future') === 0) ? 'menu--active' : '' }}">
                        <div class="menu__icon">@icon('compass') </div>
                        <div class="menu__title">{{ __('in-sidebar.future-future') }}</div>
                    </a>
                </li>
            </ul>
        </li>

        <li>
            <a href="{{ route('product.index') }}" class="menu {{ (strpos($ROUTE, 'product.index') === 0) ? 'menu--active' : '' }}">
                <div class="menu__icon">@icon('box')</div>
                <div class="menu__title">{{ __('in-sidebar.products') }}</div>
            </a>
        </li>

        <li>
            <a href="{{ route('dashboard.sync') }}" class="menu {{ (strpos($ROUTE, 'dashboard.sync') === 0) ? 'menu--active' : '' }}">
                <div class="menu__icon">@icon('refresh-cw')</div>
                <div class="menu__title">{{ __('in-sidebar.sync') }}</div>
            </a>
        </li>

        <li>
            <a href="{{ route('user.update') }}" class="menu {{ (strpos($ROUTE, 'user.update') === 0) ? 'menu--active' : '' }}">
                <div class="menu__icon">@icon('user')</div>
                <div class="menu__title">{{ __('in-sidebar.profile') }}</div>
            </a>
        </li>

        <li>
            <a href="{{ route('user.update.platform') }}" class="menu {{ (strpos($ROUTE, 'user.update.platform') === 0) ? 'menu--active' : '' }}">
                <div class="menu__icon">@icon('key')</div>
                <div class="menu__title">{{ __('in-sidebar.accounts') }}</div>
            </a>
        </li>

        <li>
            <a href="{{ route('user.logout') }}" class="menu">
                <div class="menu__icon">@icon('toggle-right')</div>
                <div class="menu__title">{{ __('in-sidebar.logout') }}</div>
            </a>
        </li>
    </ul>
</div>