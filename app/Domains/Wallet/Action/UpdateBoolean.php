<?php declare(strict_types=1);

namespace App\Domains\Wallet\Action;

use App\Domains\Wallet\Model\Wallet as Model;

class UpdateBoolean extends ActionAbstract
{
    /**
     * @return \App\Domains\Wallet\Model\Wallet
     */
    public function handle(): Model
    {
        $this->data();
        $this->save();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function data(): void
    {
        if ($this->data['column'] === 'processing_at') {
            $this->data['value'] = null;
        } else {
            $this->data['value'] = empty($this->row->{$this->data['column']});
        }
    }

    /**
     * @return void
     */
    protected function save(): void
    {
        $this->row->{$this->data['column']} = $this->data['value'];
        $this->row->save();
    }
}
