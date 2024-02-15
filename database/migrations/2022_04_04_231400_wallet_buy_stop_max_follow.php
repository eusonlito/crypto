<?php declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Domains\Shared\Migration\MigrationAbstract;

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
        $this->keys();
    }

    /**
     * @return bool
     */
    protected function upMigrated(): bool
    {
        return Schema::hasColumn('wallet', 'buy_stop_max_follow');
    }

    /**
     * @return void
     */
    protected function tables()
    {
        Schema::table('wallet', function (Blueprint $table) {
            $table->boolean('buy_stop_max_follow')->default(0)->after('buy_stop_max_executable');
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
        Schema::table('wallet', function (Blueprint $table) {
            $table->dropColumn('buy_stop_max_follow');
        });
    }
};
