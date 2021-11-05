<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Kucoin\Api;

use stdClass;
use Illuminate\Support\Collection;
use App\Services\Platform\Resource\Wallet as WalletResource;

class Wallets extends ApiAbstract
{
    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $currencies;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function handle(): Collection
    {
        $this->currencies();

        return $this->collection($this->query());
    }

    /**
     * @return array
     */
    protected function query(): array
    {
        return $this->requestAuth('GET', '/accounts');
    }

    /**
     * @return void
     */
    protected function currencies(): void
    {
        $this->currencies = collect($this->currenciesQuery())->keyBy('id');
    }

    /**
     * @return array
     */
    protected function currenciesQuery(): array
    {
        return $this->requestGuest('GET', '/currencies');
    }

    /**
     * @param \stdClass $row
     *
     * @return \App\Services\Platform\Resource\Wallet
     */
    protected function resource(stdClass $row): WalletResource
    {
        return new WalletResource([
            'address' => $row->id,
            'symbol' => $row->currency,
            'name' => $row->currency,
            'amount' => (float)$row->balance,
            'crypto' => ($this->currencies->get($row->currency)->details->type === 'crypto'),
            'trading' => $row->trading_enabled,
        ]);
    }
}
