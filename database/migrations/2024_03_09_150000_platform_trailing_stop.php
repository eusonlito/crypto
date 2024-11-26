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
        return Schema::hasColumn('platform', 'trailing_stop');
    }

    /**
     * @return void
     */
    protected function upTables(): void
    {
        Schema::table('platform', function (Blueprint $table) {
            $table->boolean('trailing_stop')->default(0);
        });
    }

    /**
     * @return void
     */
    protected function upUpdate(): void
    {
        $this->db()->statement('
            UPDATE `platform`
            SET `trailing_stop` = TRUE
            WHERE `code` = "binance";
        ');
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::table('platform', function (Blueprint $table) {
            $table->dropColumn('trailing_stop');
        });
    }
};
