<?php declare(strict_types=1);

namespace App\Domains\Core\Controller;

use Throwable;
use Illuminate\Http\Response;
use Eusonlito\LaravelMeta\Facade as Meta;
use App\Domains\Core\Model\ModelAbstract;
use App\Services\Html\Alert;
use App\Services\Request\Response as ResponseService;

abstract class ControllerWebAbstract extends ControllerAbstract
{
    /**
     * @return void
     */
    protected function init(): void
    {
        $this->initDefault();
        $this->initCustom();
    }

    /**
     * @return void
     */
    protected function initDefault(): void
    {
        $this->initViewShare();
    }

    /**
     * @return void
     */
    protected function initViewShare(): void
    {
        view()->share([
            'ROUTE' => $this->request->route()?->getName() ?: '',
            'AUTH' => $this->auth,
            'REQUEST' => $this->request,
        ]);
    }

    /**
     * @return void
     */
    protected function initCustom(): void
    {
    }

    /**
     * @param string $name
     * @param ?string $value
     *
     * @return void
     */
    protected function meta(string $name, ?string $value): void
    {
        if ($value) {
            Meta::set($name, $value);
        }
    }

    /**
     * @param string $page
     * @param array $data = []
     * @param ?int $status = null
     *
     * @return \Illuminate\Http\Response
     */
    protected function page(string $page, array $data = [], ?int $status = null): Response
    {
        return response()->view('domains.'.$page, $data, ResponseService::status($status));
    }

    /**
     * @param array $data = []
     * @param ?\App\Domains\Core\Model\ModelAbstract $row = null
     *
     * @return void
     */
    final protected function requestMergeWithRow(array $data = [], ?ModelAbstract $row = null): void
    {
        $this->request->merge($this->request->input() + $data + $this->requestMergeWithRowAsArray($row));
    }

    /**
     * @param ?\App\Domains\Core\Model\ModelAbstract $row
     *
     * @return array
     */
    final protected function requestMergeWithRowAsArray(?ModelAbstract $row): array
    {
        if ($row) {
            return $row->toArray();
        }

        if (isset($this->row)) {
            return $this->row->toArray();
        }

        return [];
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    final protected function actionIfExists(string $name): mixed
    {
        if ($this->request->input('_action') !== $name) {
            return null;
        }

        return call_user_func_array([$this, 'actionCall'], func_get_args());
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    final protected function actionPost(string $name): mixed
    {
        if ($this->request->isMethod('post') === false) {
            return null;
        }

        return $this->actionIfExists($name, ...func_get_args());
    }

    /**
     * @param \Throwable $e
     *
     * @return mixed
     */
    protected function actionException(Throwable $e): mixed
    {
        parent::actionException($e);

        return Alert::exception($this->request, $e);
    }

    /**
     * @param string $status
     * @param string $message
     *
     * @return mixed
     */
    final protected function sessionMessage(string $status, string $message): mixed
    {
        return Alert::{$status}($message);
    }
}
