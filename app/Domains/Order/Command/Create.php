<?php declare(strict_types=1);

namespace App\Domains\Order\Command;

class Create extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'order:create:stop-limit {--user_id=} {--product_id=} {--wallet_id=} {--type=} {--side=} {--amount=} {--price=} {--limit=}';

    /**
     * @var string
     */
    protected $description = 'Create a Stop-Limit Order Data by {--user_id=} to {--product_id=} in {--wallet_id=} with {--type=} {--side=} {--amount=} {--price=} {--limit=}';

    /**
     * @return void
     */
    public function handle()
    {
        $this->checkOptions(['user_id', 'product_id', 'wallet_id', 'type', 'side', 'amount', 'price', 'limit']);

        $this->auth();
        $this->requestWithOptions();

        $this->factory()->action()->create($this->product());
    }
}
