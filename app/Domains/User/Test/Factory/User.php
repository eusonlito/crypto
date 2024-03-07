<?php declare(strict_types=1);

namespace App\Domains\User\Test\Factory;

use Illuminate\Support\Facades\Hash;
use App\Domains\Core\Test\Factory\FactoryAbstract;
use App\Domains\User\Model\User as Model;

class User extends FactoryAbstract
{
    /**
     * @var class-string<\App\Domains\User\Model\User>
     */
    protected $model = Model::class;

    /**
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => ($email = $this->faker->companyEmail()),
            'password' => Hash::make($email),
            'admin' => false,
            'enabled' => true,
            'investment' => 1000,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'language_id' => 1,
        ];
    }
}
