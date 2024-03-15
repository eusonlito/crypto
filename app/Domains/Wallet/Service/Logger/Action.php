<?php declare(strict_types=1);

namespace App\Domains\Wallet\Service\Logger;

use App\Domains\Wallet\Model\Wallet as Model;
use App\Services\Logger\RotatingFileAbstract;

class Action extends RotatingFileAbstract
{
    /**
     * @var string
     */
    protected static string $name;

    /**
     * @return string
     */
    protected static function folder(): string
    {
        return 'wallet';
    }

    /**
     * @return string
     */
    protected static function path(): string
    {
        return date('Y/m/d').'/'.static::$name;
    }

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
        static::$name = $action.'-'.$row->id;

        static::$status($action.'-'.$row->id, [
            'action' => $action,
            'row' => $row->toArray(),
            'data' => $data,
        ]);
    }
}
