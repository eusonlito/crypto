<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action;

use App\Domains\Wallet\Model\Wallet as Model;
use App\Domains\Core\Action\ActionFactoryAbstract;

class ActionFactory extends ActionFactoryAbstract
{
    /**
     * @var ?\App\Domains\Wallet\Model\Wallet
     */
    protected ?Model $row;

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function buyStop(): Model
    {
        return $this->actionHandle(BuyStop::class);
    }

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function buyStopMax(): Model
    {
        return $this->actionHandle(BuyStopMax::class);
    }

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function buyStopMin(): Model
    {
        return $this->actionHandle(BuyStopMin::class);
    }

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function buyStopTrailingAi(): Model
    {
        return $this->actionHandle(BuyStopTrailingAi::class);
    }

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function buyStopTrailingAiCheck(): Model
    {
        return $this->actionHandle(BuyStopTrailingAiCheck::class);
    }

    /**
     * @return void
     */
    public function buyStopTrailingAiCheckAll(): void
    {
        $this->actionHandle(BuyStopTrailingAiCheckAll::class);
    }

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function buyStopTrailingCheck(): Model
    {
        return $this->actionHandle(BuyStopTrailingCheck::class);
    }

    /**
     * @return void
     */
    public function buyStopTrailingCheckAll(): void
    {
        $this->actionHandle(BuyStopTrailingCheckAll::class);
    }

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function buyStopTrailingCreate(): Model
    {
        return $this->actionHandle(BuyStopTrailingCreate::class);
    }

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function buyStopTrailingFollow(): Model
    {
        return $this->actionHandle(BuyStopTrailingFollow::class);
    }

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function create(): Model
    {
        return $this->actionHandle(Create::class, $this->validate()->create());
    }

    /**
     * @return void
     */
    public function delete(): void
    {
        $this->actionHandle(Delete::class);
    }

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function sellStop(): Model
    {
        return $this->actionHandle(SellStop::class);
    }

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function sellStopLoss(): Model
    {
        return $this->actionHandle(SellStopLoss::class);
    }

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function sellStopMax(): Model
    {
        return $this->actionHandle(SellStopMax::class);
    }

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function sellStopTrailingAi(): Model
    {
        return $this->actionHandle(SellStopTrailingAi::class);
    }

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function sellStopTrailingAiCheck(): Model
    {
        return $this->actionHandle(SellStopTrailingAiCheck::class);
    }

    /**
     * @return void
     */
    public function sellStopTrailingAiCheckAll(): void
    {
        $this->actionHandle(SellStopTrailingAiCheckAll::class);
    }

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function sellStopTrailingCheck(): Model
    {
        return $this->actionHandle(SellStopTrailingCheck::class);
    }

    /**
     * @return void
     */
    public function sellStopTrailingCheckAll(): void
    {
        $this->actionHandle(SellStopTrailingCheckAll::class);
    }

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function sellStopTrailingCreate(): Model
    {
        return $this->actionHandle(SellStopTrailingCreate::class);
    }

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function sellStopMin(): Model
    {
        return $this->actionHandle(SellStopMin::class);
    }

    /**
     * @return void
     */
    public function syncPlatform(): void
    {
        $this->actionHandle(SyncPlatform::class, [], ...func_get_args());
    }

    /**
     * @return void
     */
    public function syncAll(): void
    {
        $this->actionHandle(SyncAll::class);
    }

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function syncOne(): Model
    {
        return $this->actionHandle(SyncOne::class);
    }

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function update(): Model
    {
        return $this->actionHandle(Update::class, $this->validate()->update());
    }

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function updateBoolean(): Model
    {
        return $this->actionHandle(UpdateBoolean::class, $this->validate()->updateBoolean());
    }

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function updateBuyStop(): Model
    {
        return $this->actionHandle(UpdateBuyStop::class, $this->validate()->updateBuyStop());
    }

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function updateColumn(): Model
    {
        return $this->actionHandle(UpdateColumn::class, $this->validate()->updateColumn());
    }

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function updateSellMarket(): Model
    {
        return $this->actionHandle(UpdateSellMarket::class, $this->validate()->updateSellMarket());
    }

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function updateSellStop(): Model
    {
        return $this->actionHandle(UpdateSellStop::class, $this->validate()->updateSellStop());
    }

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function updateSellStopLoss(): Model
    {
        return $this->actionHandle(UpdateSellStopLoss::class, $this->validate()->updateSellStopLoss());
    }

    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function updateSync(): Model
    {
        return $this->actionHandle(UpdateSync::class);
    }
}
