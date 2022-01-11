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
        return Schema::hasColumn('wallet_history', 'payload');
    }

    /**
     * @return void
     */
    protected function tables()
    {
        $this->tablesCreate();
        $this->tablesUpdate();
        $this->tablesDrop();
    }

    /**
     * @return void
     */
    protected function tablesCreate()
    {
        Schema::table('wallet_history', function (Blueprint $table) {
            $table->json('payload')->nullable()->after('name');
        });
    }

    /**
     * @return void
     */
    protected function tablesUpdate()
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
    protected function tablesDrop()
    {
        Schema::table('wallet_history', function (Blueprint $table) {
            if (Schema::hasColumn('wallet_history', 'order')) {
                $table->dropColumn('order');
            }

            if (Schema::hasColumn('wallet_history', 'amount')) {
                $table->dropColumn('amount');
            }

            if (Schema::hasColumn('wallet_history', 'buy_exchange')) {
                $table->dropColumn('buy_exchange');
            }

            if (Schema::hasColumn('wallet_history', 'buy_value')) {
                $table->dropColumn('buy_value');
            }

            if (Schema::hasColumn('wallet_history', 'current_exchange')) {
                $table->dropColumn('current_exchange');
            }

            if (Schema::hasColumn('wallet_history', 'current_value')) {
                $table->dropColumn('current_value');
            }

            if (Schema::hasColumn('wallet_history', 'sell_stop')) {
                $table->dropColumn('sell_stop');
            }

            if (Schema::hasColumn('wallet_history', 'sell_stop_exchange')) {
                $table->dropColumn('sell_stop_exchange');
            }

            if (Schema::hasColumn('wallet_history', 'sell_stop_amount')) {
                $table->dropColumn('sell_stop_amount');
            }

            if (Schema::hasColumn('wallet_history', 'sell_stop_max')) {
                $table->dropColumn('sell_stop_max');
            }

            if (Schema::hasColumn('wallet_history', 'sell_stop_max_value')) {
                $table->dropColumn('sell_stop_max_value');
            }

            if (Schema::hasColumn('wallet_history', 'sell_stop_max_percent')) {
                $table->dropColumn('sell_stop_max_percent');
            }

            if (Schema::hasColumn('wallet_history', 'sell_stop_max_at')) {
                $table->dropColumn('sell_stop_max_at');
            }

            if (Schema::hasColumn('wallet_history', 'sell_stop_max_executable')) {
                $table->dropColumn('sell_stop_max_executable');
            }

            if (Schema::hasColumn('wallet_history', 'sell_stop_min_exchange')) {
                $table->dropColumn('sell_stop_min_exchange');
            }

            if (Schema::hasColumn('wallet_history', 'sell_stop_min_value')) {
                $table->dropColumn('sell_stop_min_value');
            }

            if (Schema::hasColumn('wallet_history', 'sell_stop_min_percent')) {
                $table->dropColumn('sell_stop_min_percent');
            }

            if (Schema::hasColumn('wallet_history', 'sell_stop_min_at')) {
                $table->dropColumn('sell_stop_min_at');
            }

            if (Schema::hasColumn('wallet_history', 'sell_stop_min_executable')) {
                $table->dropColumn('sell_stop_min_executable');
            }

            if (Schema::hasColumn('wallet_history', 'buy_stop')) {
                $table->dropColumn('buy_stop');
            }

            if (Schema::hasColumn('wallet_history', 'buy_stop_exchange')) {
                $table->dropColumn('buy_stop_exchange');
            }

            if (Schema::hasColumn('wallet_history', 'buy_stop_amount')) {
                $table->dropColumn('buy_stop_amount');
            }

            if (Schema::hasColumn('wallet_history', 'buy_stop_max')) {
                $table->dropColumn('buy_stop_max');
            }

            if (Schema::hasColumn('wallet_history', 'buy_stop_max_value')) {
                $table->dropColumn('buy_stop_max_value');
            }

            if (Schema::hasColumn('wallet_history', 'buy_stop_max_percent')) {
                $table->dropColumn('buy_stop_max_percent');
            }

            if (Schema::hasColumn('wallet_history', 'buy_stop_max_at')) {
                $table->dropColumn('buy_stop_max_at');
            }

            if (Schema::hasColumn('wallet_history', 'buy_stop_max_executable')) {
                $table->dropColumn('buy_stop_max_executable');
            }

            if (Schema::hasColumn('wallet_history', 'buy_stop_min')) {
                $table->dropColumn('buy_stop_min');
            }

            if (Schema::hasColumn('wallet_history', 'buy_stop_min_value')) {
                $table->dropColumn('buy_stop_min_value');
            }

            if (Schema::hasColumn('wallet_history', 'buy_stop_min_percent')) {
                $table->dropColumn('buy_stop_min_percent');
            }

            if (Schema::hasColumn('wallet_history', 'buy_stop_min_at')) {
                $table->dropColumn('buy_stop_min_at');
            }

            if (Schema::hasColumn('wallet_history', 'buy_stop_min_executable')) {
                $table->dropColumn('buy_stop_min_executable');
            }

            if (Schema::hasColumn('wallet_history', 'sell_stoploss')) {
                $table->dropColumn('sell_stoploss');
            }

            if (Schema::hasColumn('wallet_history', 'sell_stoploss_exchange')) {
                $table->dropColumn('sell_stoploss_exchange');
            }

            if (Schema::hasColumn('wallet_history', 'sell_stoploss_value')) {
                $table->dropColumn('sell_stoploss_value');
            }

            if (Schema::hasColumn('wallet_history', 'sell_stoploss_percent')) {
                $table->dropColumn('sell_stoploss_percent');
            }

            if (Schema::hasColumn('wallet_history', 'sell_stoploss_at')) {
                $table->dropColumn('sell_stoploss_at');
            }

            if (Schema::hasColumn('wallet_history', 'sell_stoploss_executable')) {
                $table->dropColumn('sell_stoploss_executable');
            }

            if (Schema::hasColumn('wallet_history', 'processing')) {
                $table->dropColumn('processing');
            }

            if (Schema::hasColumn('wallet_history', 'custom')) {
                $table->dropColumn('custom');
            }

            if (Schema::hasColumn('wallet_history', 'crypto')) {
                $table->dropColumn('crypto');
            }

            if (Schema::hasColumn('wallet_history', 'trade')) {
                $table->dropColumn('trade');
            }

            if (Schema::hasColumn('wallet_history', 'visible')) {
                $table->dropColumn('visible');
            }

            if (Schema::hasColumn('wallet_history', 'enabled')) {
                $table->dropColumn('enabled');
            }
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
        Schema::table('wallet_history', function (Blueprint $table) {
            $table->dropColumn('payload');

            $table->unsignedSmallInteger('order')->default(0);

            $table->unsignedDouble('amount');

            $table->unsignedDouble('buy_exchange')->default(0);
            $table->unsignedDouble('buy_value')->default(0);

            $table->unsignedDouble('current_exchange')->default(0);
            $table->unsignedDouble('current_value')->default(0);

            $table->boolean('sell_stop')->default(0);

            $table->boolean('sell_stop_exchange')->default(0);

            $table->unsignedDouble('sell_stop_amount')->default(0);

            $table->unsignedDouble('sell_stop_max')->default(0);
            $table->unsignedDouble('sell_stop_max_value')->default(0);
            $table->unsignedDouble('sell_stop_max_percent')->default(0);
            $table->dateTime('sell_stop_max_at')->nullable();
            $table->boolean('sell_stop_max_executable')->default(0);

            $table->unsignedDouble('sell_stop_min')->default(0);
            $table->unsignedDouble('sell_stop_min_value')->default(0);
            $table->unsignedDouble('sell_stop_min_percent')->default(0);
            $table->dateTime('sell_stop_min_at')->nullable();
            $table->boolean('sell_stop_min_executable')->default(0);

            $table->boolean('buy_stop')->default(0);

            $table->boolean('buy_stop_exchange')->default(0);

            $table->unsignedDouble('buy_stop_amount')->default(0);

            $table->unsignedDouble('buy_stop_max')->default(0);
            $table->unsignedDouble('buy_stop_max_value')->default(0);
            $table->unsignedDouble('buy_stop_max_percent')->default(0);
            $table->dateTime('buy_stop_max_at')->nullable();
            $table->boolean('buy_stop_max_executable')->default(0);

            $table->unsignedDouble('buy_stop_min')->default(0);
            $table->unsignedDouble('buy_stop_min_value')->default(0);
            $table->unsignedDouble('buy_stop_min_percent')->default(0);
            $table->dateTime('buy_stop_min_at')->nullable();
            $table->boolean('buy_stop_min_executable')->default(0);

            $table->boolean('sell_stoploss')->default(0);

            $table->unsignedDouble('sell_stoploss_exchange')->default(0);
            $table->unsignedDouble('sell_stoploss_value')->default(0);
            $table->unsignedDouble('sell_stoploss_percent')->default(0);

            $table->dateTime('sell_stoploss_at')->nullable();
            $table->boolean('sell_stoploss_executable')->default(0);

            $table->boolean('processing')->default(0);
            $table->boolean('custom')->default(0);
            $table->boolean('crypto')->default(0);
            $table->boolean('trade')->default(0);
            $table->boolean('visible')->default(0);
            $table->boolean('enabled')->default(0);
        });
    }
};
