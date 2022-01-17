<?php declare(strict_types=1);

namespace App\Services\Platform\Resource;

class Candle extends ResourceAbstract
{
    /**
     * @var string
     */
    public string $startAt;

    /**
     * @var string
     */
    public string $endAt;

    /**
     * @var float
     */
    public float $open;

    /**
     * @var float
     */
    public float $high;

    /**
     * @var float
     */
    public float $low;

    /**
     * @var float
     */
    public float $close;

    /**
     * @var float
     */
    public float $volume;

    /**
     * @var float
     */
    public float $volumeQuote;

    /**
     * @var int
     */
    public int $count;
}
