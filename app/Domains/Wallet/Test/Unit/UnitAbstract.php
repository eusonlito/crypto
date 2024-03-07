<?php declare(strict_types=1);

namespace App\Domains\Wallet\Test\Unit;

use App\Domains\Wallet\Model\Wallet as Model;
use App\Domains\Core\Test\Unit\UnitAbstract as UnitAbstractCore;

abstract class UnitAbstract extends UnitAbstractCore
{
    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return Model::class;
    }
}
