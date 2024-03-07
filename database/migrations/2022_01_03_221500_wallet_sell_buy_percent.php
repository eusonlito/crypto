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
        return (Schema::hasColumn('wallet', 'sell_stop_percent') === false)
            && (Schema::hasColumn('wallet', 'buy_stop_percent') === false);
    }

    /**
     * @return void
     */
    protected function tables()
    {
        Schema::table('wallet', function (Blueprint $table) {
            $table->dropColumn('sell_stop_percent');
            $table->dropColumn('buy_stop_percent');
        });

        Schema::table('wallet_history', function (Blueprint $table) {
            $table->dropColumn('sell_stop_percent');
            $table->dropColumn('buy_stop_percent');
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::table('wallet', function (Blueprint $table) {
            $table->unsignedDouble('sell_stop_percent')->default(0)->after('sell_stop_min_executable');
            $table->unsignedDouble('buy_stop_percent')->default(0)->after('buy_stop_min_executable');
        });

        Schema::table('wallet_history', function (Blueprint $table) {
            $table->unsignedDouble('sell_stop_percent')->default(0)->after('sell_stop_min_executable');
            $table->unsignedDouble('buy_stop_percent')->default(0)->after('buy_stop_min_executable');
        });
    }
};
