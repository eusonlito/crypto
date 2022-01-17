<?php declare(strict_types=1);

namespace App\Services\Platform\Resource;

class Order extends ResourceAbstract
{
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
}
