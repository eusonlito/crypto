<?php declare(strict_types=1);

namespace App\Domains\Exchange\Controller;

use App\Domains\Product\Model\Product as ProductModel;
use App\Exceptions\NotFoundException;

class Detail extends ControllerAbstract
{
    /**
     * @var \App\Domains\Product\Model\Product
     */
    protected ProductModel $product;

    /**
     * @var int
     */
    protected int $time;

    /**
     * @param int $product_id
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function __invoke(int $product_id)
    {
        $this->time();
        $this->product($product_id);

        $this->title();

        $prices = $this->product->exchanges->pluck('exchange');

        return $this->page('exchange.detail', [
            'product' => $this->product,
            'platform' => $this->product->platform,
            'time' => $this->time,
            'list' => $this->product->exchanges,
            'prices' => $prices,
            'max' => $prices->max(),
            'min' => $prices->min(),
            'first' => $prices->first(),
            'last' => $prices->last(),
        ]);
    }

    /**
     * @return void
     */
    protected function time(): void
    {
        $this->time = (int)$this->auth->preference('exchange-detail-time', $this->request->input('time'), 60);
    }

    /**
     * @param int $product_id
     *
     * @return void
     */
    protected function product(int $product_id): void
    {
        $this->product = ProductModel::byId($product_id)
            ->withExchangesChart(...$this->productExchangesData())
            ->firstOr(static function () {
                throw new NotFoundException(__('exchange.error.product-not-found'));
            });
    }

    /**
     * @return array
     */
    protected function productExchangesData(): array
    {
        return [
            $this->time,
            $this->request->input('start_at'),
            $this->request->input('end_at'),
            (bool)$this->request->input('detail'),
        ];
    }

    /**
     * @return void
     */
    protected function title(): void
    {
        $title = $this->product->title();

        if ($last = $this->product->exchanges->last()) {
            $title .= ' - '.helper()->money($last->exchange);
        }

        $this->meta('title', $title);
    }
}
