<?php declare(strict_types=1);

namespace App\Services\Platform\Resource\Traits;

use ReflectionClass;

trait Properties
{
    /**
     * @var array
     */
    protected static array $properties;

    /**
     * @param array $attributes
     *
     * @return void
     */
    protected function properties(array $attributes): void
    {
        foreach ($this->propertiesCached() as $name) {
            if (array_key_exists($name, $attributes)) {
                $this->$name = $attributes[$name];
            }
        }
    }

    /**
     * @return array
     */
    protected function propertiesCached(): array
    {
        return static::$properties ??= array_map(static fn ($value) => $value->getName(), (new ReflectionClass($this))->getProperties());
    }
}
