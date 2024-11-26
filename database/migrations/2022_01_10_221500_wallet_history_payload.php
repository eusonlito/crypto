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
    }

    /**
     * @return bool
     */
    protected function upMigrated(): bool
    {
        return Schema::hasColumn('wallet_history', 'payload');
    }

    /**
     * @return void
     */
    protected function upTables(): void
    {
        $this->upTablesCreate();
        $this->upTablesUpdate();
        $this->upTablesDrop();
    }

    /**
     * @return void
     */
    protected function upTablesCreate(): void
    {
        Schema::table('wallet_history', function (Blueprint $table) {
            $table->json('payload')->nullable();
        });
    }

    /**
     * @return void
     */
    protected function upTablesUpdate(): void
    {
        $columns = $this->db()->getSchemaBuilder()->getColumnListing('wallet_history');

        $this->db()->statement('
            UPDATE `wallet_history`
            SET `payload` = JSON_OBJECT('.implode(',', array_map(static fn ($value) => sprintf('"%s", `%s`', $value, $value), $columns)).');
        ');
    }

    /**
     * @return void
     */
    protected function upTablesDrop(): void
    {
        Schema::table('wallet_history', function (Blueprint $table) {
            $table->dropColumn('order');
            $table->dropColumn('amount');
            $table->dropColumn('buy_exchange');
            $table->dropColumn('buy_value');
            $table->dropColumn('current_exchange');
            $table->dropColumn('current_value');
            $table->dropColumn('sell_stop');
            $table->dropColumn('sell_stop_exchange');
            $table->dropColumn('sell_stop_amount');
            $table->dropColumn('sell_stop_max');
            $table->dropColumn('sell_stop_max_value');
            $table->dropColumn('sell_stop_max_percent');
            $table->dropColumn('sell_stop_max_at');
            $table->dropColumn('sell_stop_max_executable');
            $table->dropColumn('sell_stop_min_exchange');
            $table->dropColumn('sell_stop_min_value');
            $table->dropColumn('sell_stop_min_percent');
            $table->dropColumn('sell_stop_min_at');
            $table->dropColumn('sell_stop_min_executable');
            $table->dropColumn('buy_stop');
            $table->dropColumn('buy_stop_exchange');
            $table->dropColumn('buy_stop_amount');
            $table->dropColumn('buy_stop_max');
            $table->dropColumn('buy_stop_max_value');
            $table->dropColumn('buy_stop_max_percent');
            $table->dropColumn('buy_stop_max_at');
            $table->dropColumn('buy_stop_max_executable');
            $table->dropColumn('buy_stop_min');
            $table->dropColumn('buy_stop_min_value');
            $table->dropColumn('buy_stop_min_percent');
            $table->dropColumn('buy_stop_min_at');
            $table->dropColumn('buy_stop_min_executable');
            $table->dropColumn('sell_stoploss');
            $table->dropColumn('sell_stoploss_exchange');
            $table->dropColumn('sell_stoploss_value');
            $table->dropColumn('sell_stoploss_percent');
            $table->dropColumn('sell_stoploss_at');
            $table->dropColumn('sell_stoploss_executable');
            $table->dropColumn('processing_at');
            $table->dropColumn('custom');
            $table->dropColumn('crypto');
            $table->dropColumn('trade');
            $table->dropColumn('visible');
            $table->dropColumn('enabled');
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::table('wallet_history', function (Blueprint $table) {
            $table->dropColumn('payload');

            $table->unsignedSmallInteger('order')->default(0);

            $table->double('amount');

            $table->double('buy_exchange')->default(0);
            $table->double('buy_value')->default(0);

            $table->double('current_exchange')->default(0);
            $table->double('current_value')->default(0);

            $table->boolean('sell_stop')->default(0);

            $table->boolean('sell_stop_exchange')->default(0);

            $table->double('sell_stop_amount')->default(0);

            $table->double('sell_stop_max')->default(0);
            $table->double('sell_stop_max_value')->default(0);
            $table->double('sell_stop_max_percent')->default(0);
            $table->dateTime('sell_stop_max_at')->nullable();
            $table->boolean('sell_stop_max_executable')->default(0);

            $table->double('sell_stop_min')->default(0);
            $table->double('sell_stop_min_value')->default(0);
            $table->double('sell_stop_min_percent')->default(0);
            $table->dateTime('sell_stop_min_at')->nullable();
            $table->boolean('sell_stop_min_executable')->default(0);

            $table->boolean('buy_stop')->default(0);

            $table->boolean('buy_stop_exchange')->default(0);

            $table->double('buy_stop_amount')->default(0);

            $table->double('buy_stop_max')->default(0);
            $table->double('buy_stop_max_value')->default(0);
            $table->double('buy_stop_max_percent')->default(0);
            $table->dateTime('buy_stop_max_at')->nullable();
            $table->boolean('buy_stop_max_executable')->default(0);

            $table->double('buy_stop_min')->default(0);
            $table->double('buy_stop_min_value')->default(0);
            $table->double('buy_stop_min_percent')->default(0);
            $table->dateTime('buy_stop_min_at')->nullable();
            $table->boolean('buy_stop_min_executable')->default(0);

            $table->boolean('sell_stoploss')->default(0);

            $table->double('sell_stoploss_exchange')->default(0);
            $table->double('sell_stoploss_value')->default(0);
            $table->double('sell_stoploss_percent')->default(0);

            $table->dateTime('sell_stoploss_at')->nullable();
            $table->boolean('sell_stoploss_executable')->default(0);

            $table->boolean('custom')->default(0);
            $table->boolean('crypto')->default(0);
            $table->boolean('trade')->default(0);
            $table->boolean('visible')->default(0);
            $table->boolean('enabled')->default(0);

            $table->dateTime('processing_at')->nullable();
        });
    }
};
