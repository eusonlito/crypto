<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Binance\Api;

class OrdersCancelAll extends ApiAbstract
{
    /**
     * @var string
     */
    protected string $product;

    /**
     * @var bool
     */
    protected bool $log = true;

    /**
     * @param string $product
     *
     * @return self
     */
    public function __construct(string $product)
    {
        $this->product = $product;
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        $this->query();
    }

    /**
     * @return void
     */
    protected function query(): void
    {
        app()->isProduction()
            ? $this->queryProduction()
            : $this->queryFake();
    }

    /**
     * @return void
     */
    protected function queryProduction(): void
    {
        $this->requestAuth('DELETE', '/api/v3/openOrders', ['symbol' => $this->product]);
    }

    /**
     * @return void
     */
    protected function queryFake(): void
    {
    }
}
