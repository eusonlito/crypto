<?php declare(strict_types=1);

namespace App\Domains\Dashboard\Controller;

use App\Domains\Dashboard\Service\Controller\Index as IndexService;

class Index extends ControllerAbstract
{
    /**
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function __invoke()
    {
        if ($this->hasWallets() === false) {
            return redirect()->route('dashboard.start');
        }

        $this->meta('title', __('dashboard-index.meta-title'));

        return $this->page('dashboard.index', $this->data());
    }

    /**
     * @return array
     */
    protected function data(): array
    {
        return IndexService::new($this->auth, $this->request)->data() + [
            'filters' => $this->request->input(),
            'investment' => $this->auth->investment,
        ];
    }
}
