<?php declare(strict_types=1);

namespace App\Domains\Ticker\Model\Traits;

trait TickerSql
{
    /**
     * @param int $product_id
     * @param float $exchange
     *
     * @return void
     */
    public static function updateByProductIdAndExchange(int $product_id, float $exchange): void
    {
        static::DB()->statement('
            UPDATE `ticker`
            SET
                `exchange_current` = :exchange,

                `exchange_min` = IF(`exchange_current` < `exchange_min`, `exchange_current`, `exchange_min`),
                `exchange_min_at` = IF(`exchange_current` = `exchange_min`, NOW(), `exchange_min_at`),

                `exchange_max` = IF(`exchange_current` > `exchange_max`, `exchange_current`, `exchange_max`),
                `exchange_max_at` = IF(`exchange_current` = `exchange_max`, NOW(), `exchange_max_at`),

                `value_current` = `exchange_current` * `amount`,

                `value_min` = `amount` * `exchange_min`,
                `value_max` = `amount` * `exchange_max`
            WHERE `product_id` = :product_id;
        ', [
            'product_id' => $product_id,
            'exchange' => $exchange,
        ]);
    }
}
