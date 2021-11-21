<?php declare(strict_types=1);

namespace App\Domains\Product\Command;

class FiatPlatform extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'product:fiat:platform {--platform_id=}';

    /**
     * @var string
     */
    protected $description = 'Added FIAT Products By {--platform_id=}';

    /**
     * @return void
     */
    public function handle()
    {
        $this->platform();
        $this->factory()->action()->fiat($this->platform);
    }
}
