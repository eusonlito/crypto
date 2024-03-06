<?php declare(strict_types=1);

namespace App\Domains\Order\Service\Controller;

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
     * @return \Illuminate\Support\Collection
     */
    public function get(): Collection
    {
        $q = Model::query()
            ->byUserId($this->user->id)
            ->withRelations()
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

        $buy = $list->where('side', 'buy');
        $sell = $list->where('side', 'sell');

        $balance = $sell->sum('value') - $buy->sum('value');

        return (object)[
            'buy' => $buy->values(),
            'buy_prices' => $buy->pluck('price'),
            'buy_value' => $buy->sum('value'),
            'buy_count' => $buy->count(),
            'buy_amount' => $buy->sum('amount'),
            'buy_average' => $this->average($buy),
            'sell' => $sell->values(),
            'sell_prices' => $sell->pluck('price'),
            'sell_value' => $sell->sum('value'),
            'sell_count' => $sell->count(),
            'sell_amount' => $sell->sum('amount'),
            'sell_average' => $this->average($sell),
            'wallet_amount' => $first->wallet?->amount,
            'wallet_value' => $first->wallet?->current_value,
            'balance' => $balance,
            'date_first' => $first->created_at,
            'date_last' => $list->last()->created_at,
            'platform' => $first->platform,
            'product' => $first->product,
            'wallet' => $first->wallet,
        ];
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
}
