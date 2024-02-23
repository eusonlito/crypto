<?php declare(strict_types=1);

namespace App\Domains\Product\Model\Builder;

use App\Domains\Core\Model\Builder\BuilderAbstract;

class ProductUser extends BuilderAbstract
{
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
     * @param int $user_id
     *
     * @return self
     */
    public function byUserId(int $user_id): self
    {
        return $this->where('user_id', $user_id);
    }

    /**
     * @param bool $favorite = true
     *
     * @return self
     */
    public function whereFavorite(bool $favorite = true): self
    {
        return $this->where('favorite', $favorite);
    }
}
