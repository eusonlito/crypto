<?php declare(strict_types=1);

namespace App\Domains\Translation\Command;

use App\Domains\Translation\Service\Fixed as FixedService;

class Fixed extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'translation:fixed';

    /**
     * @var string
     */
    protected $description = 'Search fixed texts on app and views';

    /**
     * @return void
     */
    public function handle()
    {
        foreach ((new FixedService())->scan() as $status) {
            $this->info($status);
        }
    }
}
