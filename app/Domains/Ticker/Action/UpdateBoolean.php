<?php declare(strict_types=1);

namespace App\Domains\Ticker\Action;

use App\Domains\Ticker\Model\Ticker as Model;

class UpdateBoolean extends ActionAbstract
{
    /**
     * @return \App\Domains\Ticker\Model\Ticker
     */
    public function handle(): Model
    {
        $this->store();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function store(): void
    {
        $this->row->{$this->data['column']} = !$this->row->{$this->data['column']};
        $this->row->save();
    }
}
