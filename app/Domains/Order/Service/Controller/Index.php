<?php declare(strict_types=1);

namespace App\Domains\Order\Service\Controller;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Domains\Order\Model\Order as Model;
use App\Domains\User\Model\User as UserModel;

class Index
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
     * @return \Illuminate\Pagination\LengthAwarePaginator|\Illuminate\Support\Collection
     */
    public function get(): LengthAwarePaginator|Collection
    {
        $q = Model::query()->byUserId($this->user->id)->list();

        if ($filter = $this->request->input('search')) {
            $q->byProductSearch($filter);
        }

        if ($filter = $this->request->input('platform_id')) {
            $q->byPlatformId($filter);
        }

        if (strlen($filter = $this->request->input('filled'))) {
            $q->whereFilled((bool)$filter)->withProductExchange();
        }

        if ($filter = $this->request->input('side')) {
            $q->bySide($filter);
        }

        if (strlen($filter = $this->request->input('custom'))) {
            $q->whereCustom((bool)$filter);
        }

        if ($filter = helper()->dateToDate($this->request->input('date_start', ''))) {
            $q->byCreatedAtStart($filter);
        }

        if ($filter = helper()->dateToDate($this->request->input('date_end', ''))) {
            $q->byCreatedAtEnd($filter);
        }

        if ($this->isPaginated()) {
            return $q->get()->map($this->map(...));
        }

        $list = $q->paginate(50);
        $list->getCollection()->transform($this->map(...));

        return $list;
    }

    /**
     * @return bool
     */
    protected function isPaginated(): bool
    {
        return $this->request->input('search')
            || $this->request->input('date_start')
            || $this->request->input('date_end');
    }

    /**
     * @param \App\Domains\Order\Model\Order $row
     *
     * @return \App\Domains\Order\Model\Order
     */
    protected function map(Model $row): Model
    {
        $row->exchange_current = $row->product->exchange->exchange ?? 0;
        $row->value_current = $row->amount * $row->exchange_current;

        if (empty($row->value_current) || ($row->side === 'sell')) {
            $row->success = $row->value >= $row->value_current;
            $row->difference = $row->value - $row->value_current;
        } else {
            $row->success = $row->value_current >= $row->value;
            $row->difference = $row->value_current - $row->value;
        }

        return $row;
    }
}
