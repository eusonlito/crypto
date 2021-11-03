<?php declare(strict_types=1);

namespace App\Services\Platform\Resource;

use App\Services\Platform\Resource\Traits\Properties;

class Wallet
{
    use Properties;

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
