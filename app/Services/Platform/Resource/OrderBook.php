<?php declare(strict_types=1);

namespace App\Services\Platform\Resource;

use App\Services\Platform\Resource\Traits\Properties;

class OrderBook
{
    use Properties;

    /**
     * @var array
     */
    public array $asks;

    /**
     * @var array
     */
    public array $bids;

    /**
     * @param array $attributes
     *
     * @return self
     */
    public function __construct(array $attributes)
    {
        $this->properties($attributes);
    }

    /**
     * @param string $type
     *
     * @return array
     */
    public function group(string $type): array
    {
        if (empty($this->$type)) {
            return [];
        }

        $reference = $this->groupReference($this->$type[0][0]);
        $decimals = strpos(strrev(strval($reference)), '.') ?: 0;
        $float = is_float($reference);
        $values = [];

        foreach ($this->$type as $each) {
            $key = $this->groupKey($each[0], $reference, $float, $decimals);
            $values[$key] = ($values[$key] ?? 0) + $each[1];
        }

        return array_slice($values, 0, -10, true);
    }

    /**
     * @param float $value
     *
     * @return int|float
     */
    protected function groupReference(float $value)
    {
        if ($value > 10000) {
            return 50;
        }

        if ($value > 1000) {
            return 5;
        }

        if ($value > 100) {
            return 1;
        }

        if ($value > 10) {
            return 0.05;
        }

        if ($value > 1) {
            return 0.01;
        }

        return 0.001;
    }

    /**
     * @param float $value
     * @param float $reference
     * @param bool $float
     * @param int $decimals
     *
     * @return string
     */
    protected function groupKey(float $value, float $reference, bool $float, int $decimals): string
    {
        $key = floor($value / $reference) * $reference;

        return $float ? sprintf('%.'.$decimals.'f', $key) : strval($key);
    }
}
