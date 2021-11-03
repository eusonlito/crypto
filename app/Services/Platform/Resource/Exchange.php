<?php declare(strict_types=1);

namespace App\Services\Platform\Resource;

use App\Services\Platform\Resource\Traits\Properties;

class Exchange
{
    use Properties;

    /**
     * @var string
     */
    public string $code;

    /**
     * @var float
     */
    public float $price;

    /**
     * @var string
     */
    public string $createdAt;

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
     * @param \App\Services\Platform\Resource\Exchange $resource
     * @param float $price
     * @param string $created_at
     *
     * @return bool
     */
    public function shouldBeUpdated(Exchange $resource, float $price, string $created_at): bool
    {
        if ($this->pricePercent($resource->price, $price) >= $this->pricePercentReference()) {
            return true;
        }

        return $this->createdAtDiff($resource->createdAt, $created_at) >= $this->createdAtDiffReference();
    }

    /**
     * @param float $current
     * @param float $previous
     *
     * @return float
     */
    protected function pricePercent(float $current, float $previous): float
    {
        return helper()->percent($previous, $current, true, true);
    }

    /**
     * @return float
     */
    protected function pricePercentReference(): float
    {
        return 0.25;
    }

    /**
     * @param string $current
     * @param string $previous
     *
     * @return int
     */
    protected function createdAtDiff(string $current, string $previous): int
    {
        return strtotime($current) - strtotime($previous);
    }

    /**
     * @return int
     */
    protected function createdAtDiffReference(): int
    {
        return 60;
    }
}
