<?php declare(strict_types=1);

namespace App\Domains\Order\Action;

use Illuminate\Support\Collection;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Product\Model\Product as ProductModel;

class SyncByWallets extends ActionAbstract
{
    /**
     * @var \App\Domains\Platform\Model\Platform
     */
    protected PlatformModel $platform;

    /**
     * @param \App\Domains\Platform\Model\Platform $platform
     *
     * @return void
     */
    public function handle(PlatformModel $platform): void
    {
        $this->platform = $platform;

        if ($this->available() === false) {
            return;
        }

        $this->save();
    }

    /**
     * @return bool
     */
    protected function available(): bool
    {
        return $this->platform->userPivotLoad($this->auth);
    }

    /**
     * @return void
     */
    protected function save(): void
    {
        $this->factory()
            ->action()
            ->syncByProducts($this->saveProducts());
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function saveProducts(): Collection
    {
        return ProductModel::query()
            ->byPlatformId($this->platform->id)
            ->whereWalletsByUserId($this->auth->id)
            ->get();
    }
}
