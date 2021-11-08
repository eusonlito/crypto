<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action;

use App\Domains\Platform\Model\Platform as PlatformModel;

class SyncAll extends ActionAbstract
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
        foreach (PlatformModel::byUserId($this->auth->id)->withUserPivot($this->auth->id)->get() as $each) {
            $this->factory()->action()->sync($each);
        }
    }
}
