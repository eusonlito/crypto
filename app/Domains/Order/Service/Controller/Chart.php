<?php declare(strict_types=1);

namespace App\Domains\Order\Service\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Domains\Order\Model\Order as Model;
use App\Domains\User\Model\User as UserModel;

class Chart
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
            'values' => ($values = $this->values()),
            'labels' => $this->labels($values),
        ];
    }

    /**
     * @return array
     */
    protected function values(): array
    {
        $values = [];

        foreach ($this->list()->groupBy('wallet_id') as $group) {
            $buy = null;

            foreach ($group as $row) {
                if ($row->side === 'buy') {
                    $buy = $row;

                    continue;
                }

                if ($buy === null) {
                    continue;
                }

                $value = round($row->value - ($row->amount * $buy->price), 2);
                $values[$row->wallet->name][date('Y-m-d', strtotime($row->updated_at))] = $value;
            }
        }

        foreach ($values as $group) {
            foreach ($group as $date => $value) {
                $values['total'][$date] ??= 0;
                $values['total'][$date] += $value;
            }
        }

        return $values;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function list(): Collection
    {
        $q = Model::query()
            ->byUserId($this->user->id)
            ->whereWalletId()
            ->withWallet()
            ->whereFilled()
            ->orderByDate();

        if ($filter = (int)$this->request->input('platform_id')) {
            $q->byPlatformId($filter);
        }

        if ($filter = helper()->dateToDate($this->request->input('date_start', ''))) {
            $q->byCreatedAtStart($filter);
        }

        if ($filter = helper()->dateToDate($this->request->input('date_end', ''))) {
            $q->byCreatedAtEnd($filter);
        }

        return $q->get();
    }

    /**
     * @param array $values
     *
     * @return array
     */
    protected function labels(array $values): array
    {
        $values = array_unique(array_merge([], ...array_values(array_map('array_keys', $values))));

        sort($values);

        return $values;
    }
}
