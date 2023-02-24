<?php declare(strict_types=1);

namespace App\Services\Command;

use App\Services\Filesystem\Directory;

class Artisan
{
    /**
     * @param string $command
     *
     * @return void
     */
    public static function exec(string $command): void
    {
        $log = static::logFile($command);
        $command = static::command($command, $log);

        static::logWrite($log, $command);

        static::launch($command);
    }

    /**
     * @param string $command
     * @param string $log
     *
     * @return string
     */
    protected static function command(string $command, string $log): string
    {
        return 'nohup '.PHP_BINARY.' '.base_path('artisan').' '.$command.' >> '.$log.' 2>&1 &';
    }

    /**
     * @param string $command
     *
     * @return void
     */
    protected static function launch(string $command): void
    {
        exec($command);
    }

    /**
     * @param string $command
     *
     * @return string
     */
    protected static function logFile(string $command): string
    {
        $file = storage_path('logs/artisan/'.date('Y-m-d/H-i-s').'-'.uniqid().'-'.str_slug($command).'.log');

        Directory::create($file, true);

        return $file;
    }

    /**
     * @param string $file
     * @param string $message
     *
     * @return void
     */
    protected static function logWrite(string $file, string $message): void
    {
        file_put_contents($file, $message."\n\n", LOCK_EX);
    }
}
