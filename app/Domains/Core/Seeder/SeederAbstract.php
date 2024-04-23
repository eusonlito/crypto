<?php declare(strict_types=1);

namespace App\Domains\Core\Seeder;

use Closure;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SeederAbstract extends Seeder
{
    /**
     * @param string $name
     *
     * @return string
     */
    protected function file(string $name): string
    {
        return $this->pathDataFromClass().'/'.$name;
    }

    /**
     * @return string
     */
    protected function pathDataFromClass(): string
    {
        return base_path(lcfirst(dirname(str_replace('\\', '/', get_called_class())))).'/data';
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function contents(string $name): string
    {
        return file_get_contents($this->file($name));
    }

    /**
     * @param string $name
     *
     * @return array
     */
    protected function json(string $name): array
    {
        return json_decode($this->contents($name.'.json'), true);
    }

    /**
     * @param \Closure $function
     *
     * @return void
     */
    protected function transaction(Closure $function): void
    {
        DB::transaction(function () use ($function) {
            Schema::disableForeignKeyConstraints();

            $function();

            Schema::enableForeignKeyConstraints();
        });
    }

    /**
     * @param string ...$tables
     *
     * @return void
     */
    protected function truncate(string ...$tables): void
    {
        Schema::disableForeignKeyConstraints();

        foreach ((array)$tables as $table) {
            DB::table($table)->truncate();
        }

        Schema::enableForeignKeyConstraints();
    }

    /**
     * @param string $model
     * @param string $key
     * @param array $rows
     *
     * @return void
     */
    protected function insertWithoutDuplicates(string $model, string $key, array $rows): void
    {
        $keys = $model::query()->withoutGlobalScopes()->pluck($key)->toArray();

        foreach ($rows as $row) {
            if (in_array($row[$key], $keys) === false) {
                $model::query()->insert($this->insertUpdateData($row));
            }
        }
    }

    /**
     * @param string $model
     * @param string $key
     * @param array $rows
     *
     * @return void
     */
    protected function updateBy(string $model, string $key, array $rows): void
    {
        foreach ($rows as $row) {
            $model::query()
                ->where($key, $row[$key])
                ->update($this->insertUpdateData($row));
        }
    }

    /**
     * @param array $row
     *
     * @return array
     */
    protected function insertUpdateData(array $row): array
    {
        return array_map(static fn ($value) => is_array($value) ? json_encode($value) : $value, $row);
    }
}
