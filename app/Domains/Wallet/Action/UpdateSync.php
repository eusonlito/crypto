<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action;

use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Product\Model\Product as ProductModel;
use App\Domains\Wallet\Model\Wallet as Model;
use App\Domains\Wallet\Service\Logger\Action as ActionLogger;

class UpdateSync extends ActionAbstract
{
    /**
     * @var \App\Domains\Platform\Model\Platform
     */
    protected PlatformModel $platform;

    /**
     * @var \App\Domains\Product\Model\Product
     */
    protected ProductModel $product;

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function handle(): Model
    {
        $this->platform();
        $this->product();
        $this->logBefore();

        if ($this->available() === false) {
            return tap($this->row, fn () => $this->logNotAvailable());
        }

        $this->order();
        $this->wallet();
        $this->logSuccess();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function platform(): void
    {
        $this->platform = $this->row->platform;
        $this->platform->userPivotLoad($this->auth);
    }

    /**
     * @return void
     */
    protected function product(): void
    {
        $this->product = $this->row->product;
        $this->product->setRelation('platform', $this->platform);
    }

    /**
     * @return bool
     */
    protected function available(): bool
    {
        return (bool)$this->platform->userPivot;
    }

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    protected function notAvailable(): Model
    {
        ActionLogger::set('error', 'update-sync', $this->row, ['error' => 'Not Available']);

        return $this->row;
    }

    /**
     * @return void
     */
    protected function order(): void
    {
        $this->factory('Order')->action()->syncByProducts(collect([$this->product]));
    }

    /**
     * @return void
     */
    protected function wallet(): void
    {
        $this->walletCurrent();
        $this->walletCurrency();
    }

    /**
     * @return void
     */
    protected function walletCurrent(): void
    {
        $this->syncOne($this->row);
    }

    /**
     * @return void
     */
    protected function walletCurrency(): void
    {
        if ($row = $this->walletCurrencyRow()) {
            $this->syncOne($row);
        }
    }

    /**
     * @return ?\App\Domains\Wallet\Model\Wallet
     */
    protected function walletCurrencyRow(): ?Model
    {
        return Model::query()
            ->byUserId($this->auth->id)
            ->byProductCurrencyBaseIdAndCurrencyQuoteId($this->product->currency_quote_id, $this->product->currency_quote_id)
            ->first();
    }

    /**
     * @param \App\Domains\Wallet\Model\Wallet $row
     *
     * @return void
     */
    protected function syncOne(Model $row): void
    {
        $this->factory(null, $row)->action()->syncOne();
    }

    /**
     * @return void
     */
    protected function logBefore(): void
    {
        $this->log('info', ['detail' => __FUNCTION__]);
    }

    /**
     * @return void
     */
    protected function logNotAvailable(): void
    {
        $this->log('error', ['error' => 'Not Available']);
    }

    /**
     * @return void
     */
    protected function logSuccess(): void
    {
        $this->log('info', ['success' => true]);
    }

    /**
     * @param string $status
     * @param array $data = []
     *
     * @return void
     */
    protected function log(string $status, array $data = []): void
    {
        ActionLogger::set($status, 'update-sync', $this->row, $data);
    }
}
