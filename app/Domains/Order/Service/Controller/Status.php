<?php declare(strict_types=1);

namespace App\Domains\Order\Service\Controller;

use DateTime;
use stdClass;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Domains\Order\Model\Order as Model;
use App\Domains\User\Model\User as UserModel;

class Status
{
    /**
     * @var \App\Domains\User\Model\User
     */
    protected UserModel $user;

    /**
     * @var \Illuminate\Http\Request
     */
    protected Request $request;

    /**
     * @return self
     */
    public static function new(): self
    {
        return new static(...func_get_args());
    }

    /**
     * @param \App\Domains\User\Model\User $user
     * @param \Illuminate\Http\Request $request
     *
     * @return self
     */
    public function __construct(UserModel $user, Request $request)
    {
        $this->user = $user;
        $this->request = $request;
    }

    /**
     * @return array
     */
    public function get(): array
    {
        return [
            'list' => ($list = $this->list()),
            'total' => $this->total($list),
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function list(): Collection
    {
        $q = Model::query()
            ->byUserId($this->user->id)
            ->withRelations()
            ->whereWalletId()
            ->withWallet()
            ->whereFilled()
            ->orderByDate();

        if ($filter = (int)$this->request->input('platform_id')) {
            $q->byPlatformId($filter);
        }

        if ($filter = (int)$this->request->input('product_id')) {
            $q->byProductId($filter);
        }

        if ($filter = (int)$this->request->input('wallet_id')) {
            $q->byProductWalletId($filter);
        }

        if ($filter = helper()->dateToDate($this->request->input('date_start', ''))) {
            $q->byCreatedAtStart($filter);
        }

        if ($filter = helper()->dateToDate($this->request->input('date_end', ''))) {
            $q->byCreatedAtEnd($filter);
        }

        return $q->get()
            ->groupBy('product_id')
            ->map($this->map(...))
            ->filter()
            ->sortByDesc('balance')
            ->values();
    }

    /**
     * @param \Illuminate\Support\Collection $list
     *
     * @return ?\stdClass
     */
    protected function map(Collection $list): ?stdClass
    {
        while ($list->isNotEmpty() && ($list->first()->side === 'sell')) {
            $list = $list->slice(1);
        }

        if ($list->isEmpty()) {
            return null;
        }

        while ($list->isNotEmpty() && ($list->last()->side === 'buy')) {
            $list = $list->slice(0, -1);
        }

        if ($list->isEmpty()) {
            return null;
        }

        $first = $list->first();
        $last = $list->last();

        $buy = $list->where('side', 'buy');
        $sell = $list->where('side', 'sell');

        $buy_value = $buy->sum('value');
        $sell_value = $sell->sum('value');

        $balance = $sell_value - $buy_value;
        $investment = $sell_value / $sell->count();
        $balance_percent = ($balance / $investment) * 100;
        $days = $this->mapDays($first, $last);
        $balance_percent_daily = $balance_percent / $days;

        return (object)[
            'buy' => $buy->values(),
            'buy_prices' => $buy->pluck('price'),
            'buy_value' => $buy_value,
            'buy_count' => $buy->count(),
            'buy_amount' => $buy->sum('amount'),
            'buy_average' => $this->average($buy),
            'sell' => $sell->values(),
            'sell_prices' => $sell->pluck('price'),
            'sell_value' => $sell_value,
            'sell_count' => $sell->count(),
            'sell_amount' => $sell->sum('amount'),
            'sell_average' => $this->average($sell),
            'wallet_amount' => $first->wallet?->amount,
            'wallet_value' => $first->wallet?->current_value,
            'investment' => $investment,
            'days' => $days,
            'balance' => $balance,
            'balance_percent' => $balance_percent,
            'balance_percent_daily' => $balance_percent_daily,
            'date_first' => $first->created_at,
            'date_last' => $last->created_at,
            'platform' => $first->platform,
            'product' => $first->product,
            'wallet' => $first->wallet,
        ];
    }

    /**
     * @param \App\Domains\Order\Model\Order $first
     * @param \App\Domains\Order\Model\Order $last
     *
     * @return int
     */
    protected function mapDays(Model $first, Model $last): int
    {
        return date_diff(new DateTime($first->created_at), new DateTime($last->created_at), true)->days ?: 1;
    }

    /**
     * @param \Illuminate\Support\Collection $list
     *
     * @return float
     */
    protected function average(Collection $list): float
    {
        return $list->sum('value') / ($list->sum('amount') ?: 1);
    }

    /**
     * @param \Illuminate\Support\Collection $list
     *
     * @return array
     */
    protected function total(Collection $list): array
    {
        $investment = $list->sum('investment');
        $balance = $list->sum('balance');
        $balance_percent = $balance / $investment * 100;
        $balance_percent_daily = $balance_percent / $list->max('days');

        return [
            'buy_count' => $list->sum('buy_count'),
            'sell_count' => $list->sum('sell_count'),
            'buy_value' => $list->sum('buy_value'),
            'sell_value' => $list->sum('sell_value'),
            'wallet_value' => $list->sum('wallet_value'),
            'investment' => $investment,
            'balance' => $balance,
            'balance_percent' => $balance_percent,
            'balance_percent_daily' => $balance_percent_daily,
        ];
    }
}
