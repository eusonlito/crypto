<?php declare(strict_types=1);

namespace App\Services\Platform\Resource;

class Wallet extends ResourceAbstract
{
    /**
     * @var string
     */
    public string $address;

    /**
     * @var string
     */
    public string $symbol;

    /**
     * @var string
     */
    public string $name;

    /**
     * @var float
     */
    public float $amount;

    /**
     * @var bool
     */
    public bool $crypto;

    /**
     * @var bool
     */
    public bool $trading;
}
