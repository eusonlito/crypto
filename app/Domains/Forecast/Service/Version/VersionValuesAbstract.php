<?php declare(strict_types=1);

namespace App\Domains\Forecast\Service\Version;

use Illuminate\Support\Collection;

abstract class VersionValuesAbstract
{
    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $exchanges;

    /**
     * @var array
     */
    protected array $values = [];

    /**
     * @param \Illuminate\Support\Collection $exchanges
     *
     * @return self
     */
    public function __construct(Collection $exchanges)
    {
        $this->exchanges = $exchanges;
        $this->init();
    }

    /**
     * @return void
     */
    abstract protected function init(): void;

    /**
     * @return bool
     */
    abstract public function error(): bool;

    /**
     * @return int
     */
    abstract public function version(): int;

    /**
     * @return array
     */
    abstract public function keys(): array;

    /**
     * @return array
     */
    abstract public function values(): array;

    /**
     * @return bool
     */
    abstract public function valid(): bool;
}
