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
        throw new \Exception('Error!');
        if ($this->hasWallets() === false) {
            return redirect()->route('dashboard.start');
        }

        $this->filters();

        $this->meta('title', __('dashboard-index.meta-title'));

        return $this->page('dashboard.index', $this->data());
    }

    /**
     * @return void
     */
    protected function filters(): void
    {
        $this->request->merge([
            'time' => (int)$this->auth->preference('dashboard-time', $this->request->input('time'), 60),
            'references' => (bool)$this->auth->preference('dashboard-references', $this->request->input('references'), true),
        ]);
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
