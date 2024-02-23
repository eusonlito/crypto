<?php declare(strict_types=1);

namespace App\Domains\Forecast\Action;

use Illuminate\Support\Collection;
use App\Domains\Forecast\Model\Forecast as Model;
use App\Domains\Core\Action\ActionFactoryAbstract;

class ActionFactory extends ActionFactoryAbstract
{
    /**
     * @var ?\App\Domains\Forecast\Model\Forecast
     */
    protected ?Model $row;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function all(): Collection
    {
        return $this->actionHandle(All::class, $this->validate()->all(), ...func_get_args());
    }

    /**
     * @return ?\App\Domains\Forecast\Model\Forecast
     */
    public function selected(): ?Model
    {
        return $this->actionHandle(Selected::class, $this->validate()->selected(), ...func_get_args());
    }
}
