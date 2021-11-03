<?php declare(strict_types=1);

namespace App\Domains\Wallet\Service\Logger;

use App\Domains\Wallet\Model\Wallet as Model;

class BuySellStop extends LoggerAbstract
{
    /**
     * @var string
     */
    protected static string $name = 'wallet-buy-sell-stop';

    /**
     * @param string $action
     * @param \App\Domains\Wallet\Model\Wallet $row
     * @param bool $executable
     *
     * @return void
     */
    public static function set(string $action, Model $row, bool $executable): void
    {
        static::info($action.'-'.$row->id, [
            'action' => $action,
            'executable' => $executable,
            'row' => $row->toArray(),
        ]);
    }
}
