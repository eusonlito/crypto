<?php declare(strict_types=1);

namespace App\Services\Mail;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\Event;

class Logger
{
    /**
     * @return self
     */
    public static function new(): self
    {
        return new static(...func_get_args());
    }

    /**
     * @return void
     */
    public function listen(): void
    {
        if ($this->enabled() !== true) {
            return;
        }

        Event::listen(MessageSending::class, $this->store(...));
    }

    /**
     * @return bool
     */
    protected function enabled(): bool
    {
        return config('logging.channels.mail.enabled') === true;
    }

    /**
     * @param \Illuminate\Mail\Events\MessageSending $event
     *
     * @return void
     */
    protected function store(MessageSending $event): void
    {
        $file = $this->file();

        helper()->mkdir($file, true);

        file_put_contents($file, $this->contents($event));
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
     * @param \Illuminate\Mail\Events\MessageSending $event
     *
     * @return string
     */
    protected function contents(MessageSending $event): string
    {
        $raw = $event->message->toString();

        return str_contains($raw, 'Content-Transfer-Encoding: quoted-printable')
            ? $this->contentsQuotedPrintable($raw)
            : $raw;
    }

    /**
     * @param string $raw
     *
     * @return string
     */
    protected function contentsQuotedPrintable(string $raw): string
    {
        [$headers, $content] = explode("\r\n\r\n", $raw, 2);

        return implode("\r\n\r\n", [
            $headers,
            quoted_printable_decode($content),
        ]);
    }
}
