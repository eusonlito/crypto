<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Binance\Api;

use stdClass;
use Illuminate\Support\Collection;
use App\Services\Platform\Resource\Wallet as WalletResource;

class Wallets extends ApiAbstract
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function handle(): Collection
    {
        return $this->collection($this->query());
    }

    /**
     * @return array
     */
    protected function query(): array
    {
        return $this->requestAuth('GET', '/sapi/v1/capital/config/getall');
    }

    /**
     * @param \stdClass $row
     *
     * @return \App\Services\Platform\Resource\Wallet
     */
    protected function resource(stdClass $row): WalletResource
    {
        return new WalletResource([
            'address' => $row->coin,
            'symbol' => $row->coin,
            'name' => $row->name,
            'crypto' => ($row->isLegalMoney === false),
            'amount' => ((float)$row->free + (float)$row->locked),
            'trading' => $row->trading,
        ]);
    }
}
