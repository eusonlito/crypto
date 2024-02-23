<?php declare(strict_types=1);

namespace App\Services\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Mail
{
    /**
     * @return \Monolog\Logger
     */
    public function __invoke(): Logger
    {
        return $this->logger();
    }

    /**
     * @return \Monolog\Handler\StreamHandler
     */
    public function handler(): StreamHandler
    {
        return new StreamHandler($this->file(), 'DEBUG');
    }

    /**
     * @return string
     */
    protected function file(): string
    {
        return storage_path('logs/mail/'.$this->path().'.log');
    }

    /**
     * @return string
     */
    protected function path(): string
    {
        return date('Y/m/d/H-i-s').'-'.microtime(true);
    }

    /**
     * @return \Monolog\Logger
     */
    public function logger(): Logger
    {
        $logger = new Logger('mail');
        $logger->pushHandler($this->handler());

        return $logger;
    }
}
