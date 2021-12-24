<?php declare(strict_types=1);

namespace App\Domains\Wallet\Service\Logger;

use App\Domains\Wallet\Model\Wallet as Model;

class Action extends LoggerAbstract
{
    /**
     * @var string
     */
    protected static string $name = 'wallet-action';

    /**
     * @param string $status
     * @param string $action
     * @param \App\Domains\Wallet\Model\Wallet $row
     * @param array $data
     *
     * @return void
     */
    public static function set(string $status, string $action, Model $row, array $data): void
    {
        static::$status($action.'-'.$row->id, [
            'action' => $action,
            'row' => $row->toArray(),
            'data' => $data,
        ]);
    }
}
