<?php declare(strict_types=1);

namespace App\Domains\Order\ControllerTest;

use Illuminate\Support\Collection;
use App\Domains\Order\Mail\Filled;
use App\Domains\Order\Model\Order as Model;

class MailFilled extends ControllerTestAbstract
{
    /**
     * @return \App\Domains\Order\Mail\Filled
     */
    public function __invoke(): Filled
    {
        return $this->factory()->mail()->filled(
            $row = $this->rowLast(),
            $this->previous($row),
        );
    }

    /**
     * @param \App\Domains\Order\Model\Order $row
     *
     * @return \Illuminate\Support\Collection
     */
    protected function previous(Model $row): Collection
    {
        return Model::query()
            ->byIdPrevious($row->id)
            ->byUserId($row->user_id)
            ->byProductId($row->product_id)
            ->whereFilled()
            ->orderByLast()
            ->limit(5)
            ->get();
    }
}
