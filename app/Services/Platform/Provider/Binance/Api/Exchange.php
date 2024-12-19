<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Binance\Api;

use stdClass;
use App\Services\Platform\Resource\Exchange as ExchangeResource;

class Exchange extends ApiAbstract
{
    /**
     * @var string
     */
    protected string $product;

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
     * @return ?\App\Services\Platform\Resource\Exchange
     */
    public function handle(): ?ExchangeResource
    {
        return $this->resource($this->query());
    }

    /**
     * @return \stdClass
     */
    protected function query(): stdClass
    {
        return $this->requestGuest('GET', '/api/v3/ticker/price', [
            'symbol' => $this->product,
        ]);
    }

    /**
     * @param \stdClass $row
     *
     * @return ?\App\Services\Platform\Resource\Exchange
     */
    protected function resource(stdClass $row): ?ExchangeResource
    {
        $price = floatval($row->price);

        if ($price === 0.0) {
            return null;
        }

        return new ExchangeResource([
            'code' => $row->symbol,
            'price' => $price,
            'createdAt' => date('Y-m-d H:i:s'),
        ]);
    }
}
