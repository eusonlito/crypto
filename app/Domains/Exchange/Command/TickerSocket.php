<?php declare(strict_types=1);

namespace App\Domains\Exchange\Command;

class TickerSocket extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'exchange:ticker:socket {--platform_id=}';

    /**
     * @var string
     */
    protected $description = 'Read Ticker Socket Feed form {--platform_id=}';

    /**
     * @return void
     */
    public function handle()
    {
        $this->factory()->action()->tickerSocket($this->platform());
    }
}
