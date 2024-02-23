<?php declare(strict_types=1);

namespace App\Domains\Core\Action;

use Closure;
use Throwable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Domains\Core\Model\ModelAbstract;
use App\Domains\Core\Traits\Factory;
use App\Exceptions\NotFoundException;
use App\Exceptions\ValidatorException;

abstract class ActionAbstract
{
    use Factory;

    /**
     * @param ?\Illuminate\Http\Request $request = null
     * @param ?\Illuminate\Contracts\Auth\Authenticatable $auth = null
     * @param ?\App\Domains\Core\Model\ModelAbstract $row = null
     * @param array $data = []
     *
     * @return self
     */
    final public function __construct(?Request $request = null, ?Authenticatable $auth = null, ?ModelAbstract $row = null, array $data = [])
    {
        $this->request = $request;
        $this->auth = $auth;
        $this->data = $data;

        if (property_exists($this, 'row')) {
            $this->row = $row;
        }
    }

    /**
     * @param \Closure $closure
     * @param ?\Closure $rollback = null
     *
     * @return mixed
     */
    final protected function transaction(Closure $closure, ?Closure $rollback = null): mixed
    {
        try {
            return $this->connection()->transaction($closure);
        } catch (Throwable $e) {
            if ($rollback) {
                return $rollback($e);
            }

            throw $e;
        }
    }

    /**
     * @param \Closure $closure
     * @param int $limit
     * @param int $wait
     *
     * @return mixed
     */
    final protected function try(Closure $closure, int $limit, int $wait): mixed
    {
        $try = 1;

        do {
            try {
                return $closure();
            } catch (Throwable $e) {
                $this->tryError($e, $limit, $wait, $try);
            }
        } while ($limit > $try++);

        throw $e;
    }

    /**
     * @param \Throwable $e
     * @param int $limit
     * @param int $wait
     * @param int $try
     *
     * @return void
     */
    final protected function tryError(Throwable $e, int $limit, int $wait, int $try): void
    {
        Log::error(sprintf('tryError - Limit %s - Wait %s - Try %s', $limit, $wait, $try));
        Log::error($e);

        sleep($wait);
    }

    /**
     * @param string $message = ''
     *
     * @return void
     */
    final protected function exceptionNotFound(string $message = ''): void
    {
        throw new NotFoundException($message ?: __('common.error.not-found'));
    }

    /**
     * @param string $message
     *
     * @return void
     */
    final protected function exceptionValidator(string $message): void
    {
        throw new ValidatorException($message);
    }

    /**
     * @return bool
     */
    final protected function runningUnitTests(): bool
    {
        return App::runningUnitTests();
    }
}
