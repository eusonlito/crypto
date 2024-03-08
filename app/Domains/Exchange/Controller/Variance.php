<?php declare(strict_types=1);

namespace App\Domains\Exchange\Controller;

use Illuminate\Http\Response;
use App\Domains\Exchange\Service\Controller\Variance as ControllerService;

class Variance extends ControllerAbstract
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function __invoke(): Response
    {
        $this->meta('title', __('exchange-variance.meta-title'));

        return $this->page('exchange.variance', $this->data());
    }

    /**
     * @return array
     */
    protected function data(): array
    {
        return ControllerService::new($this->request, $this->auth)->data();
    }
}
