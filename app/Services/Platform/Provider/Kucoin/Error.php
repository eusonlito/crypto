<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Kucoin;

use App\Exceptions\UnexpectedValueException;

class Error
{
    /**
     * @param mixed $response
     *
     * @return mixed
     */
    public static function check($response)
    {
        if ($response === null) {
            throw new UnexpectedValueException(__('kucoin.error.empty'));
        }

        if (isset($response->msg)) {
            static::errors($response->msg);
        }

        return $response;
    }

    /**
     * @param string $error
     *
     * @return void
     */
    protected static function errors(string $error): void
    {
        throw new UnexpectedValueException($error);
    }
}
