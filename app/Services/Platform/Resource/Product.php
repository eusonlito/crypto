<?php declare(strict_types=1);

namespace App\Services\Platform\Resource;

class Product extends ResourceAbstract
{
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
}
