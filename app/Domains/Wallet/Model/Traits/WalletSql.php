<?php declare(strict_types=1);

namespace App\Domains\Wallet\Model\Traits;

trait WalletSql
{
    /**
     * @param int $product_id
     * @param float $exchange
     *
     * @return void
     */
    public static function updateByProductIdAndExchange(int $product_id, float $exchange): void
    {
        static::db()->unprepared(strtr('
            UPDATE `wallet`
            SET
                `current_exchange` = :exchange,
                `current_value` = `current_exchange` * `amount`,

                '.static::updateBySqlSellStop().',
                '.static::updateBySqlBuyStopFollow().',
                '.static::updateBySqlBuyStop().',
                '.static::updateBySqlStopLoss().'

            WHERE `product_id` = :product_id;
        ', [
            ':product_id' => $product_id,
            ':exchange' => $exchange,
        ]));
    }

    /**
     * @return string
     */
    protected static function updateBySqlSellStop(): string
    {
        return '
            `sell_stop_max_exchange` = IF (
                (
                    `sell_stop`
                    AND `sell_stop_max_exchange`
                    AND `current_exchange` > `sell_stop_max_exchange`
                ),
                `current_exchange`, `sell_stop_max_exchange`
            ),

            `sell_stop_max_value` = `sell_stop_max_exchange` * `sell_stop_amount`,

            `sell_stop_max_at` = IF (
                (
                    `sell_stop`
                    AND `sell_stop_max_exchange`
                    AND `current_exchange` >= `sell_stop_max_exchange`
                ), NOW(), `sell_stop_max_at`
            ),

            `sell_stop_max_executable` = IF (
                (
                    `sell_stop`
                    AND `sell_stop_max_exchange`
                    AND `current_exchange` >= `sell_stop_max_exchange`
                ), TRUE, FALSE
            ),

            `sell_stop_min_exchange` = IF (
                (
                    `sell_stop`
                    AND `sell_stop_min_percent`
                    AND `sell_stop_max_exchange`
                    AND `sell_stop_min_at` IS NULL
                    AND `current_exchange` >= `sell_stop_max_exchange`
                ),
                `sell_stop_max_exchange` * (1 - (`sell_stop_min_percent` / 100)),
                `sell_stop_min_exchange`
            ),

            `sell_stop_min_value` = `sell_stop_min_exchange` * `sell_stop_amount`,

            `sell_stop_min_at` = IF (
                (
                    `sell_stop`
                    AND `sell_stop_min_exchange`
                    AND `sell_stop_max_at` IS NOT NULL
                    AND `current_exchange` <= `sell_stop_min_exchange`
                ), NOW(), `sell_stop_min_at`
            ),

            `sell_stop_min_executable` = IF (
                (
                    `sell_stop`
                    AND `sell_stop_min_exchange`
                    AND `sell_stop_max_exchange`
                    AND `sell_stop_min_at` IS NOT NULL
                    AND `sell_stop_max_at` IS NOT NULL
                    AND `current_exchange` <= `sell_stop_min_exchange`
                ), TRUE, `sell_stop_min_executable`
            )
        ';
    }

    /**
     * @return string
     */
    protected static function updateBySqlBuyStopFollow(): string
    {
        return '
            `buy_stop_reference` = IF (
                (
                    `buy_stop`
                    AND `buy_stop_reference`
                    AND `buy_stop_max_follow`
                    AND `current_exchange` > `buy_stop_reference`
                ), `current_exchange`, `buy_stop_reference`
            ),

            `buy_stop_min_exchange` = IF (
                (
                    `buy_stop`
                    AND `buy_stop_reference`
                    AND `buy_stop_max_follow`
                    AND `buy_stop_min_percent`
                    AND `current_exchange` >= `buy_stop_reference`
                ),
                `buy_stop_reference` * (1 - (`buy_stop_min_percent` / 100)),
                `buy_stop_min_exchange`
            ),

            `buy_stop_max_exchange` = IF (
                (
                    `buy_stop`
                    AND `buy_stop_reference`
                    AND `buy_stop_max_follow`
                    AND `buy_stop_max_percent`
                    AND `buy_stop_min_exchange`
                    AND `buy_stop_max_at` IS NULL
                    AND `current_exchange` >= `buy_stop_reference`
                ),
                `buy_stop_min_exchange` * (1 + (`buy_stop_max_percent` / 100)),
                `buy_stop_max_exchange`
            ),

            `buy_stop_amount` = IF (
                (
                    `buy_stop`
                    AND `buy_stop_reference`
                    AND `buy_stop_max_follow`
                    AND `buy_stop_max_value`
                    AND `buy_stop_max_exchange`
                    AND `current_exchange` >= `buy_stop_reference`
                ),
                `buy_stop_max_value` / `buy_stop_max_exchange`,
                `buy_stop_amount`
            )
        ';
    }

    /**
     * @return string
     */
    protected static function updateBySqlBuyStop(): string
    {
        return '
            `buy_stop_min_exchange` = IF (
                (
                    `buy_stop`
                    AND `buy_stop_min_exchange`
                    AND `current_exchange` <= `buy_stop_min_exchange`
                ), `current_exchange`, `buy_stop_min_exchange`
            ),

            `buy_stop_min_value` = `buy_stop_min_exchange` * `buy_stop_amount`,

            `buy_stop_min_at` = IF (
                (
                    `buy_stop`
                    AND `buy_stop_min_exchange`
                    AND `current_exchange` <= `buy_stop_min_exchange`
                ), NOW(), `buy_stop_min_at`
            ),

            `buy_stop_min_executable` = IF (
                (
                    `buy_stop`
                    AND `buy_stop_min_exchange`
                    AND `current_exchange` <= `buy_stop_min_exchange`
                ), TRUE, FALSE
            ),

            `buy_stop_max_exchange` = IF (
                (
                    `buy_stop`
                    AND `buy_stop_max_percent`
                    AND `buy_stop_min_exchange`
                    AND `buy_stop_max_at` IS NULL
                    AND `current_exchange` <= `buy_stop_min_exchange`
                ),
                `buy_stop_min_exchange` * (1 + (`buy_stop_max_percent` / 100)),
                `buy_stop_max_exchange`
            ),

            `buy_stop_max_at` = IF (
                (
                    `buy_stop`
                    AND `buy_stop_max_exchange`
                    AND `buy_stop_min_at` IS NOT NULL
                    AND `current_exchange` >= `buy_stop_max_exchange`
                ), NOW(), `buy_stop_max_at`
            ),

            `buy_stop_max_executable` = IF (
                (
                    `buy_stop`
                    AND `buy_stop_max_exchange`
                    AND `buy_stop_min_exchange`
                    AND `buy_stop_max_at` IS NOT NULL
                    AND `buy_stop_min_at` IS NOT NULL
                    AND `current_exchange` >= `buy_stop_max_exchange`
                ), TRUE, `buy_stop_max_executable`
            )
        ';
    }

    /**
     * @return string
     */
    protected static function updateBySqlStopLoss(): string
    {
        return '
            `sell_stoploss_at` = IF (
                (
                    `sell_stoploss`
                    AND `sell_stoploss_exchange`
                    AND `sell_stoploss_at` IS NULL
                    AND `current_exchange` <= `sell_stoploss_exchange`
                    AND `current_value` >= 1
                ), NOW(), `sell_stoploss_at`
            ),

            `sell_stoploss_executable` = IF (
                (
                    `sell_stoploss`
                    AND `sell_stoploss_exchange`
                    AND `sell_stoploss_at` IS NOT NULL
                    AND `current_exchange` <= `sell_stoploss_exchange`
                    AND `current_value` >= 1
                ), TRUE, FALSE
            )
        ';
    }
}
