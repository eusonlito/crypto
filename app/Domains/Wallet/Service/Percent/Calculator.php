<?php declare(strict_types=1);

namespace App\Domains\Wallet\Service\Percent;

use Illuminate\Support\Collection;
use App\Domains\Wallet\Model\Wallet as Model;

class Calculator
{
    /**
     * @var \App\Domains\Wallet\Model\Wallet
     */
    protected Model $row;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $exchanges;

    /**
     * @param \App\Domains\Wallet\Model\Wallet $row
     * @param \Illuminate\Support\Collection $exchanges
     *
     * @return self
     */
    public static function new(Model $row, Collection $exchanges): self
    {
        return new self($row, $exchanges);
    }

    /**
     * @param \App\Domains\Wallet\Model\Wallet $row
     * @param \Illuminate\Support\Collection $exchanges
     *
     * @return self
     */
    public function __construct(Model $row, Collection $exchanges)
    {
        $this->row = $row;
        $this->exchanges = $exchanges;
    }

    /**
     * @return array
     */
    public function get(): array
    {
        return [];
    }
}
