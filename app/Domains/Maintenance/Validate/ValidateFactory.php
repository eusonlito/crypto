<?php declare(strict_types=1);

namespace App\Domains\Maintenance\Validate;

use App\Domains\Shared\Validate\ValidateFactoryAbstract;

class ValidateFactory extends ValidateFactoryAbstract
{
    /**
     * @return array
     */
    public function fileDeleteOlder(): array
    {
        return $this->handle(FileDeleteOlder::class);
    }

    /**
     * @return array
     */
    public function fileZip(): array
    {
        return $this->handle(FileZip::class);
    }
}
