<?php declare(strict_types=1);

namespace App\Domains\Product\Command;

use App\Domains\Platform\Model\Platform as PlatformModel;
use App\Domains\Product\Model\Product as Model;
use App\Domains\Core\Command\CommandAbstract as CommandAbstractCore;

abstract class CommandAbstract extends CommandAbstractCore
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
        $this->row = Model::query()->findOrFail($this->checkOption('id'));
    }

    /**
     * @return void
     */
    protected function platform(): void
    {
        $this->platform = PlatformModel::query()->findOrFail($this->checkOption('platform_id'));
    }
}
