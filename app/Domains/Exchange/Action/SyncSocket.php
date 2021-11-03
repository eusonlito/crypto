<?php declare(strict_types=1);

namespace App\Domains\Exchange\Action;

use App\Domains\Exchange\Action\Traits\SyncSocket as SyncSocketTrait;
use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Platform\Service\Provider\ProviderApiFactory;
use App\Services\Platform\ApiFactoryAbstract;

class SyncSocket extends ActionAbstract
{
    use SyncSocketTrait;

    /**
     * @var \App\Services\Platform\ApiFactoryAbstract
     */
    protected ApiFactoryAbstract $api;

    /**
     * @param \App\Domains\Platform\Model\Platform $platform
     *
     * @return void
     */
    public function handle(PlatformModel $platform): void
    {
        $this->platform = $platform;

        $this->api();
        $this->reload();
        $this->read();
    }

    /**
     * @return void
     */
    protected function api(): void
    {
        $this->api = ProviderApiFactory::get($this->platform);
    }

    /**
     * @return void
     */
    protected function connect(): void
    {
        foreach ($this->api->exchanges() as $each) {
            $this->readValue($each);
        }

        sleep(10);
    }
}
