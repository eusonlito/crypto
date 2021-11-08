<?php declare(strict_types=1);

namespace App\Domains\Exchange\Command;

use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Shared\Command\CommandAbstract as CommandAbstractShared;

abstract class CommandAbstract extends CommandAbstractShared
{
    /**
     * @return \App\Domains\Platform\Model\Platform
     */
    public function platform(): PlatformModel
    {
        return PlatformModel::findOrFail($this->checkOption('platform_id'));
    }
}
