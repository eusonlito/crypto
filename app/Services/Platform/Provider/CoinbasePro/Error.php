<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\CoinbasePro;

use Throwable;
use App\Services\Platform\Exception\InsufficientFundsException;
use App\Services\Platform\Exception\RequestException;

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
            throw new RequestException(__('coinbase-pro.error.empty'));
        }

        if (isset($response->message)) {
            static::errors($response->message);
        }

        return $response;
    }

    /**
     * @param \Throwable $e
     *
     * @return void
     */
    public static function exception(Throwable $e): void
    {
        static::errors($e->getMessage());
    }

    /**
     * @param string $error
     *
     * @return void
     */
    protected static function errors(string $error): void
    {
        if (str_contains(strtolower($error), 'enough funds')) {
            throw new InsufficientFundsException($error);
        }

        throw new RequestException($error);
    }
}
