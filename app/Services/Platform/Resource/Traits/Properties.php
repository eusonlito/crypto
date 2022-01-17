<?php declare(strict_types=1);

namespace App\Services\Platform\Resource\Traits;

use BadMethodCallException;
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
        $this->propertiesCheck($attributes);

        foreach ($attributes as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * @param array $attributes
     *
     * @return void
     */
    protected function propertiesCheck(array $attributes): void
    {
        $properties = $this->propertiesCached();
        $keys = array_keys($attributes);

        if ($diff = array_diff($keys, $properties)) {
            throw new BadMethodCallException(sprintf('Invalid attributes %s on %s', implode(', ', $diff), $this::class));
        }

        if ($diff = array_diff($properties, $keys)) {
            throw new BadMethodCallException(sprintf('Required attributes %s on %s', implode(', ', $diff), $this::class));
        }
    }

    /**
     * @return array
     */
    protected function propertiesCached(): array
    {
        return static::$properties[$this::class] ??= $this->propertiesMap();
    }

    /**
     * @return array
     */
    protected function propertiesMap(): array
    {
        return array_values(array_filter(array_map(
            static fn ($value) => $value->isPublic() ? $value->getName() : null,
            (new ReflectionClass($this))->getProperties()
        )));
    }
}
