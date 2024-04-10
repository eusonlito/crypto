<?php declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\Language\Seeder\Language as LanguageSeeder;
use App\Domains\Platform\Seeder\Platform as PlatformSeeder;

class Database extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        $time = time();

        $this->call(LanguageSeeder::class);
        $this->call(PlatformSeeder::class);

        $this->command->info(sprintf('Seeding: Total Time %s seconds', time() - $time));
    }
}
