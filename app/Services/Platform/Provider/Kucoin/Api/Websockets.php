<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Kucoin\Api;

use stdClass;

class Websockets extends ApiAbstract
{
    /**
     * @return \stdClass
     */
    public function handle(): stdClass
    {
        return $this->query()->data;
    }

    /**
     * @return \stdClass
     */
    protected function query(): stdClass
    {
        return $this->requestGuest('POST', '/api/v1/bullet-public');
    }
}
