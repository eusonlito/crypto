<?php declare(strict_types=1);

namespace App\Domains\Product\Command;

use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Product\Model\Product as Model;
use App\Domains\Shared\Command\CommandAbstract as CommandAbstractShared;

abstract class CommandAbstract extends CommandAbstractShared
{
    /**
     * @var \App\Domains\Product\Model\Product
     */
    protected Model $row;

    /**
     * @var \App\Domains\Platform\Model\Platform
     */
    protected PlatformModel $platform;

    /**
     * @return void
     */
    protected function row(): void
    {
        $this->row = Model::findOrFail($this->checkOption('id'));
    }

    /**
     * @return void
     */
    protected function platform(): void
    {
        $this->platform = PlatformModel::findOrFail($this->checkOption('platform_id'));
    }
}
