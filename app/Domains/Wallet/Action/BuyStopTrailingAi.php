<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action;

use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Platform\Service\Provider\ProviderApiFactory;
use App\Domains\Product\Model\Product as ProductModel;
use App\Domains\Wallet\Model\Wallet as Model;
use App\Domains\Wallet\Action\Traits\DataBuyStop as DataBuyStopTrait;
use App\Domains\Wallet\Service\Logger\Action as ActionLogger;
use App\Services\Ai\ChatCompletions;
use App\Services\Platform\ApiFactoryAbstract;
use App\Services\Trader\Trader;

class BuyStopTrailingAi extends ActionAbstract
{
    use DataBuyStopTrait;

    /**
     * @var \App\Services\Platform\ApiFactoryAbstract
     */
    protected ApiFactoryAbstract $api;

    /**
     * @var \App\Domains\Platform\Model\Platform
     */
    protected PlatformModel $platform;

    /**
     * @var \App\Domains\Product\Model\Product
     */
    protected ProductModel $product;

    /**
     * @var array
     */
    protected array $candles;

    /**
     * @var array
     */
    protected array $orderBook;

    /**
     * @var array
     */
    protected array $trader;

    /**
     * @var array
     */
    protected array $values;

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function handle(): Model
    {
        if ($this->available() === false) {
            return $this->row;
        }

        $this->platform();
        $this->product();

        $this->logBefore();

        if ($this->executable() === false) {
            return $this->row;
        }

        $this->api();
        $this->candles();
        $this->orderBooks();
        $this->trader();

        if ($this->calculate() === false) {
            return $this->row;
        }

        $this->update();
        $this->logSuccess();

        return $this->row;
    }

    /**
     * @return bool
     */
    protected function available(): bool
    {
        return $this->row->buy_stop_ai
            && config('ai.openai.key');
    }

    /**
     * @return void
     */
    protected function platform(): void
    {
        $this->platform = $this->row->platform;
        $this->platform->userPivotLoad($this->auth);
    }

    /**
     * @return void
     */
    protected function product(): void
    {
        $this->product = $this->row->product;
        $this->product->setRelation('platform', $this->platform);
    }

    /**
     * @return bool
     */
    protected function executable(): bool
    {
        if ($this->executableStatus()) {
            return true;
        }

        $this->logNotExecutable();

        return false;
    }

    /**
     * @return bool
     */
    protected function executableStatus(): bool
    {
        return (bool)$this->platform->userPivot
            && $this->row->enabled
            && $this->row->crypto;
    }

    /**
     * @return void
     */
    protected function api(): void
    {
        $this->api = ProviderApiFactory::get($this->platform);
    }

    /**
     * @return void
     */
    protected function candles(): void
    {
        $this->candle('5minute');
        $this->candle('15minute');
    }

    /**
     * @param string $interval
     *
     * @return void
     */
    protected function candle(string $interval): void
    {
        $this->candles[$interval] = $this->api->candles(
            $this->product->code,
            $interval,
            date('Y-m-d H:i:s', strtotime('-3 day'))
        );
    }

    /**
     * @return void
     */
    protected function orderBooks(): void
    {
        $this->orderBook(10);
        $this->orderBook(20);
    }

    /**
     * @param int $limit
     *
     * @return void
     */
    protected function orderBook(int $limit): void
    {
        $this->orderBook[$limit] = $this->api->orderBook($this->product->code, $limit);
    }

    /**
     * @return void
     */
    protected function trader(): void
    {
        foreach ($this->candles as $interval => $values) {
            $this->trader['indicators'][$interval] = Trader::all($values->pluck('close')->all());
        }

        foreach ($this->orderBook as $limit => $values) {
            $bids = array_sum(array_column($values->bids, '1'));
            $asks = array_sum(array_column($values->asks, '1'));
            $imbalance = ($bids - $asks) / ($bids + $asks) * 100;

            $this->trader['book'][$limit]['bids'] = round($bids, 3);
            $this->trader['book'][$limit]['asks'] = round($asks, 3);
            $this->trader['book'][$limit]['imbalance'] = round($imbalance, 2);
        }
    }

    /**
     * @return bool
     */
    protected function calculate(): bool
    {
        $json = strval($this->calculateRequest());

        if (preg_match('/(\{.*\})/', $json, $matches) === 0) {
            return false;
        }

        $json = json_decode($matches[1], true);

        if (empty($json['limit']) || empty($json['stop'])) {
            return false;
        }

        $this->values = $json;

        return true;
    }

    /**
     * @return ?string
     */
    protected function calculateRequest(): ?string
    {
        return ChatCompletions::new()
            ->setMessage('user', $this->calculateRequestMessage())
            ->send()
            ->getContent();
    }

