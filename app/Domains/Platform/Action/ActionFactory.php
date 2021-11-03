<?php declare(strict_types=1);

namespace App\Domains\Platform\Action;

use App\Domains\Platform\Model\Platform as Model;
use App\Domains\Shared\Action\ActionFactoryAbstract;

class ActionFactory extends ActionFactoryAbstract
{
    /**
     * @var ?\App\Domains\Platform\Model\Platform
     */
    protected ?Model $row;

    /**
     * @return \App\Domains\Platform\Model\Platform
     */
    public function relate(): Model
    {
        return $this->actionHandle(Relate::class, null, ...func_get_args());
    }
}
