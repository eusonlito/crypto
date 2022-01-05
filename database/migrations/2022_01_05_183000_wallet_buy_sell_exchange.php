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
        return Schema::hasColumn('wallet', 'sell_stop_exchange')
            || Schema::hasColumn('wallet', 'buy_stop_exchange');
    }

    /**
     * @return void
     */
    protected function tables()
    {
        Schema::table('wallet', function (Blueprint $table) {
            $table->unsignedDouble('sell_stop_exchange')->default(0)->after('sell_stop');
            $table->unsignedDouble('buy_stop_exchange')->default(0)->after('buy_stop');
        });

        Schema::table('wallet_history', function (Blueprint $table) {
            $table->unsignedDouble('sell_stop_exchange')->default(0)->after('sell_stop');
            $table->unsignedDouble('buy_stop_exchange')->default(0)->after('buy_stop');
        });

        $this->db()->statement('UPDATE `wallet` SET `sell_stop_exchange` = `buy_exchange`, `buy_stop_exchange` = `buy_exchange`;');
        $this->db()->statement('UPDATE `wallet_history` SET `sell_stop_exchange` = `buy_exchange`, `buy_stop_exchange` = `buy_exchange`;');
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
            $table->dropColumn('sell_stop_exchange');
            $table->dropColumn('buy_stop_exchange');
        });

        Schema::table('wallet_history', function (Blueprint $table) {
            $table->dropColumn('sell_stop_exchange');
            $table->dropColumn('buy_stop_exchange');
        });
    }
};
