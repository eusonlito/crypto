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
        return Schema::hasColumn('wallet', 'buy_stop_max_follow');
    }

    /**
     * @return void
     */
    protected function upTables(): void
    {
        Schema::table('wallet', function (Blueprint $table) {
            $table->boolean('buy_stop_max_follow')->default(0);
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::table('wallet', function (Blueprint $table) {
            $table->dropColumn('buy_stop_max_follow');
        });
    }
};
