<?php declare(strict_types=1);

namespace App\Domains\Product\Action;

use App\Domains\Product\Model\Product as Model;

class UpdateBoolean extends ActionAbstract
{
    /**
     * @return \App\Domains\Product\Model\Product
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
