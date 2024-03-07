<?php declare(strict_types=1);

namespace App\Domains\Exchange\Controller;

use Illuminate\Http\Response;
use App\Domains\Exchange\Service\Controller\Index as ControllerService;

class Index extends ControllerAbstract
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function __invoke(): Response
    {
        $this->meta('title', __('exchange-index.meta-title'));

        return $this->page('exchange.index', $this->data());
    }

    /**
     * @return array
     */
    protected function data(): array
    {
        return ControllerService::new($this->request, $this->auth)->data();
    }
}
