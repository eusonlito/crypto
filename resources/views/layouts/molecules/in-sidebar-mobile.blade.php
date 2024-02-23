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
            <a href="javascript:;" class="menu {{ (strpos($ROUTE, 'wallet.') === 0) ? 'menu--active' : '' }}">
                <div class="menu__icon">@icon('book-open')</div>
                <div class="menu__title">
                    {{ __('in-sidebar.wallets') }} <div class="menu__sub-icon">@icon('chevron-down')</div>
                </div>
            </a>

            <ul class="{{ (strpos($ROUTE, 'wallet.') === 0) ? 'menu__sub-open' : '' }}">
                <li>
                    <a href="{{ route('wallet.index') }}" class="menu {{ (strpos($ROUTE, 'wallet.index') === 0) ? 'menu--active' : '' }}">
                        <div class="menu__icon">@icon('list') </div>
                        <div class="menu__title">{{ __('in-sidebar.wallets-list') }}</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('wallet.simulator') }}" class="menu {{ (strpos($ROUTE, 'wallet.simulator') === 0) ? 'menu--active' : '' }}">
                        <div class="menu__icon">@icon('activity') </div>
                        <div class="menu__title">{{ __('in-sidebar.wallets-simulator') }}</div>
                    </a>
                </li>
            </ul>
        </li>

        <li>
            <a href="javascript:;" class="menu {{ (strpos($ROUTE, 'order.') === 0) ? 'menu--active' : '' }}">
                <div class="menu__icon">@icon('shuffle')</div>
                <div class="menu__title">
                    {{ __('in-sidebar.orders') }} <div class="menu__sub-icon">@icon('chevron-down')</div>
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
                        <div class="menu__icon">@icon('bar-chart-2') </div>
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
                    {{ __('in-sidebar.future') }} <div class="menu__sub-icon">@icon('chevron-down')</div>
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
            <a href="{{ route('exchange.index') }}" class="menu {{ (strpos($ROUTE, 'exchange.') === 0) ? 'menu--active' : '' }}">
                <div class="menu__icon">@icon('bar-chart')</div>
                <div class="menu__title">{{ __('in-sidebar.exchange') }}</div>
            </a>
        </li>

        <li>
            <a href="{{ route('ticker.index') }}" class="menu {{ in_array($ROUTE, ['ticker.index', 'ticker.create', 'ticker.update']) ? 'menu--active' : '' }}">
                <div class="menu__icon">@icon('bookmark')</div>
                <div class="menu__title">{{ __('in-sidebar.tickers') }}</div>
            </a>
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
            <a href="{{ route('user.update') }}" class="menu {{ ($ROUTE === 'user.update') ? 'menu--active' : '' }}">
                <div class="menu__icon">@icon('user')</div>
                <div class="menu__title">{{ __('in-sidebar.profile') }}</div>
            </a>
        </li>

        <li>
            <a href="{{ route('user.update.platform') }}" class="menu {{ ($ROUTE === 'user.update.platform') ? 'menu--active' : '' }}">
                <div class="menu__icon">@icon('key')</div>
                <div class="menu__title">{{ __('in-sidebar.accounts') }}</div>
            </a>
        </li>

        @if ($AUTH->admin)

        <li>
            <a href="{{ route('user.index') }}" class="menu {{ ($ROUTE === 'user.index') ? 'menu--active' : '' }}">
                <div class="menu__icon">@icon('users')</div>
                <div class="menu__title">{{ __('in-sidebar.users') }}</div>
            </a>
        </li>

        @php ($active = str_starts_with($ROUTE, 'monitor.'))

        <li>
            <a href="javascript:;" class="menu {{ $active ? 'menu--active' : '' }}">
                <div class="menu__icon">@icon('activity')</div>
                <div class="menu__title">
                    {{ __('in-sidebar.monitor') }} <div class="menu__sub-icon {{ $active ? 'transform rotate-180' : '' }}">@icon('chevron-down')</div>
                </div>
            </a>

            <ul class="{{ $active ? 'menu__sub-open' : '' }}">
                <li>
                    <a href="{{ route('monitor.index') }}" class="menu {{ ($ROUTE === 'monitor.index') ? 'menu--active' : '' }}">
                        <div class="menu__icon">@icon('server')</div>
                        <div class="menu__title">{{ __('in-sidebar.monitor-index') }}</div>
                    </a>
                </li>

                <li>
                    <a href="{{ route('monitor.installation') }}" class="menu {{ ($ROUTE === 'monitor.installation') ? 'menu--active' : '' }}">
                        <div class="menu__icon">@icon('check-circle')</div>
                        <div class="menu__title">{{ __('in-sidebar.monitor-installation') }}</div>
                    </a>
                </li>

                <li>
                    <a href="{{ route('monitor.database') }}" class="menu {{ ($ROUTE === 'monitor.database') ? 'menu--active' : '' }}">
                        <div class="menu__icon">@icon('database')</div>
                        <div class="menu__title">{{ __('in-sidebar.monitor-database') }}</div>
                    </a>
                </li>

                <li>
                    <a href="{{ route('monitor.log') }}" class="menu {{ ($ROUTE === 'monitor.log') ? 'menu--active' : '' }}">
                        <div class="menu__icon">@icon('file-text')</div>
                        <div class="menu__title">{{ __('in-sidebar.monitor-log') }}</div>
                    </a>
                </li>
            </ul>
        </li>

        @endif

        <li>
            <a href="{{ route('user.logout') }}" class="menu">
                <div class="menu__icon">@icon('toggle-right')</div>
                <div class="menu__title">{{ __('in-sidebar.logout') }}</div>
            </a>
        </li>
    </ul>
</div>
