<?php declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Domains\Core\Migration\MigrationAbstract;

return new class() extends MigrationAbstract {
    /**
     * @return void
     */
    public function up(): void
    {
        if ($this->upMigrated()) {
            return;
        }

        $this->upTables();
        $this->upUpdate();
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
    protected function upTables(): void
    {
        Schema::table('wallet', function (Blueprint $table) {
            $table->double('sell_stop_exchange')->default(0);
            $table->double('buy_stop_exchange')->default(0);
        });

        if (Schema::hasColumn('wallet_history', 'buy_exchange')) {
            Schema::table('wallet_history', function (Blueprint $table) {
                $table->double('sell_stop_exchange')->default(0);
                $table->double('buy_stop_exchange')->default(0);
            });
        }
    }

    /**
     * @return void
     */
    protected function upUpdate(): void
    {
        $this->db()->statement('UPDATE `wallet` SET `sell_stop_exchange` = `buy_exchange`, `buy_stop_exchange` = `buy_exchange`;');

        if (Schema::hasColumn('wallet_history', 'buy_exchange')) {
            $this->db()->statement('UPDATE `wallet_history` SET `sell_stop_exchange` = `buy_exchange`, `buy_stop_exchange` = `buy_exchange`;');
        }
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::table('wallet', function (Blueprint $table) {
            $table->dropColumn('sell_stop_exchange');
            $table->dropColumn('buy_stop_exchange');
        });

        if (Schema::hasColumn('wallet_history', 'sell_stop_exchange')) {
            Schema::table('wallet_history', function (Blueprint $table) {
                $table->dropColumn('sell_stop_exchange');
                $table->dropColumn('buy_stop_exchange');
            });
        }
    }
};