    /**
     * @return string
     */
    protected function calculateRequestMessage(): string
    {
        return trim(strtr(<<<'EOF'
        As an expert financial assistant, analyze the following indicators and data for the :symbol trading pair to recommend the optimal percentages for 'limit' and 'stop' that minimize risk in rapid intraday buy trading. Please adhere to the following requirements:

        - Trades should be executed within a short time frame.
        - Buying and selling can to occur as quickly as possible.

        Use the data provided below to calculate and recommend the optimal 'limit' and 'stop' percentages to a trailing stop buy operation.

        Return your recommendation in JSON format with the keys **"limit"** and **"stop"**, ensuring they comply with the specified requirements. Do not include any additional text or explanations.

        **Current Price**
        - :current_exchange

        **Technical Indicators (5-minute interval):**
        - RSI (5m): :indicators_5minute_rsi
        - SMA 24 (5m): :indicators_5minute_sma_50
        - SMA 48 (5m): :indicators_5minute_sma_200
        - MACD (5m): :indicators_5minute_macd
        - MACD Signal (5m): :indicators_5minute_macd_signal
        - MACD Histogram (5m): :indicators_5minute_macd_hist
        - Volatility (5m): :indicators_5minute_volatility

        **Technical Indicators (15-minute interval):**
        - RSI (15m): :indicators_15minute_rsi
        - SMA 24 (15m): :indicators_15minute_sma_50
        - SMA 48 (15m): :indicators_15minute_sma_200
        - MACD (15m): :indicators_15minute_macd
        - MACD Signal (15m): :indicators_15minute_macd_signal
        - MACD Histogram (15m): :indicators_15minute_macd_hist
        - Volatility (15m): :indicators_15minute_volatility

        **Order Book Data:**

        ***10-Level Depth:***
        - Total bid volume (10 levels): :book_10_bids
        - Total ask volume (10 levels): :book_10_asks
        - Volume imbalance between bids and asks (10 levels): :book_10_imbalance%

        ***20-Level Depth:***
        - Total bid volume (20 levels): :book_20_bids
        - Total ask volume (20 levels): :book_20_asks
        - Volume imbalance between bids and asks (20 levels): :book_20_imbalance%

        **Important Notes:**

        - 'limit' is the percentage decrease in price that triggers the activation of the buy order and must be at least 5. If the market seems to be fluctuating wildly, or if there has been a recent rapid increase in prices, you may want to set it as high as possible.
        - 'stop' is the percentage rebound rise in price required for the order execution after the 'limit' has been reached.
        - 'limit' - 'stop' operation must be at least >= 2.5 to get a minimum 2.5% percent difference.

        Provide only a valid JSON output without any format or additional comments.

        Examples:

        {"limit": 4.5, "stop": 1.5}
        {"limit": 5, "stop": 2}
        {"limit": 6, "stop": 3}
        EOF, [
            ':symbol' => $this->product->code,
            ':current_exchange' => $this->row->current_exchange,
            ':indicators_5minute_rsi' => $this->trader['indicators']['5minute']['rsi'],
            ':indicators_5minute_sma_50' => $this->trader['indicators']['5minute']['sma_50'],
            ':indicators_5minute_sma_200' => $this->trader['indicators']['5minute']['sma_200'],
            ':indicators_5minute_macd' => $this->trader['indicators']['5minute']['macd'],
            ':indicators_5minute_macd_signal' => $this->trader['indicators']['5minute']['macd_signal'],
            ':indicators_5minute_macd_hist' => $this->trader['indicators']['5minute']['macd_hist'],
            ':indicators_5minute_volatility' => $this->trader['indicators']['5minute']['volatility'],
            ':indicators_15minute_rsi' => $this->trader['indicators']['15minute']['rsi'],
            ':indicators_15minute_sma_50' => $this->trader['indicators']['15minute']['sma_50'],
            ':indicators_15minute_sma_200' => $this->trader['indicators']['15minute']['sma_200'],
            ':indicators_15minute_macd' => $this->trader['indicators']['15minute']['macd'],
            ':indicators_15minute_macd_signal' => $this->trader['indicators']['15minute']['macd_signal'],
            ':indicators_15minute_macd_hist' => $this->trader['indicators']['15minute']['macd_hist'],
            ':indicators_15minute_volatility' => $this->trader['indicators']['15minute']['volatility'],
            ':book_10_bids' => $this->trader['book'][10]['bids'],
            ':book_10_asks' => $this->trader['book'][10]['asks'],
            ':book_10_imbalance' => $this->trader['book'][10]['imbalance'],
            ':book_20_bids' => $this->trader['book'][20]['bids'],
            ':book_20_asks' => $this->trader['book'][20]['asks'],
            ':book_20_imbalance' => $this->trader['book'][20]['imbalance'],
        ]));
    }

    /**
     * @return void
     */
    protected function update(): void
    {
        $this->updateData();
        $this->updateRow();
    }

    /**
     * @return void
     */
    protected function updateData(): void
    {
        $this->data = [
            'buy_stop_percent' => $this->row->buy_stop_percent,
            'buy_stop_reference' => $this->row->buy_stop_reference,
            'buy_stop_max_percent' => $this->values['limit'],
            'buy_stop_min_percent' => $this->values['stop'],
            'buy_stop_max_at' => $this->row->buy_stop_max_at,
            'buy_stop_min_at' => $this->row->buy_stop_min_at,
        ];

        $this->dataBuyStop();
    }

    /**
     * @return void
     */
    protected function updateRow(): void
    {
        $this->row->buy_stop_max_exchange = $this->data['buy_stop_max_exchange'];
        $this->row->buy_stop_max_value = $this->data['buy_stop_max_value'];
        $this->row->buy_stop_max_percent = $this->data['buy_stop_max_percent'];

        $this->row->buy_stop_min_exchange = $this->data['buy_stop_min_exchange'];
        $this->row->buy_stop_min_value = $this->data['buy_stop_min_value'];
        $this->row->buy_stop_min_percent = $this->data['buy_stop_min_percent'];

        $this->row->save();
    }

    /**
     * @return void
     */
    protected function logBefore(): void
    {
        $this->log('info', ['detail' => __FUNCTION__]);
    }

    /**
     * @return void
     */
    protected function logNotExecutable(): void
    {
        $this->log('error', ['detail' => __FUNCTION__]);
    }

    /**
     * @return void
     */
    protected function logSuccess(): void
    {
        $this->log('info', ['detail' => __FUNCTION__]);
    }

    /**
     * @param string $status
     * @param array $data = []
     *
     * @return void
     */
    protected function log(string $status, array $data = []): void
    {
        ActionLogger::set($status, 'buy-stop-trailing-ai', $this->row, $data);
    }
}
