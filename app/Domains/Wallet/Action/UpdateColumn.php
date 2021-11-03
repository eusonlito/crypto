<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action;

use App\Domains\Wallet\Model\Wallet as Model;
use App\Exceptions\ValidatorException;

class UpdateColumn extends ActionAbstract
{
    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function handle(): Model
    {
        $this->data();
        $this->store();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function data(): void
    {
        $this->data['value'] = match ($this->data['column']) {
            'order' => (int)$this->data['value'],
            default => throw new ValidatorException(__('wallet-update-column.error.column')),
        };
    }

    /**
     * @return void
     */
    protected function store(): void
    {
        $this->row->{$this->data['column']} = $this->data['value'];
        $this->row->save();
    }
}
