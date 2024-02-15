<?php declare(strict_types=1);

use App\Domains\Shared\Migration\MigrationAbstract;
use App\Domains\Platform\Seeder\Platform as PlatformSeeder;

return new class extends MigrationAbstract {
    /**
     * @return void
     */
    public function up()
    {
        $this->seed();
    }

    /**
     * @return void
     */
    protected function seed()
    {
        (new PlatformSeeder())->run();
    }
};
