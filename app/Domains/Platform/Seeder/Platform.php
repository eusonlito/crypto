<?php declare(strict_types=1);

namespace App\Domains\Platform\Seeder;

use App\Domains\Platform\Model\Platform as Model;
use App\Domains\Shared\Seeder\SeederAbstract;

class Platform extends SeederAbstract
{
    /**
     * @return void
     */
    public function run()
    {
        $this->truncate('platform');

        Model::insert($this->json('platform'));
    }
}
