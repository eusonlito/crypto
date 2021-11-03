<?php declare(strict_types=1);

namespace App\Domains\Wallet\Command;

use App\Domains\Shared\Command\CommandAbstract as CommandAbstractShared;
use App\Domains\Wallet\Model\Wallet as Model;

abstract class CommandAbstract extends CommandAbstractShared
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
        $this->row = Model::findOrFail($this->checkOption('id'));
        $this->actingAs($this->row->user);
    }
}
