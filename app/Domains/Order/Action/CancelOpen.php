<?php declare(strict_types=1);

namespace App\Domains\Order\Action;

use Throwable;
use App\Domains\Order\Model\Order as Model;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Platform\Service\Provider\ProviderApiFactory;
use App\Domains\Product\Model\Product as ProductModel;
use App\Services\Platform\ApiFactoryAbstract;

class CancelOpen extends ActionAbstract
{
    /**
     * @var \App\Services\Platform\ApiFactoryAbstract
     */
    protected ApiFactoryAbstract $api;

    /**
     * @var \App\Domains\Platform\Model\Platform
     */
    protected PlatformModel $platform;

    /**
     * @var \App\Domains\Product\Model\Product
     */
    protected ProductModel $product;

    /**
     * @param \App\Domains\Product\Model\Product $product
     *
     * @return void
     */
    public function handle(ProductModel $product): void
    {
        $this->product($product);
        $this->platform();

        $this->api();
        $this->cancelOpen();
        $this->save();
    }

    /**
     * @param \App\Domains\Product\Model\Product $product
     *
     * @return void
     */
    protected function product(ProductModel $product): void
    {
        $this->product = $product;
    }

    /**
     * @return void
     */
    protected function platform(): void
    {
        $this->platform = $this->product->platform;
        $this->platform->userPivotLoad($this->auth);
    }

    /**
     * @return void
     */
    protected function api(): void
    {
        $this->api = ProviderApiFactory::get($this->platform);
    }

    /**
     * @return void
     */
    protected function cancelOpen(): void
    {
        if ($this->api->ordersOpen($this->product->code)->isNotEmpty()) {
            $this->api->ordersCancelAll($this->product->code);
        }
    }

    /**
     * @return void
     */
    protected function save(): void
    {
        while (true) {
            try {
                $this->saveUpdate();
                return;
            } catch (Throwable) {
                sleep(1);
            }
        }
    }

    /**
     * @return void
     */
    protected function saveUpdate(): void
    {
        Model::query()
            ->byUserId($this->auth->id)
            ->byProductId($this->product->id)
            ->byStatus('new')
            ->update(['status' => 'canceled']);
    }
}
