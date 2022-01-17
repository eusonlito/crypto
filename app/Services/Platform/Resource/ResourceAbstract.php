<?php declare(strict_types=1);

namespace App\Services\Platform\Resource;

use App\Services\Platform\Resource\Traits\Properties as PropertiesTrait;

abstract class ResourceAbstract
{
    use PropertiesTrait;

    /**
     * @param array $attributes
     *
     * @return self
     */
    public function __construct(array $attributes)
    {
        $this->properties($attributes);
    }
}
