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
        return Schema::hasColumn('wallet', 'sell_stop_reference');
    }

    /**
     * @return void
     */
    protected function tables()
    {
        Schema::table('wallet', function (Blueprint $table) {
            $table->renameColumn('sell_stop_exchange', 'sell_stop_reference');
            $table->renameColumn('sell_stop_min', 'sell_stop_min_exchange');
            $table->renameColumn('sell_stop_max', 'sell_stop_max_exchange');

            $table->renameColumn('buy_stop_exchange', 'buy_stop_reference');
            $table->renameColumn('buy_stop_min', 'buy_stop_min_exchange');
            $table->renameColumn('buy_stop_max', 'buy_stop_max_exchange');
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
            $table->renameColumn('sell_stop_reference', 'sell_stop_exchange');
            $table->renameColumn('sell_stop_min_exchange', 'sell_stop_min');
            $table->renameColumn('sell_stop_max_exchange', 'sell_stop_max');

            $table->renameColumn('buy_stop_reference', 'buy_stop_exchange');
            $table->renameColumn('buy_stop_min_exchange', 'buy_stop_min');
            $table->renameColumn('buy_stop_max_exchange', 'buy_stop_max');
        });
    }
};
