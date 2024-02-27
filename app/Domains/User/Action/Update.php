<?php declare(strict_types=1);

namespace App\Domains\User\Action;

use Illuminate\Support\Facades\Hash;
use App\Domains\User\Model\User as Model;
use App\Exceptions\ValidatorException;

class Update extends ActionAbstract
{
    /**
     * @return \App\Domains\User\Model\User
     */
    public function handle(): Model
    {
        $this->data();
        $this->check();
        $this->update();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function data(): void
    {
        $this->data['email'] = strtolower($this->data['email']);
    }

    /**
     * @return void
     */
    protected function check(): void
    {
        $this->checkEmail();
    }

    /**
     * @return void
     */
    protected function checkEmail(): void
    {
        if (Model::query()->byIdNot($this->row->id)->byEmail($this->data['email'])->count()) {
            throw new ValidatorException(__('user-update.error.email-exists'));
        }
    }

    /**
     * @return void
     */
    protected function update(): void
    {
        $this->row->email = $this->data['email'];
        $this->row->investment = $this->data['investment'];
        $this->row->tfa_enabled = $this->data['tfa_enabled'];

        if ($this->data['password']) {
            $this->row->password = Hash::make($this->data['password']);
        }

        $this->row->save();
    }
}
