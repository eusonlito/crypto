<?php declare(strict_types=1);

namespace App\Providers;

use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use App\Services\Database\Logger as LoggerDatabase;
use App\Services\Mail\Logger as LoggerMail;

class Debug extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        $this->queue();
        $this->logging();
        $this->dumper();
    }

    /**
     * @return void
     */
    protected function queue(): void
    {
        Queue::failing(static function (JobFailed $event) {
            Log::error($event->exception->getMessage());
        });
    }

    /**
     * @return void
     */
    protected function logging(): void
    {
        $this->loggingDatabase();
        $this->loggingMail();
    }

    /**
     * @return void
     */
    protected function loggingDatabase(): void
    {
        LoggerDatabase::listen();
    }

    /**
     * @return void
     */
    protected function loggingMail(): void
    {
        LoggerMail::listen();
    }

    /**
     * @return void
     */
    protected function dumper(): void
    {
        VarDumper::setHandler(static function ($var) {
            $cloner = new VarCloner();
            $cloner->setMaxItems(-1);

            $dumper = (PHP_SAPI === 'cli') ? new CliDumper() : new HtmlDumper();
            $dumper->dump($cloner->cloneVar($var));
        });
    }
}
