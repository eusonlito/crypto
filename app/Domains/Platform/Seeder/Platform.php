<?php declare(strict_types=1);

namespace App\Domains\Platform\Seeder;

use App\Domains\Platform\Model\Platform as Model;
use App\Domains\Core\Seeder\SeederAbstract;

class Platform extends SeederAbstract
{
    /**
     * @return void
     */
    public function run()
    {
        $this->insertWithoutDuplicates(Model::class, 'code', $this->json('platform'));
    }
}
