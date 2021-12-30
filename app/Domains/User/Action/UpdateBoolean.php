<?php declare(strict_types=1);

namespace App\Domains\User\Action;

use App\Domains\User\Model\User as Model;
use App\Exceptions\ValidatorException;

class UpdateBoolean extends ActionAbstract
{
    /**
     * @return \App\Domains\User\Model\User
     */
    public function handle(): Model
    {
        $this->check();
        $this->store();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function check(): void
    {
        $this->checkColumn();
        $this->checkSelf();
    }

    /**
     * @return void
     */
    protected function checkColumn(): void
    {
        if (($this->row->getCasts()[$this->data['column']] ?? null) !== 'boolean') {
            throw new ValidatorException(__('user-update-boolean.error.column-not-valid'));
        }
    }

    /**
     * @return void
     */
    protected function checkSelf(): void
    {
        if ($this->row->id === $this->auth->id) {
            throw new ValidatorException(__('user-update-boolean.error.self'));
        }
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
