<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Kucoin\Api;

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
        return $this->collection($this->query()->data);
    }

    /**
     * @return \stdClass
     */
    protected function query(): stdClass
    {
        return $this->requestAuth('GET', '/api/v1/accounts', [
            'type' => 'trade',
        ]);
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
            'crypto' => ($row->currency !== 'USDT'),
            'trading' => true,
        ]);
    }
}
