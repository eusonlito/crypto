<?php declare(strict_types=1);

namespace App\Domains\Dashboard\Controller;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class Start extends ControllerAbstract
{
    /**
     * @var array
     */
    protected array $filters;

    /**
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function __invoke(): Response|RedirectResponse
    {
        if ($this->hasWallets()) {
            return redirect()->route('dashboard.index');
        }

        $this->meta('title', __('dashboard-start.meta-title'));

        return $this->page('dashboard.start');
    }
}
