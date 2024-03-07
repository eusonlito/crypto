<?php declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Domains\Core\Migration\MigrationAbstract;

return new class extends MigrationAbstract {
    /**
     * @return void
     */
    public function up()
    {
        if ($this->upMigrated()) {
            return;
        }

        $this->tables();
    }

    /**
     * @return bool
     */
    protected function upMigrated(): bool
    {
        return Schema::hasColumn('user', 'enabled')
            || Schema::hasColumn('user', 'admin');
    }

    /**
     * @return void
     */
    protected function tables()
    {
        Schema::table('user', function (Blueprint $table) {
            $table->boolean('admin')->default(0)->after('tfa_enabled');
            $table->boolean('enabled')->default(1)->after('tfa_enabled');
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::table('user', function (Blueprint $table) {
            $table->dropColumn('admin');
            $table->dropColumn('enabled');
        });
    }
};
