<?php declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Domains\Shared\Migration\MigrationAbstract;

return new class extends MigrationAbstract
{
    /**
     * @return void
     */
    public function up()
    {
        if ($this->upMigrated()) {
            return;
        }

        $this->tables();
        $this->keys();
    }

    /**
     * @return bool
     */
    protected function upMigrated(): bool
    {
        return Schema::hasColumn('order', 'custom');
    }

    /**
     * @return void
     */
    protected function tables()
    {
        Schema::table('order', function (Blueprint $table) {
            $table->boolean('custom')->default(0)->after('filled');
        });
    }

    /**
     * @return void
     */
    protected function keys()
    {
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::table('order', function (Blueprint $table) {
            $table->dropColumn('custom');
        });
    }
};
