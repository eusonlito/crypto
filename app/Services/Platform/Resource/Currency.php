<?php declare(strict_types=1);

namespace App\Services\Platform\Resource;

class Currency extends ResourceAbstract
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
     * @var string
     */
    public string $symbol;

    /**
     * @var int
     */
    public int $precision;
}
