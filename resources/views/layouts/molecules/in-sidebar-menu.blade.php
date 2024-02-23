<li>
    <a href="{{ route('dashboard.index') }}" class="side-menu {{ (strpos($ROUTE, 'dashboard.index') === 0) ? 'side-menu--active' : '' }}">
        <div class="side-menu__icon">@icon('home')</div>
        <div class="side-menu__title">{{ __('in-sidebar.dashboard') }}</div>
    </a>
</li>

<li>
    <a href="javascript:;" class="side-menu {{ (strpos($ROUTE, 'wallet.') === 0) ? 'side-menu--active' : '' }}">
        <div class="side-menu__icon">@icon('book-open')</div>
        <div class="side-menu__title">
            {{ __('in-sidebar.wallets') }} <div class="side-menu__sub-icon">@icon('chevron-down')</div>
        </div>
    </a>

    <ul class="{{ (strpos($ROUTE, 'wallet.') === 0) ? 'side-menu__sub-open' : '' }}">
        <li>
            <a href="{{ route('wallet.index') }}" class="side-menu {{ (strpos($ROUTE, 'wallet.index') === 0) ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">@icon('list') </div>
                <div class="side-menu__title">{{ __('in-sidebar.wallets-list') }}</div>
            </a>
        </li>
        <li>
            <a href="{{ route('wallet.simulator') }}" class="side-menu {{ (strpos($ROUTE, 'wallet.simulator') === 0) ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">@icon('activity') </div>
                <div class="side-menu__title">{{ __('in-sidebar.wallets-simulator') }}</div>
            </a>
        </li>
    </ul>
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
    <a href="{{ route('ticker.index') }}" class="side-menu {{ in_array($ROUTE, ['ticker.index', 'ticker.create', 'ticker.update']) ? 'side-menu--active' : '' }}">
        <div class="side-menu__icon">@icon('bookmark')</div>
        <div class="side-menu__title">{{ __('in-sidebar.tickers') }}</div>
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

@if ($AUTH->admin)

<li>
    <a href="{{ route('user.index') }}" class="side-menu {{ ($ROUTE === 'user.index') ? 'side-menu--active' : '' }}">
        <div class="side-menu__icon">@icon('users')</div>
        <div class="side-menu__title">{{ __('in-sidebar.users') }}</div>
    </a>
</li>

@php ($active = str_starts_with($ROUTE, 'monitor.'))

<li>
    <a href="javascript:;" class="side-menu {{ $active ? 'side-menu--active' : '' }}">
        <div class="side-menu__icon">@icon('activity')</div>
        <div class="side-menu__title">
            {{ __('in-sidebar.monitor') }} <div class="side-menu__sub-icon {{ $active ? 'transform rotate-180' : '' }}">@icon('chevron-down')</div>
        </div>
    </a>

    <ul class="{{ $active ? 'side-menu__sub-open' : '' }}">
        <li>
            <a href="{{ route('monitor.index') }}" class="side-menu {{ ($ROUTE === 'monitor.index') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">@icon('server')</div>
                <div class="side-menu__title">{{ __('in-sidebar.monitor-index') }}</div>
            </a>
        </li>

        <li>
            <a href="{{ route('monitor.installation') }}" class="side-menu {{ ($ROUTE === 'monitor.installation') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">@icon('check-circle')</div>
                <div class="side-menu__title">{{ __('in-sidebar.monitor-installation') }}</div>
            </a>
        </li>

        <li>
            <a href="{{ route('monitor.database') }}" class="side-menu {{ ($ROUTE === 'monitor.database') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">@icon('database')</div>
                <div class="side-menu__title">{{ __('in-sidebar.monitor-database') }}</div>
            </a>
        </li>

        <li>
            <a href="{{ route('monitor.log') }}" class="side-menu {{ ($ROUTE === 'monitor.log') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon">@icon('file-text')</div>
                <div class="side-menu__title">{{ __('in-sidebar.monitor-log') }}</div>
            </a>
        </li>
    </ul>
</li>

@endif

<li>
    <a href="{{ route('user.logout') }}" class="side-menu">
        <div class="side-menu__icon">@icon('toggle-right')</div>
        <div class="side-menu__title">{{ __('in-sidebar.logout') }}</div>
    </a>
</li>
