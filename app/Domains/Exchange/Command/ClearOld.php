<?php declare(strict_types=1);

namespace App\Domains\Exchange\Command;

class ClearOld extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'exchange:clear:old {--days=15}';

    /**
     * @var string
     */
    protected $description = 'Delete old Exchange values previous to {--days=15} days';

    /**
     * @return void
     */
    public function handle()
    {
        $this->factory()->action(['days' => $this->checkOption('days')])->clearOld();
    }
}
