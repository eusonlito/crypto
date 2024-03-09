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
        return Schema::hasColumn('wallet', 'sell_stoploss_executable');
    }

    /**
     * @return void
     */
    protected function upTables(): void
    {
        Schema::table('wallet', function (Blueprint $table) {
            $table->boolean('sell_stoploss_executable')->default(0);
        });

        Schema::table('wallet_history', function (Blueprint $table) {
            $table->boolean('sell_stoploss_executable')->default(0);
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::table('wallet', function (Blueprint $table) {
            $table->dropColumn('sell_stoploss_executable');
        });

        Schema::table('wallet_history', function (Blueprint $table) {
            $table->dropColumn('sell_stoploss_executable');
        });
    }
};
