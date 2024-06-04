<?php declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Domains\Core\Migration\MigrationAbstract;
use App\Domains\Order\Model\Order as OrderModel;

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
        $this->upUpdate();
    }

    /**
     * @return bool
     */
    protected function upMigrated(): bool
    {
        return Schema::hasColumn('order', 'previous_price');
    }

    /**
     * @return void
     */
    protected function upTables(): void
    {
        Schema::table('order', function (Blueprint $table) {
            $table->double('previous_price')->nullable();
            $table->double('previous_value')->nullable();
            $table->double('previous_percent')->nullable();
        });
    }

    /**
     * @return void
     */
    protected function upUpdate(): void
    {
        OrderModel::previousSet();
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::table('order', function (Blueprint $table) {
            $table->dropColumn('previous_price');
            $table->dropColumn('previous_value');
            $table->dropColumn('previous_percent');
        });
    }
};
