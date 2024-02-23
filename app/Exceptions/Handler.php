<?php declare(strict_types=1);

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as HandlerVendor;
use Illuminate\Http\JsonResponse;
use Sentry\Laravel\Integration;
use App\Domains\Error\Controller\Index as ErrorController;
use App\Services\Request\Logger;

class Handler extends HandlerVendor
{
    /**
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Validation\ValidationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \App\Exceptions\GenericException::class,
    ];

    /**
     * @return void
     */
    public function register(): void
    {
        $this->reportable(static function (Throwable $e) {
            Integration::captureUnhandledException($e);
        });
    }

    /**
     * @param \Throwable $e
     *
     * @return void
     */
    public function report(Throwable $e): void
    {
        $this->reportParent($e);
        $this->reportRequest($e);
    }

    /**
     * @param \Throwable $e
     *
     * @return void
     */
    protected function reportParent(Throwable $e): void
    {
        parent::report($e);
    }

    /**
     * @param \Throwable $e
     *
     * @return void
     */
    protected function reportRequest(Throwable $e)
    {
        if (config('logging.channels.request.enabled')) {
            Logger::fromException(request(), $e);
        }
    }

    /**
     * @return array
     */
    protected function context(): array
    {
        return parent::context() + [
            'url' => request()->fullUrl(),
            'method' => request()->getMethod(),
        ];
    }

    /**
     * @param mixed $request
     * @param \Throwable $e
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function render($request, Throwable $e)
    {
        $e = Response::fromException($e);

        if ($request->ajax() || $request->expectsJson()) {
            return $this->renderJson($e);
        }

        if (config('app.debug')) {
            return parent::render($request, $e->getPrevious() ?: $e);
        }

        return app(ErrorController::class)($e);
    }

    /**
     * @param \Throwable $e
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function renderJson(Throwable $e): JsonResponse
    {
        return response()->json($this->renderJsonData($e), $e->getCode());
    }

    /**
     * @param \Throwable $e
     *
     * @return array
     */
    protected function renderJsonData(Throwable $e): array
    {
        return [
            'code' => $e->getCode(),
            'status' => $e->getStatus(),
            'message' => $e->getMessage(),
            'details' => $e->getDetails(),
        ];
    }
}
