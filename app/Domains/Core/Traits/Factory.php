<?php declare(strict_types=1);

namespace App\Domains\Core\Traits;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Domains\Core\Model\ModelAbstract;
use App\Domains\Core\Service\Factory\Factory as FactoryService;

trait Factory
{
    /**
     * @var ?\Illuminate\Http\Request
     */
    protected ?Request $request = null;

    /**
     * @var ?\Illuminate\Contracts\Auth\Authenticatable
     */
    protected ?Authenticatable $auth = null;

    /**
     * @var array
     */
    protected array $data = [];

    /**
     * @param ?string $domain = null
     * @param ?\App\Domains\Core\Model\ModelAbstract $row = null
     *
     * @return \App\Domains\Core\Service\Factory\Factory
     */
    final protected function factory(?string $domain = null, ?ModelAbstract $row = null): FactoryService
    {
        return new FactoryService($this->factoryNamespace($domain), $this->request, $this->auth, $this->factoryRow($domain, $row));
    }

    /**
     * @param ?string $domain = null
     *
     * @return string
     */
    final protected function factoryNamespace(?string $domain = null): string
    {
        return 'App\\Domains\\'.$this->factoryDomain($domain);
    }

    /**
     * @param ?string $domain = null
     *
     * @return string
     */
    final protected function factoryDomain(?string $domain = null): string
    {
        return $domain ?: explode('\\', get_called_class())[2];
    }

    /**
     * @param ?string $domain = null
     * @param ?\App\Domains\Core\Model\ModelAbstract $row = null
     *
     * @return ?\App\Domains\Core\Model\ModelAbstract
     */
    final protected function factoryRow(?string $domain = null, ?ModelAbstract $row = null): ?ModelAbstract
    {
        return ($row || ($domain !== null)) ? $row : ($this->row ?? null);
    }

    /**
     * @return \Illuminate\Database\ConnectionInterface
     */
    final protected function connection(): ConnectionInterface
    {
        return DB::connection($this->connectionName());
    }

    /**
     * @return string
     */
    protected function connectionName(): string
    {
        return config('database.default');
    }
}
