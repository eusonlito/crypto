<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\CoinbasePro\Request;

class Guest extends RequestAbstract
{
    /**
     * @return mixed
     */
    public function send()
    {
        return $this->client()->send()->getBody('object');
    }
}
