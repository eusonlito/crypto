<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Binance\Api;

use Exception;
use stdClass;

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
     * @return \stdClass
     */
    protected function query(): stdClass
    {
        return $this->requestAuth('GET', '/sapi/v1/account/status');
    }
}
