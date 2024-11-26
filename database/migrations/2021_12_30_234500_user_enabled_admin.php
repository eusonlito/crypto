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
        return Schema::hasColumn('user', 'enabled')
            || Schema::hasColumn('user', 'admin');
    }

    /**
     * @return void
     */
    protected function upTables(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $table->boolean('admin')->default(0);
            $table->boolean('enabled')->default(1);
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $table->dropColumn('admin');
            $table->dropColumn('enabled');
        });
    }
};
