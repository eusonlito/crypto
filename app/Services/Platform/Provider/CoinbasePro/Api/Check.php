<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\CoinbasePro\Api;

use Exception;

class Check extends ApiAbstract
{
    /**
     * @return bool
     */
    public function handle(): bool
    {
        try {
            $this->query();
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    protected function query(): array
    {
        return $this->requestAuth('GET', '/accounts');
    }
}
