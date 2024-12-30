<?php declare(strict_types=1);

namespace App\Services\Platform\Resource;

class OrderBook extends ResourceAbstract
{
    /**
     * @var array
     */
    public array $asks;

    /**
     * @var array
     */
    public array $bids;

    /**
     * @param string $type
     * @param float $percent = 0.5
     *
     * @return array
     */
    public function group(string $type, float $percent = 0.5): array
    {
        $group = $this->$type;

        $reference = $group[0][0] * $percent / 100;
        $decimals = strpos(strrev(strval($reference)), '.') ?: 0;
        $float = is_float($reference);
        $values = [];

        foreach ($group as $each) {
            $key = $this->groupKey($each[0], $reference, $float, $decimals);
            $values[$key] = ($values[$key] ?? 0) + $each[1];
        }

        return $values;
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
