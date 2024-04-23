<?php declare(strict_types=1);

namespace App\Domains\Order\Model\Traits;

trait OrderSql
{
    /**
     * @return void
     */
    public static function walletFix(): void
    {
        static::DB()->unprepared('
            UPDATE `order`
            SET `wallet_id` = (
                SELECT `id`
                FROM `wallet`
                WHERE (
                    `wallet`.`product_id` = `order`.`product_id`
                    AND `wallet`.`user_id`  = `order`.`user_id`
                )
            );
        ');
    }
}
