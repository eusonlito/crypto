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
        return Schema::hasColumn('wallet', 'sell_stoploss_executable');
    }

    /**
     * @return void
     */
    protected function tables()
    {
        Schema::table('wallet', function (Blueprint $table) {
            $table->boolean('sell_stoploss_executable')->default(0)->after('sell_stoploss_at');
        });

        Schema::table('wallet_history', function (Blueprint $table) {
            $table->boolean('sell_stoploss_executable')->default(0)->after('sell_stoploss_at');
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
            $table->dropColumn('sell_stoploss_executable');
        });

        Schema::table('wallet_history', function (Blueprint $table) {
            $table->dropColumn('sell_stoploss_executable');
        });
    }
};
