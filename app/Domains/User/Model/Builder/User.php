<?php declare(strict_types=1);

namespace App\Domains\User\Model\Builder;

use App\Domains\Shared\Model\Builder\BuilderAbstract;

class User extends BuilderAbstract
{
    /**
     * @param string $email
     *
     * @return self
     */
    public function byEmail(string $email): self
    {
        return $this->where('email', strtolower($email));
    }

    /**
     * @param string $ip
     *
     * @return self
     */
    public function byIp(string $ip): self
    {
        return $this->where('ip', $ip);
    }

    /**
     * @param string $date
     *
     * @return self
     */
    public function byCreatedAtRecent(string $date): self
    {
        return $this->where('created_at', '>=', $date);
    }
}
