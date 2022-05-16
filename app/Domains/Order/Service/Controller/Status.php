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
        $q = Model::byUserId($this->user->id)
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
            ->map(fn ($list) => $this->map($list))
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
        $first = $list->first();

        $buy = $list->where('side', 'buy');
        $sell = $list->where('side', 'sell');

        $buy_count = $buy->count();
        $buy_average = $this->average($buy);

        $sell_count = $sell->count();
        $sell_average = $this->average($sell);

        $sell_pending = $this->sellPending($list);
        $sell_pending_average = $this->sellPendingAverage($sell_pending);

        $dates = $list->pluck('created_at')->sort();

        return (object)[
            'buy' => $buy->values(),
            'buy_prices' => $buy->pluck('price'),
            'buy_value' => $buy->sum('value'),
            'buy_count' => $buy_count,
            'buy_average' => $buy_average,
            'sell' => $sell->values(),
            'sell_prices' => $sell->pluck('price'),
            'sell_value' => $sell->sum('value'),
            'sell_count' => $sell_count,
            'sell_average' => $sell_average,
            'sell_pending' => $sell_pending,
            'sell_pending_average' => $sell_pending_average,
            'balance' => $this->balance($list),
            'date_first' => $dates->first(),
            'date_last' => $dates->last(),
            'platform' => $first->platform,
            'product' => $first->product,
            'wallet' => $first->wallet,
        ];
    }

    /**
     * @param \Illuminate\Support\Collection $list
     *
     * @return array
     */
    protected function sellPending(Collection $list): array
    {
        $buys = [];

        foreach ($list as $row) {
            $buys = $this->sellPendingRow($row, $buys);
        }

        return array_values($buys);
    }

    /**
     * @param \App\Domains\Order\Model\Order $row
     * @param array $buys
     *
     * @return array
     */
    protected function sellPendingRow(Model $row, array $buys): array
    {
        if (empty($buys) && ($row->side === 'sell')) {
            return $buys;
        }

        if ($row->side === 'buy') {
            $buys[] = ['amount' => $row->amount, 'price' => $row->price];

            return $buys;
        }

        $amount = $row->amount;

        foreach ($buys as $index => $each) {
            if ($each['amount'] > $amount) {
                $buys[$index]['amount'] -= $amount;
                break;
            }

            $amount -= $each['amount'];

            unset($buys[$index]);

            if ($amount <= 0) {
                break;
            }
        }

        return $buys;
    }

    /**
     * @param array $buys
     *
     * @return float
     */
    protected function sellPendingAverage(array $buys): float
    {
        $total = array_sum(array_map(static fn ($value) => $value['amount'] * $value['price'], $buys));
        $price = array_sum(array_column($buys, 'amount'));

        return ($total && $price) ? ($total / $price) : 0;
    }

    /**
     * @param \Illuminate\Support\Collection $list
     *
     * @return float
     */
    protected function balance(Collection $list): float
    {
        $buy = $list->where('side', 'buy')->sum('value');
        $sell = $list->where('side', 'sell')->sum('value');

        return ($sell - $buy) + ($list->first()->wallet->current_value ?? 0);
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
