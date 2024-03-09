<?php declare(strict_types=1);

use App\Domains\Core\Migration\MigrationAbstract;
use App\Domains\Platform\Seeder\Platform as PlatformSeeder;

return new class extends MigrationAbstract {
    /**
     * @return void
     */
    public function up(): void
    {
        $this->upSeed();
    }

    /**
     * @return void
     */
    protected function upSeed(): void
    {
        (new PlatformSeeder())->run();
    }
};
