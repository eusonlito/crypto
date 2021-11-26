<?php declare(strict_types=1);

namespace App\Domains\Order\Service\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Domains\Order\Model\Order as Model;
use App\Domains\User\Model\User as UserModel;

class Index
{
    /**
     * @param \App\Domains\User\Model\User
     */
    protected UserModel $user;

    /**
     * @param \Illuminate\Http\Request
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
        $q = Model::byUserId($this->user->id)->list();

        if ($filter = $this->request->input('platform_id')) {
            $q->byPlatformId($filter);
        }

        if (strlen($filter = $this->request->input('filled'))) {
            $q->whereFilled((bool)$filter)->withProductExchange();
        }

        if ($filter = $this->request->input('side')) {
            $q->bySide($filter);
        }

        if ($filter = helper()->dateToDate($this->request->input('date_start', ''))) {
            $q->byCreatedAtStart($filter);
        }

        if ($filter = helper()->dateToDate($this->request->input('date_end', ''))) {
            $q->byCreatedAtEnd($filter);
        }

        return $q->paginate(50)->map(fn ($list) => $this->map($list));
    }

    /**
     * @param \App\Domains\Order\Model\Order $row
     *
     * @return Model
     */
    protected function map(Model $row): Model
    {
        $row->exchange_current = $row->product->exchange->exchange ?? 0;
        $row->value_current = $row->amount * $row->exchange_current;

        if (empty($row->value_current) || ($row->side === 'sell')) {
            $row->success = $row->value >= $row->value_current;
        } else {
            $row->success = $row->value_current >= $row->value;
        }

        return $row;
    }
}
