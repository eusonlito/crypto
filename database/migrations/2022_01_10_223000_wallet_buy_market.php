<?php declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Domains\Core\Migration\MigrationAbstract;

return new class extends MigrationAbstract {
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
        return Schema::hasColumn('wallet', 'buy_market');
    }

    /**
     * @return void
     */
    protected function upTables(): void
    {
        Schema::table('wallet', function (Blueprint $table) {
            // Buy Market Enabled
            $table->boolean('buy_market')->default(0);

            // Buy Market Exchange Reference
            $table->double('buy_market_reference')->default(0);

            // Amount available to spend
            $table->double('buy_market_amount')->default(0);

            // We will buy at this price if reached
            $table->double('buy_market_percent')->default(0);
            $table->double('buy_market_exchange')->default(0);
            $table->double('buy_market_value')->default(0);

            $table->dateTime('buy_market_at')->nullable();
            $table->boolean('buy_market_executable')->default(0);
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::table('wallet', function (Blueprint $table) {
            $table->dropColumn('buy_market');
            $table->dropColumn('buy_market_reference');
            $table->dropColumn('buy_market_amount');
            $table->dropColumn('buy_market_percent');
            $table->dropColumn('buy_market_exchange');
            $table->dropColumn('buy_market_value');
            $table->dropColumn('buy_market_at');
            $table->dropColumn('buy_market_executable');
        });
    }
};
