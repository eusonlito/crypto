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
        return Schema::hasColumn('wallet', 'sell_stop_percent');
    }

    /**
     * @return void
     */
    protected function upTables(): void
    {
        Schema::table('wallet', function (Blueprint $table) {
            $table->double('sell_stop_percent')->default(100);
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::table('wallet', function (Blueprint $table) {
            $table->dropColumn('sell_stop_percent');
        });
    }
};
