<?php declare(strict_types=1);

namespace App\Domains\Exchange\Action;

use App\Domains\Exchange\Action\Traits\SyncSocket as SyncSocketTrait;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Platform\Service\Provider\ProviderSocketFactory;
use App\Services\Platform\SocketAbstract;

class TickerSocket extends ActionAbstract
{
    use SyncSocketTrait;

    /**
     * @var ?\App\Services\Platform\SocketAbstract
     */
    protected ?SocketAbstract $websocket;

    /**
     * @param \App\Domains\Platform\Model\Platform $platform
     *
     * @return void
     */
    public function handle(PlatformModel $platform): void
    {
        if (empty($platform->enabled)) {
            return;
        }

        $this->platform = $platform;

        $this->websocket();

        if ($this->websocket === null) {
            return;
        }

        $this->reload();
        $this->read();
    }

    /**
     * @return void
     */
    protected function websocket(): void
    {
        $this->websocket = ProviderSocketFactory::get($this->platform, 'Ticker');
    }

    /**
     * @return void
     */
    protected function connect(): void
    {
        $this->websocket
            ->open()
            ->subscribe($this->products->pluck('code')->toArray())
            ->read(fn ($value) => $this->readValue($value));
    }
}
