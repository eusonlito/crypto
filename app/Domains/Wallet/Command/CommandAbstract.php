<?php declare(strict_types=1);

namespace App\Domains\Wallet\Command;

use App\Domains\Core\Command\CommandAbstract as CommandAbstractCore;
use App\Domains\Wallet\Model\Wallet as Model;

abstract class CommandAbstract extends CommandAbstractCore
{
    /**
     * @var \App\Domains\Wallet\Model\Wallet
     */
    protected Model $row;

    /**
     * @return void
     */
    protected function row(): void
    {
        $this->row = Model::query()->findOrFail($this->checkOption('id'));
        $this->actingAs($this->row->user);
    }
}
