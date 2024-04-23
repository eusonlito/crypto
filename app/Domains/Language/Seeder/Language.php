<?php declare(strict_types=1);

namespace App\Domains\Language\Seeder;

use App\Domains\Language\Model\Language as Model;
use App\Domains\Core\Seeder\SeederAbstract;

class Language extends SeederAbstract
{
    /**
     * @return void
     */
    public function run()
    {
        $this->insertWithoutDuplicates(Model::class, 'iso', $this->json('language'));
    }
}
