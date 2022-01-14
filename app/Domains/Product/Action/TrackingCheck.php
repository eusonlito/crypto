<?php declare(strict_types=1);

namespace App\Domains\Product\Action;

use App\Domains\Product\Model\Product as Model;
use App\Domains\Wallet\Model\Wallet as WalletModel;

class TrackingCheck extends ActionAbstract
{
    /**
     * @var array
     */
    protected array $ids;

    /**
     * @return void
     */
    public function handle(): void
    {
        $this->ids();
        $this->update();
    }

    /**
     * @return void
     */
    protected function ids(): void
    {
        $this->ids = WalletModel::whereBuyOrSellPending()->pluck('product_id')->toArray();
    }

    /**
     * @return void
     */
    protected function update(): void
    {
        Model::byIdsNot($this->ids)->update(['tracking' => false]);
    }
}
