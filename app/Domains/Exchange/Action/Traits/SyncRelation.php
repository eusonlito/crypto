<?php declare(strict_types=1);

namespace App\Domains\Exchange\Action\Traits;

use App\Domains\Exchange\Model\Exchange as Model;
use App\Domains\Ticker\Model\Ticker as TickerModel;
use App\Domains\Wallet\Model\Wallet as WalletModel;
use App\Services\Command\Artisan;

trait SyncRelation
{
    /**
     * @param \App\Domains\Exchange\Model\Exchange $row
     *
     * @return void
     */
    protected function relations(Model $row): void
    {
        $this->relationsWallets($row);
        $this->relationsTickers($row);
    }

    /**
     * @param \App\Domains\Exchange\Model\Exchange $row
     *
     * @return void
     */
    protected function relationsWallets(Model $row): void
    {
        $this->relationsWalletsExchange($row);
        $this->relationsWalletsSell($row);
    }

    /**
     * @param \App\Domains\Exchange\Model\Exchange $row
     *
     * @return void
     */
    protected function relationsWalletsExchange(Model $row): void
    {
        WalletModel::updateByProductIdAndExchange($row->product_id, $row->exchange);
    }

    /**
     * @param \App\Domains\Exchange\Model\Exchange $row
     *
     * @return void
     */
    protected function relationsWalletsSell(Model $row): void
    {
        foreach (WalletModel::byProductId($row->product_id)->whereSellStopLossActivated()->pluck('id') as $each) {
            Artisan::exec(sprintf('wallet:sell-stop-loss --id=%s', $each));
        }

        foreach (WalletModel::byProductId($row->product_id)->whereSellStopMinActivated()->pluck('id') as $each) {
            Artisan::exec(sprintf('wallet:sell-stop:min --id=%s', $each));
        }

        foreach (WalletModel::byProductId($row->product_id)->whereSellStopMaxActivated()->pluck('id') as $each) {
            Artisan::exec(sprintf('wallet:sell-stop:max --id=%s', $each));
        }

        foreach (WalletModel::byProductId($row->product_id)->whereBuyStopMaxActivated()->pluck('id') as $each) {
            Artisan::exec(sprintf('wallet:buy-stop:max --id=%s', $each));
        }

        foreach (WalletModel::byProductId($row->product_id)->whereBuyStopMinActivated()->pluck('id') as $each) {
            Artisan::exec(sprintf('wallet:buy-stop:min --id=%s', $each));
        }

        foreach (WalletModel::byProductId($row->product_id)->whereBuyMarketActivated()->pluck('id') as $each) {
            Artisan::exec(sprintf('wallet:buy-market --id=%s', $each));
        }
    }

    /**
     * @param \App\Domains\Exchange\Model\Exchange $row
     *
     * @return void
     */
    protected function relationsTickers(Model $row): void
    {
        TickerModel::updateByProductIdAndExchange($row->product_id, $row->exchange);
    }
}
