<?php declare(strict_types=1);

namespace App\Domains\Order\Model\Traits;

trait OrderSql
{
    /**
     * @return void
     */
    public static function walletFix(): void
    {
        static::db()->unprepared('
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

    /**
     * @return void
     */
    public static function previousSet(): void
    {
        static::db()->unprepared('
            UPDATE `order` AS `current`
            SET
                `previous_price` = COALESCE((
                    SELECT `previous`.`price`
                    FROM (
                        SELECT `price`, `side`, `created_at`, `wallet_id`
                        FROM `order`
                        WHERE `filled` = 1
                    ) `previous`
                    WHERE (
                        `previous`.`wallet_id` = `current`.`wallet_id`
                        AND `previous`.`side` != `current`.`side`
                        AND `previous`.`created_at` < `current`.`created_at`
                    )
                    ORDER BY `previous`.`created_at` DESC
                    LIMIT 1
                ), 0),
                `previous_value` = `previous_price` * `amount`,
                `previous_percent` = COALESCE(ROUND((`value` - `previous_value`) / NULLIF(`previous_value`, 0) * 100, 2), 0);
        ');
    }

    /**
     * @param int $wallet_id
     *
     * @return void
     */
    public static function previousSetByWalletId(int $wallet_id): void
    {
        static::db()->unprepared(strtr('
            UPDATE `order` AS `current`
            WHERE `wallet_id` = :wallet_id
            SET
                `previous_price` = COALESCE((
                    SELECT `previous`.`price`
                    FROM (
                        SELECT `price`, `side`, `created_at`, `wallet_id`
                        FROM `order`
                        WHERE (
                            `wallet_id` = :wallet_id
                            AND `filled` = 1
                        )
                    ) `previous`
                    WHERE (
                        `previous`.`side` != `current`.`side`
                        AND `previous`.`created_at` < `current`.`created_at`
                    )
                    ORDER BY `previous`.`created_at` DESC
                    LIMIT 1
                ), 0),
                `previous_value` = `previous_price` * `amount`,
                `previous_percent` = COALESCE(ROUND((`value` - `previous_value`) / NULLIF(`previous_value`, 0) * 100, 2), 0);
        ', [
            ':wallet_id' => $wallet_id,
        ]));
    }

    /**
     * @param int $wallet_id
     * @param string $created_at
     *
     * @return void
     */
    public static function previousSetByWalletIdAndCreatedAt(int $wallet_id, string $created_at): void
    {
        static::db()->unprepared(strtr('
            UPDATE `order` AS `current`
            WHERE (
                `wallet_id` = :wallet_id
                AND `created_at` >= ":created_at"
            )
            SET
                `previous_price` = COALESCE((
                    SELECT `previous`.`price`
                    FROM (
                        SELECT `price`, `side`, `created_at`, `wallet_id`
                        FROM `order`
                        WHERE (
                            `wallet_id` = :wallet_id
                            AND `filled` = 1
                        )
                    ) `previous`
                    WHERE (
                        `previous`.`side` != `current`.`side`
                        AND `previous`.`created_at` < `current`.`created_at`
                    )
                    ORDER BY `previous`.`created_at` DESC
                    LIMIT 1
                ), 0),
                `previous_value` = `previous_price` * `amount`,
                `previous_percent` = COALESCE(ROUND((`value` - `previous_value`) / NULLIF(`previous_value`, 0) * 100, 2), 0);
        ', [
            ':wallet_id' => $wallet_id,
            ':created_at' => $created_at,
        ]));
    }
}
