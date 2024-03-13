<?php declare(strict_types=1);

namespace App\Domains\Order\Model\Builder;

use App\Domains\Order\Model\Order as Model;
use App\Domains\Product\Model\Product as ProductModel;
use App\Domains\Core\Model\Builder\BuilderAbstract;
use App\Domains\Wallet\Model\Wallet as WalletModel;

class Order extends BuilderAbstract
{
    /**
     * @param array $codes
     *
     * @return self
     */
    public function byCodes(array $codes): self
    {
        return $this->whereIn('code', $codes);
    }

    /**
     * @param string $date
     *
     * @return self
     */
    public function byCreatedAtStart(string $date): self
    {
        return $this->where('created_at', '>=', $date);
    }

    /**
     * @param string $date
     *
     * @return self
     */
    public function byCreatedAtEnd(string $date): self
    {
        return $this->where('created_at', '<=', $date);
    }

    /**
     * @param int $platform_id
     *
     * @return self
     */
    public function byPlatformId(int $platform_id): self
    {
        return $this->where('platform_id', $platform_id);
    }

    /**
     * @param int $product_id
     *
     * @return self
     */
    public function byProductId(int $product_id): self
    {
        return $this->where('product_id', $product_id);
    }

    /**
     * @param array $product_ids
     *
     * @return self
     */
    public function byProductIds(array $product_ids): self
    {
        return $this->whereIntegerInRaw('product_id', $product_ids);
    }

    /**
     * @param string $search
     *
     * @return self
     */
    public function byProductSearch(string $search): self
    {
        return $this->whereIn('product_id', ProductModel::query()->select('id')->searchLike(['code', 'name'], $search));
    }

    /**
     * @param int $wallet_id
     *
     * @return self
     */
    public function byProductWalletId(int $wallet_id): self
    {
        return $this->whereIn('product_id', WalletModel::query()->select('product_id')->byId($wallet_id));
    }

    /**
     * @param string $side
     *
     * @return self
     */
    public function bySide(string $side): self
    {
        return $this->where('side', $side);
    }

    /**
     * @param string $side
     *
     * @return self
     */
    public function bySideNot(string $side): self
    {
        return $this->whereNot('side', $side);
    }

    /**
     * @param string $status
     *
     * @return self
     */
    public function byStatus(string $status): self
    {
        return $this->where('status', $status);
    }

    /**
     * @param string $type
     *
     * @return self
     */
    public function byType(string $type): self
    {
        return $this->where('type', $type);
    }

    /**
     * @param int $wallet_id
     *
     * @return self
     */
    public function byWalletId(int $wallet_id): self
    {
        return $this->where('wallet_id', $wallet_id);
    }

    /**
     * @param \App\Domains\Order\Model\Order $row
     *
     * @return self
     */
    public function lastSame(Model $row): self
    {
        return $this->lastSameWalletIdSideAndType($row->wallet_id, $row->side, $row->type);
    }

    /**
     * @param int $wallet_id
     * @param string $side
     * @param string $type
     *
     * @return self
     */
    public function lastSameWalletIdSideAndType(int $wallet_id, string $side, string $type): self
    {
        $query = Model::query()
            ->select('id')
            ->byWalletId($wallet_id)
            ->byType($type)
            ->bySideNot($side)
            ->whereFilled()
            ->orderByLast()
            ->limit(1);

        return $this->byWalletId($wallet_id)
            ->byType($type)
            ->bySide($side)
            ->whereFilled()
            ->where('id', '>=', $query);
    }

    /**
     * @param bool $custom = true
     *
     * @return self
     */
    public function whereCustom(bool $custom = true): self
    {
        return $this->where('custom', $custom);
    }

    /**
     * @param bool $filled = true
     *
     * @return self
     */
    public function whereFilled(bool $filled = true): self
    {
        return $this->where('filled', $filled);
    }

    /**
     * @return self
     */
    public function withProduct(): self
    {
        return $this->with('product');
    }

    /**
     * @param bool $crypto = true
     *
     * @return self
     */
    public function whereProductCrypto(bool $crypto = true): self
    {
        return $this->whereIn('product_id', ProductModel::query()->select('id')->whereCrypto($crypto));
    }

    /**
     * @return self
     */
    public function withProductExchange(): self
    {
        return $this->with(['product' => static fn ($q) => $q->with(['exchange'])]);
    }

    /**
     * @return self
     */
    public function withRelations(): self
    {
        return $this->with(['platform', 'product']);
    }

    /**
     * @return self
     */
    public function withWallet(): self
    {
        return $this->with(['wallet']);
    }

    /**
     * @return self
     */
    public function groupByProductId(): self
    {
        return $this->groupBy(['product_id']);
    }

    /**
     * @return self
     */
    public function orderByDate(): self
    {
        return $this->orderBy('created_at', 'ASC');
    }

    /**
     * @return self
     */
    public function list(): self
    {
        return $this->with(['platform', 'product'])->orderBy('created_at', 'DESC');
    }
}
