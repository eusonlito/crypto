<?php declare(strict_types=1);

namespace App\Domains\Language\Action;

use App\Domains\Language\Model\Language as Model;

class Request extends ActionAbstract
{
    /**
     * @var string
     */
    protected string $iso;

    /**
     * @return void
     */
    public function handle(): void
    {
        $this->iso();
        $this->row();
        $this->set();
    }

    /**
     * @return void
     */
    public function iso(): void
    {
        $this->iso = preg_split('/[^a-zA-Z]/', (string)$this->request->header('Accept-Language'), 2)[0] ?? config('app.locale');
    }

    /**
     * @return void
     */
    protected function row(): void
    {
        $this->row = cache()
            ->tags('language')
            ->remember('language|'.$this->iso, 3600, fn () => $this->rowCached());
    }

    /**
     * @return \App\Domains\Language\Model\Language
     */
    protected function rowCached(): Model
    {
        return $this->rowCachedIso() ?: $this->rowCachedDefault();
    }

    /**
     * @return ?\App\Domains\Language\Model\Language
     */
    protected function rowCachedIso(): ?Model
    {
        return Model::enabled()->where('iso', $this->iso)->first();
    }

    /**
     * @return \App\Domains\Language\Model\Language
     */
    protected function rowCachedDefault(): Model
    {
        return Model::enabled()->where('default', 1)->first();
    }

    /**
     * @return void
     */
    protected function set(): void
    {
        app()->setLocale($this->row->iso);
        app()->singleton('language', fn () => $this->row);
    }
}
