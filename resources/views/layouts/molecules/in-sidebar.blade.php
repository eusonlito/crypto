<nav class="side-nav">
    <ul>
        <li>
            <a href="{{ route('dashboard.index') }}" class="side-menu {{ (strpos($ROUTE, 'dashboard.index') === 0) ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">@icon('home')</div>
                <div class="side-menu__title">{{ __('in-sidebar.dashboard') }}</div>
            </a>
        </li>

        <li>
            <a href="{{ route('wallet.index') }}" class="side-menu {{ in_array($ROUTE, ['wallet.index', 'wallet.create', 'wallet.update']) ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">@icon('book-open')</div>
                <div class="side-menu__title">{{ __('in-sidebar.wallets') }}</div>
            </a>
        </li>

        <li>
            <a href="{{ route('ticker.index') }}" class="side-menu {{ in_array($ROUTE, ['ticker.index', 'ticker.create', 'ticker.update']) ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">@icon('bookmark')</div>
                <div class="side-menu__title">{{ __('in-sidebar.tickers') }}</div>
            </a>
        </li>

        <li>
            <a href="javascript:;" class="side-menu {{ (strpos($ROUTE, 'order.') === 0) ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">@icon('shuffle')</div>
                <div class="side-menu__title">
                    {{ __('in-sidebar.orders') }} <div class="side-menu__sub-icon">@icon('chevron-down')</div>
                </div>
            </a>

            <ul class="{{ (strpos($ROUTE, 'order.') === 0) ? 'side-menu__sub-open' : '' }}">
                <li>
                    <a href="{{ route('order.index') }}" class="side-menu {{ (strpos($ROUTE, 'order.index') === 0) ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon">@icon('list') </div>
                        <div class="side-menu__title">{{ __('in-sidebar.orders-list') }}</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('order.status') }}" class="side-menu {{ (strpos($ROUTE, 'order.status') === 0) ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon">@icon('bar-chart-2') </div>
                        <div class="side-menu__title">{{ __('in-sidebar.orders-status') }}</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('order.sync') }}" class="side-menu {{ (strpos($ROUTE, 'order.sync') === 0) ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon">@icon('refresh-cw') </div>
                        <div class="side-menu__title">{{ __('in-sidebar.orders-sync') }}</div>
                    </a>
                </li>
            </ul>
        </li>

        <li>
            <a href="javascript:;" class="side-menu {{ (strpos($ROUTE, 'forecast.') === 0) ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">@icon('trello')</div>
                <div class="side-menu__title">
                    {{ __('in-sidebar.future') }} <div class="side-menu__sub-icon">@icon('chevron-down')</div>
                </div>
            </a>

            <ul class="{{ (strpos($ROUTE, 'forecast.') === 0) ? 'side-menu__sub-open' : '' }}">
                <li>
                    <a href="{{ route('forecast.index') }}" class="side-menu {{ (strpos($ROUTE, 'forecast.index') === 0) ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon">@icon('list') </div>
                        <div class="side-menu__title">{{ __('in-sidebar.future-list') }}</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('forecast.future') }}" class="side-menu {{ (strpos($ROUTE, 'forecast.future') === 0) ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon">@icon('compass') </div>
                        <div class="side-menu__title">{{ __('in-sidebar.future-future') }}</div>
                    </a>
                </li>
            </ul>
        </li>

        <li>
            <a href="{{ route('exchange.index') }}" class="side-menu {{ (strpos($ROUTE, 'exchange.') === 0) ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">@icon('bar-chart')</div>
                <div class="side-menu__title">{{ __('in-sidebar.exchange') }}</div>
            </a>
        </li>

        <li>
            <a href="{{ route('product.index') }}" class="side-menu {{ (strpos($ROUTE, 'product.index') === 0) ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">@icon('box')</div>
                <div class="side-menu__title">{{ __('in-sidebar.products') }}</div>
            </a>
        </li>

        <li>
            <a href="{{ route('dashboard.sync') }}" class="side-menu {{ (strpos($ROUTE, 'dashboard.sync') === 0) ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">@icon('refresh-cw')</div>
                <div class="side-menu__title">{{ __('in-sidebar.sync') }}</div>
            </a>
        </li>

        <li>
            <a href="{{ route('user.update') }}" class="side-menu {{ ($ROUTE === 'user.update') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">@icon('user')</div>
                <div class="side-menu__title">{{ __('in-sidebar.profile') }}</div>
            </a>
        </li>

        <li>
            <a href="{{ route('user.update.platform') }}" class="side-menu {{ ($ROUTE === 'user.update.platform') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">@icon('key')</div>
                <div class="side-menu__title">{{ __('in-sidebar.accounts') }}</div>
            </a>
        </li>

        <li>
            <a href="{{ route('user.logout') }}" class="side-menu">
                <div class="side-menu__icon">@icon('toggle-right')</div>
                <div class="side-menu__title">{{ __('in-sidebar.logout') }}</div>
            </a>
        </li>
    </ul>
</nav>