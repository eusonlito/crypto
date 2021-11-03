<?php declare(strict_types=1);

namespace App\Domains\IpLock\Action;

use App\Domains\IpLock\Model\IpLock as Model;

class Create extends ActionAbstract
{
    /**
     * @return \App\Domains\IpLock\Model\IpLock
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
        $this->row = Model::current()->updateOrCreate(
            ['ip' => $this->request->ip()],
            ['end_at' => date('Y-m-d H:i:s', strtotime('+'.(int)config('auth.lock.check').' seconds'))]
        );
    }
}
