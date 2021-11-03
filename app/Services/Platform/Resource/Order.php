<?php declare(strict_types=1);

namespace App\Services\Platform\Resource;

use App\Services\Platform\Resource\Traits\Properties;

class Order
{
    use Properties;

    /**
     * @var string
     */
    public string $id;

    /**
     * @var float
     */
    public float $amount;

    /**
     * @var float
     */
    public float $price;

    /**
     * @var float
     */
    public float $priceStop;

    /**
     * @var float
     */
    public float $value;

    /**
     * @var float
     */
    public float $fee;

    /**
     * @var string
     */
    public string $product;

    /**
     * @var string
     */
    public string $type;

    /**
     * @var string
     */
    public string $status;

    /**
     * @var string
     */
    public string $side;

    /**
     * @var bool
     */
    public bool $filled;

    /**
     * @var string
     */
    public string $createdAt;

    /**
     * @var string
     */
    public string $updatedAt;

    /**
     * @param array $attributes
     *
     * @return self
     */
    public function __construct(array $attributes)
    {
        $this->properties($attributes);
    }
}
