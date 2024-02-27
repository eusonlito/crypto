<?php declare(strict_types=1);

namespace App\Domains\UserSession\Action;

use App\Domains\UserSession\Model\UserSession as Model;

class Fail extends ActionAbstract
{
    /**
     * @var string
     */
    protected string $ip;

    /**
     * @return void
     */
    public function handle(): void
    {
        $this->ip = $this->request->ip();

        $this->store();

        if ($this->shouldBeLocked()) {
            $this->lock();
        }
    }

    /**
     * @return void
     */
    protected function store(): void
    {
        Model::query()->insert([
            'ip' => $this->ip,
            'success' => false,
        ]);
    }

    /**
     * @return bool
     */
    protected function shouldBeLocked(): bool
    {
        return $this->count() > (int)config('auth.lock.allowed');
    }

    /**
     * @return int
     */
    protected function count(): int
    {
        return Model::query()
            ->where('success', false)
            ->where('ip', $this->ip)
            ->where('created_at', '>=', $this->authLockCheckDate())
            ->count();
    }

    /**
     * @return string
     */
    protected function authLockCheckDate(): string
    {
        return date('Y-m-d H:i:s', strtotime('-'.(int)config('auth.lock.check').' seconds'));
    }

    /**
     * @return void
     */
    protected function lock(): void
    {
        $this->factory('IpLock')->action()->create();
    }
}
