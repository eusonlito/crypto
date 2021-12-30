<?php declare(strict_types=1);

namespace App\Domains\User\Action;

use Illuminate\Support\Facades\Hash;
use App\Domains\User\Model\User as Model;
use App\Domains\User\Service\TFA\Google as GoogleTFA;
use App\Exceptions\ValidatorException;

class Signup extends ActionAbstract
{
    /**
     * @return \App\Domains\User\Model\User
     */
    public function handle(): Model
    {
        $this->data();
        $this->check();
        $this->create();
        $this->auth();

        return $this->row;
    }

    /**
     * @return void
     */
    protected function data(): void
    {
        $this->data['email'] = strtolower($this->data['email']);
        $this->data['password'] = Hash::make($this->data['password']);
        $this->data['tfa_secret'] = GoogleTFA::generateSecretKey();
        $this->data['tfa_enabled'] = 0;
        $this->data['ip'] = $this->request->ip();
        $this->data['language_id'] = app('language')->id;
    }

    /**
     * @return void
     */
    protected function check(): void
    {
        $this->checkIp();
        $this->checkCode();
        $this->checkLast();
        $this->checkEmail();
    }

    /**
     * @return void
     */
    protected function checkIp(): void
    {
        $this->factory('IpLock')->action()->check();
    }

    /**
     * @return void
     */
    protected function checkCode(): void
    {
        if (config('auth.signup.code') !== $this->data['code']) {
            $this->fail(__('user-signup.error.code'));
        }
    }

    /**
     * @return void
     */
    protected function checkLast(): void
    {
        if (Model::byIp($this->request->ip())->byCreatedAtRecent($this->checkLastDate())->count()) {
            $this->fail(__('user-signup.error.ip-limit'));
        }
    }

    /**
     * @return string
     */
    protected function checkLastDate(): string
    {
        return date('Y-m-d H:i:s', strtotime('-60 minutes'));
    }

    /**
     * @return void
     */
    protected function checkEmail(): void
    {
        if (Model::byEmail($this->data['email'])->count()) {
            $this->fail(__('user-signup.error.email-unique'));
        }
    }

    /**
     * @param string $message
     *
     * @throws \App\Exceptions\ValidatorException
     *
     * @return void
     */
    protected function fail(string $message): void
    {
        $this->factory('UserSession')->action(['auth' => $this->data['email']])->fail();

        service()->message()->throw(new ValidatorException($message), 'validate');
    }

    /**
     * @return void
     */
    protected function create(): void
    {
        $this->row = Model::create([
            'email' => $this->data['email'],
            'password' => $this->data['password'],
            'code' => $this->data['code'],
            'tfa_secret' => $this->data['tfa_secret'],
            'tfa_enabled' => $this->data['tfa_enabled'],
            'enabled' => true,
            'ip' => $this->data['ip'],
            'language_id' => $this->data['language_id'],
        ]);
    }

    /**
     * @return void
     */
    protected function auth(): void
    {
        $this->auth = $this->factory()->action()->authModel();
    }
}
