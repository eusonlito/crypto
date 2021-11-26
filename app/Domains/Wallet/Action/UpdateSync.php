<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action;

use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Product\Model\Product as ProductModel;
use App\Domains\Wallet\Model\Wallet as Model;

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

        if ($this->available() === false) {
            return $this->row;
        }

        $this->order();
        $this->wallet();

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
     * @return void
     */
    protected function order(): void
    {
        $this->factory('Order')->action()->syncByProduct($this->product);
    }

    /**
     * @return void
     */
    protected function wallet(): void
    {
        $this->walletSameCurrency($this->product->currency_base_id);
        $this->walletSameCurrency($this->product->currency_quote_id);
    }

    /**
     * @param int $currency_id
     *
     * @return void
     */
    protected function walletSameCurrency(int $currency_id): void
    {
        foreach (Model::byUserId($this->auth->id)->byCurrencyId($currency_id)->get() as $row) {
            $this->factory(null, $row)->action()->syncOne();
        }
    }
}
