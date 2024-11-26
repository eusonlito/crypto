<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action;

use Illuminate\Support\Collection;
use App\Domains\Wallet\Model\Wallet as Model;
use App\Services\Command\Artisan;

class SellStopTrailingAiCheckAll extends ActionAbstract
{
    /**
     * @return void
     */
    public function handle(): void
    {
        $this->iterate();
    }

    /**
     * @return void
     */
    protected function iterate(): void
    {
        foreach ($this->list() as $id) {
            $this->command($id);
        }
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function list(): Collection
    {
        return Model::query()
            ->whereSellStopTrailingAi()
            ->pluck('id');
    }

    /**
     * @param int $id
     *
     * @return void
     */
    protected function command(int $id): void
    {
        Artisan::new(sprintf('wallet:sell-stop:trailing:ai --id=%s', $id))->logDaily()->exec();
    }
}
