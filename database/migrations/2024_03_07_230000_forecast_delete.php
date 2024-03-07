<?php declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Domains\Core\Migration\MigrationAbstract;

return new class extends MigrationAbstract {
    /**
     * @return void
     */
    public function up()
    {
        if ($this->upMigrated()) {
            return;
        }

        $this->tables();
    }

    /**
     * @return bool
     */
    protected function upMigrated(): bool
    {
        return Schema::hasTable('forecast') === false;
    }

    /**
     * @return void
     */
    protected function tables()
    {
        Schema::drop('forecast');
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::create('forecast', function (Blueprint $table) {
            $table->id();

            $table->string('side');

            $table->unsignedSmallInteger('version');

            $table->json('keys');
            $table->json('values');

            $table->boolean('valid')->default(0);
            $table->boolean('selected')->default(0);

            $this->dateTimeCreatedAt($table);
            $this->dateTimeUpdatedAt($table);

            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('platform_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('wallet_id')->nullable();
        });

        Schema::table('forecast', function (Blueprint $table) {
            $this->foreignOnDeleteSetNull($table, 'order');
            $this->foreignOnDeleteCascade($table, 'platform');
            $this->foreignOnDeleteCascade($table, 'product');
            $this->foreignOnDeleteCascade($table, 'user');
            $this->foreignOnDeleteSetNull($table, 'wallet');
        });
    }
};
