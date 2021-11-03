<?php declare(strict_types=1);

namespace App\Domains\Product\Action;

use App\Domains\Product\Model\Product as Model;
use App\Domains\Product\Model\ProductUser as ProductUserModel;

class Favorite extends ActionAbstract
{
    /**
     * @var bool
     */
    protected bool $related;

    /**
     * @return ?\App\Domains\Product\Model\Product
     */
    public function handle(): ?Model
    {
        $this->related();
        $this->update();

        return $this->related ? null : $this->row;
    }

    /**
     * @return void
     */
    protected function related(): void
    {
        $this->related = (bool)$this->row->userPivot()->byUserId($this->auth->id)->count();
    }

    /**
     * @return void
     */
    protected function update(): void
    {
        if ($this->related) {
            $this->delete();
        } else {
            $this->create();
        }
    }

    /**
     * @return void
     */
    protected function delete(): void
    {
        $this->row->userPivot()->byUserId($this->auth->id)->delete();
    }

    /**
     * @return void
     */
    protected function create(): void
    {
        ProductUserModel::insert([
            'favorite' => true,
            'platform_id' => $this->row->platform_id,
            'product_id' => $this->row->id,
            'user_id' => $this->auth->id,
        ]);
    }
}
