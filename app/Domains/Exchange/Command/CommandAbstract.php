<?php declare(strict_types=1);

namespace App\Domains\Exchange\Command;

use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Core\Command\CommandAbstract as CommandAbstractCore;

abstract class CommandAbstract extends CommandAbstractCore
{
    /**
     * @return \App\Domains\Platform\Model\Platform
     */
    public function platform(): PlatformModel
    {
        return PlatformModel::query()->findOrFail($this->checkOption('platform_id'));
    }
}
