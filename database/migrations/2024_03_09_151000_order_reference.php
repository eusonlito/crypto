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
        return Schema::hasColumn('order', 'reference');
    }

    /**
     * @return void
     */
    protected function upTables(): void
    {
        Schema::table('order', function (Blueprint $table) {
            $table->string('reference')->nullable()->index();
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::table('order', function (Blueprint $table) {
            $table->dropColumn('reference');
        });
    }
};
