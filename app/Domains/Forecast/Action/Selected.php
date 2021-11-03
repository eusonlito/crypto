<?php declare(strict_types=1);

namespace App\Domains\Forecast\Action;

use Illuminate\Support\Collection;
use App\Domains\Currency\Model\Currency as CurrencyModel;
use App\Domains\Forecast\Model\Forecast as Model;
use App\Domains\Forecast\Service\Version\VersionCollectionFactory;

class Selected extends ActionAbstract
{
    /**
     * @var \App\Domains\Currency\Model\Currency
     */
    protected CurrencyModel $currency;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $list;

    /**
     * @param \App\Domains\Currency\Model\Currency $currency
     *
     * @return ?\App\Domains\Forecast\Model\Forecast
     */
    public function handle(CurrencyModel $currency): ?Model
    {
        $this->currency = $currency;

        $this->list();
        $this->row();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function list(): void
    {
        $this->list = $this->factory()->action()->all($this->currency);
    }

    /**
     * @return void
     */
    protected function row(): void
    {
        $this->row = VersionCollectionFactory::get($this->list)->sort()->first();
    }
}
