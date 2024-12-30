<?php declare(strict_types=1);

namespace App\Services\Trader\Simple;

use App\Domains\Exchange\Model\Exchange as ExchangeModel;
use App\Domains\Wallet\Model\Wallet as WalletModel;
use App\Services\Platform\ApiFactoryAbstract;

abstract class TraderAbstract
{
    /**
     * @var array
     */
    protected array $config;

    /**
     * @var array
     */
    protected array $orderBook = [];

    /**
     * @var array
     */
    protected array $prices = [];

    /**
     * @var array
     */
    protected array $pricesLow = [];

    /**
     * @var array
     */
    protected array $pricesHigh = [];

    /**
     * @var float
     */
    protected float $priceCurrent;

    /**
     * @return float
     */
    abstract public function calculate(): array;

    /**
     * @param float $trend
     *
     * @return float
     */
    abstract protected function calculateTrailingDeltaBips(float $trend): int;

    /**
     * @return self
     */
    public static function new(): self
    {
        return new static(...func_get_args());
    }

    /**
     * @param \App\Domains\Wallet\Model\Wallet $wallet
     * @param \App\Services\Platform\ApiFactoryAbstract $api
     *
     * @return void
     */
    public function __construct(
        protected WalletModel $wallet,
        protected ApiFactoryAbstract $api
    ) {
        $this->prices();
        $this->priceCurrent();
        $this->orderBook();
    }

    /**
     * @return void
     */
    protected function prices(): void
    {
        $this->prices = ExchangeModel::query()
            ->byProductId($this->wallet->product->id)
            ->byCreatedAtAfter(date('Y-m-d H:i:s', strtotime('-1 day')))
            ->orderByFirst()
            ->pluck('exchange')
            ->all();
    }

    /**
     * @return void
     */
    protected function priceCurrent(): void
    {
        $this->priceCurrent = end($this->prices);
    }

    /**
     * @return void
     */
    protected function orderBook(): void
    {
        $this->orderBook = $this->api->orderBook($this->wallet->product->code, 200)->toArray();
    }

    /**
     * @return array
     */
    public function getMarketStats(): array
    {
        $min = min($this->prices);
        $max = max($this->prices);
        $mean = array_sum($this->prices) / count($this->prices);
        $range = $max - $min;

        $volatility = (($max - $min) / $mean) * 100;

        $ma20 = $this->average(array_slice($this->prices, -20));
        $ma50 = $this->average(array_slice($this->prices, -50));
        $trend = $ma20 - $ma50;

        return [
            'min' => $min,
            'max' => $max,
            'mean' => $mean,
            'range' => $range,
            'volatility' => $volatility,
            'ma20' => $ma20,
            'ma50' => $ma50,
            'trend' => $trend,
        ];
    }

    /**
     * @param array $values
     *
     * @return float
     */
    protected function average(array $values): float
    {
        return array_sum($values) / count($values);
    }

    /**
     * @param float $volatility
     * @param float $trend
     *
     * @return int
     */
    protected function calculateTrailingDelta(float $volatility, float $trend): int
    {
        $trendBips = $this->calculateTrailingDeltaBips($trend);

        $trailingDelta = 100 + ($volatility * 2) + $trendBips;
        $trailingDelta = max(min($trailingDelta, $this->config['trailing_delta_max']), $this->config['trailing_delta_min']);

        return intval($trailingDelta);
    }

    /**
     * @param float $supportLevel
     * @param float $range
     *
     * @return float
     */
    protected function adjustPriceNearSupport(float $supportLevel, float $range): float
    {
        return max($supportLevel - ($range * 0.1), 0.0);
    }

    /**
     * @param float $resistanceLevel
     * @param float $range
     *
     * @return float
     */
    protected function adjustPriceNearResistance(float $resistanceLevel, float $range): float
    {
        $adjusted = $resistanceLevel - ($range * 0.5);
        $currentPrice = $this->priceCurrent;

        if ($adjusted < $currentPrice) {
            $adjusted = $currentPrice * 1.01;
        }

        return $adjusted;
    }

    /**
     * @param array $orders
     *
     * @return float
     */
    protected function findMaxVolumeLevel(array $orders): float
    {
        $max = 0;
        $level = 0.0;

        foreach ($orders as $value) {
            if ($value[1] > $max) {
                $max = $value[1];
                $level = $value[0];
            }
        }

        return $level;
    }
}
