<?php declare(strict_types=1);

namespace App\Domains\Dashboard\Action;

class Sync extends ActionAbstract
{
    /**
     * @return void
     */
    public function handle(): void
    {
        if ($this->data['platform_id']) {
            $this->factory()->action()->syncPlatform();
        } else {
            $this->factory()->action()->syncAll();
        }
    }
}
