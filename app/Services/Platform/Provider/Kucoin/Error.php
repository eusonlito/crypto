<?php declare(strict_types=1);

namespace App\Services\Platform\Provider\Kucoin;

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
            throw new RequestException(__('kucoin.error.empty'));
        }

        if (isset($response->msg) && empty($response->success)) {
            static::errors($response->msg, (int)$response->code);
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
        static::errors($e->getMessage(), $e->getCode());
    }

    /**
     * @param string $error
     * @param int $code
     *
     * @return void
     */
    protected static function errors(string $error, int $code): void
    {
        if (str_contains(strtolower($error), 'balance insufficient')) {
            throw new InsufficientFundsException($error, $code);
        }

        throw new RequestException($error, $code);
    }
}
