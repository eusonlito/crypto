<?php declare(strict_types=1);

namespace App\Domains\Currency\Action;

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
        foreach (PlatformModel::query()->enabled()->get() as $each) {
            $this->factory()->action()->syncPlatform($each);
        }
    }
}
