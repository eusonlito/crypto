<?php declare(strict_types=1);

namespace App\Domains\CoreMaintenance\Command;

class OpcachePreload extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'core:maintenance:opcache:preload {--debug}';

    /**
     * @var string
     */
    protected $description = 'Preload Opcache';

    /**
     * @return void
     */
    public function handle(): void
    {
        $this->info('START');

        $response = $this->request();

        if ($this->option('debug')) {
            $this->info(json_decode($response, true));
        }

        $this->info('END');
    }

    /**
     * @return string
     */
    protected function request(): string
    {
        return file_get_contents(route('core-maintenance.opcache.preload'), false, stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]));
    }
}
