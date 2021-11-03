<?php declare(strict_types=1);

namespace App\Services\Platform\Resource;

use App\Services\Platform\Resource\Traits\Properties;

class Product
{
    use Properties;

    /**
     * @var string
     */
    public string $code;

    /**
     * @var string
     */
    public string $name;

    /**
     * @var int
     */
    public int $precision;

    /**
     * @var float
     */
    public float $priceMin;

    /**
     * @var float
     */
    public float $priceMax;

    /**
     * @var int
     */
    public int $priceDecimal;

    /**
     * @var float
     */
    public float $quantityMin;

    /**
     * @var float
     */
    public float $quantityMax;

    /**
     * @var int
     */
    public int $quantityDecimal;

    /**
     * @var string
     */
    public string $currencyBase;

    /**
     * @var string
     */
    public string $currencyQuote;

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
