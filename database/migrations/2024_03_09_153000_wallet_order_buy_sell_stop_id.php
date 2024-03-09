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
        $this->upKeys();
    }

    /**
     * @return bool
     */
    protected function upMigrated(): bool
    {
        return Schema::hasColumn('wallet', 'order_buy_stop_id');
    }

    /**
     * @return void
     */
    protected function upTables(): void
    {
        Schema::table('wallet', function (Blueprint $table) {
            $table->unsignedBigInteger('order_buy_stop_id')->nullable();
            $table->unsignedBigInteger('order_sell_stop_id')->nullable();
        });
    }

    /**
     * @return void
     */
    protected function upKeys(): void
    {
        Schema::table('wallet', function (Blueprint $table) {
            $this->foreignOnDeleteSetNull($table, 'order', 'order_buy_stop_id');
            $this->foreignOnDeleteSetNull($table, 'order', 'order_sell_stop_id');
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::table('wallet', function (Blueprint $table) {
            $table->dropForeign('wallet_order_buy_stop_id_fk');
            $table->dropForeign('wallet_order_sell_stop_id_fk');

            $table->dropColumn('order_buy_stop_id');
            $table->dropColumn('order_sell_stop_id');
        });
    }
};
