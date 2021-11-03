<?php declare(strict_types=1);

namespace App\Services\Platform\Resource;

use App\Services\Platform\Resource\Traits\Properties;

class Currency
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
     * @var string
     */
    public string $symbol;

    /**
     * @var int
     */
    public int $precision;

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
