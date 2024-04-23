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
        $this->upTables();
        $this->upKeys();
    }

    /**
     * @return void
     */
    protected function upTables(): void
    {
        Schema::create('currency', function (Blueprint $table) {
            $table->id();

            $table->string('code')->index();
            $table->string('name');
            $table->string('symbol');

            $table->unsignedTinyInteger('precision');

            $table->boolean('trade')->default(0);

            $this->dateTimeCreatedAt($table);
            $this->dateTimeUpdatedAt($table);

            $table->unsignedBigInteger('platform_id');
        });

        Schema::create('exchange', function (Blueprint $table) {
            $table->id();

            $table->double('exchange');

            $this->dateTimeCreatedAt($table)->index();

            $table->unsignedBigInteger('platform_id');
            $table->unsignedBigInteger('product_id');
        });

        Schema::create('ip_lock', function (Blueprint $table) {
            $table->id();

            $table->string('ip')->default('');

            $table->dateTime('end_at')->nullable();

            $this->dateTimeCreatedAt($table);
            $this->dateTimeUpdatedAt($table);
        });

        Schema::create('language', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('iso')->unique();

            $table->boolean('default')->default(0);
            $table->boolean('enabled')->default(0);
        });

        Schema::create('order', function (Blueprint $table) {
            $table->id();

            $table->string('code')->index();
            $table->string('reference')->nullable()->index();

            $table->double('amount')->default(0);
            $table->double('price')->default(0);
            $table->double('price_stop')->default(0);
            $table->double('value')->default(0);
            $table->double('fee')->default(0);

            $table->string('type');
            $table->string('status');
            $table->string('side');

            $table->boolean('filled')->default(0);
            $table->boolean('custom')->default(0);

            $table->dateTime('created_at');
            $table->dateTime('updated_at');

            $table->unsignedBigInteger('platform_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('wallet_id')->nullable();
        });

        Schema::create('platform', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique();
            $table->string('name');
            $table->string('url');

            $table->float('fee', 5, 3)->default(0);

            $table->boolean('enabled')->default(0);
            $table->boolean('trailing_stop')->default(0);

            $this->dateTimeCreatedAt($table);
            $this->dateTimeUpdatedAt($table);
        });

        Schema::create('platform_user', function (Blueprint $table) {
            $table->id();

            $table->json('settings')->nullable();

            $this->dateTimeCreatedAt($table);
            $this->dateTimeUpdatedAt($table);

            $table->unsignedBigInteger('platform_id');
            $table->unsignedBigInteger('user_id');
        });

        Schema::create('product', function (Blueprint $table) {
            $table->id();

            $table->string('code')->index();
            $table->string('name');
            $table->string('acronym');

            $table->unsignedSmallInteger('precision')->default(0);

            $table->double('price_min')->default(0);
            $table->double('price_max')->default(0);
            $table->unsignedSmallInteger('price_decimal')->default(0);

            $table->double('quantity_min')->default(0);
            $table->double('quantity_max')->default(0);
            $table->unsignedSmallInteger('quantity_decimal')->default(0);

            $table->double('ask_price')->default(0);
            $table->double('ask_quantity')->default(0);
            $table->double('ask_sum')->default(0);

            $table->double('bid_price')->default(0);
            $table->double('bid_quantity')->default(0);
            $table->double('bid_sum')->default(0);

            $table->boolean('crypto')->default(0);
            $table->boolean('trade')->default(0);
            $table->boolean('tracking')->default(0);
            $table->boolean('enabled')->default(0);

            $this->dateTimeCreatedAt($table);
            $this->dateTimeUpdatedAt($table);

            $table->unsignedBigInteger('currency_base_id');
            $table->unsignedBigInteger('currency_quote_id');
            $table->unsignedBigInteger('platform_id');
        });

        Schema::create('product_user', function (Blueprint $table) {
            $table->id();

            $table->boolean('favorite')->default(0);

            $this->dateTimeCreatedAt($table);
            $this->dateTimeUpdatedAt($table);

            $table->unsignedBigInteger('platform_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('user_id');
        });

        Schema::create('queue_fail', function (Blueprint $table) {
            $table->id();

            $table->text('connection');
            $table->text('queue');

            $table->longText('payload');
            $table->longText('exception');

            $table->timestamp('failed_at')->useCurrent();

            $this->dateTimeCreatedAt($table);
            $this->dateTimeUpdatedAt($table);
        });

        Schema::create('ticker', function (Blueprint $table) {
            $table->id();

            $table->double('amount');

            $table->double('exchange_reference')->default(0);
            $table->double('exchange_current')->default(0);
            $table->double('exchange_min')->default(0);
            $table->double('exchange_max')->default(0);

            $table->double('value_reference')->default(0);
            $table->double('value_current')->default(0);
            $table->double('value_min')->default(0);
            $table->double('value_max')->default(0);

            $table->dateTime('date_at');

            $table->dateTime('exchange_min_at')->nullable();
            $table->dateTime('exchange_max_at')->nullable();

            $table->boolean('enabled')->default(0);

            $this->dateTimeCreatedAt($table);
            $this->dateTimeUpdatedAt($table);

            $table->unsignedBigInteger('currency_id');
            $table->unsignedBigInteger('platform_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('user_id');
        });

        Schema::create('transaction', function (Blueprint $table) {
            $table->id();

            $table->string('code')->index();
            $table->string('type');
            $table->string('status');

            $table->double('price');
            $table->double('amount');
            $table->double('subtotal');
            $table->double('fee');
            $table->double('total');

            $table->dateTime('created_at');
            $table->dateTime('updated_at');

            $table->unsignedBigInteger('currency_id');
            $table->unsignedBigInteger('platform_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('wallet_id');
        });

        Schema::create('transaction_quote', function (Blueprint $table) {
            $table->id();

            $table->string('status');

            $table->double('exchange');
            $table->double('buy')->default(0);
            $table->double('reference')->default(0);

            $table->double('price');
            $table->double('amount');
            $table->double('subtotal');
            $table->double('fee');
            $table->double('total');

            $table->dateTime('created_at');

            $table->unsignedBigInteger('currency_id');
            $table->unsignedBigInteger('exchange_id');
            $table->unsignedBigInteger('platform_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('wallet_id');
        });

        Schema::create('user', function (Blueprint $table) {
            $table->id();

            $table->string('email')->unique();
            $table->string('password');
            $table->string('code');
            $table->string('remember_token')->nullable();
            $table->string('ip');
            $table->string('tfa_secret')->nullable()->unique();

            $table->json('preferences')->nullable();

            $table->double('investment')->default(0);

            $table->boolean('tfa_enabled')->default(0);
            $table->boolean('admin')->default(0);
            $table->boolean('enabled')->default(0);

            $this->dateTimeCreatedAt($table);
            $this->dateTimeUpdatedAt($table);

            $table->unsignedBigInteger('language_id');
        });

        Schema::create('user_session', function (Blueprint $table) {
            $table->id();

            $table->string('ip')->index();

            $table->boolean('success')->default(0);

            $this->dateTimeCreatedAt($table);
            $this->dateTimeUpdatedAt($table);

            $table->unsignedBigInteger('user_id')->nullable();
        });

        Schema::create('wallet', function (Blueprint $table) {
            $table->id();

            $table->string('address')->index();
            $table->string('name');

            $table->unsignedSmallInteger('order')->default(0);

            $table->double('amount');

            $table->double('buy_exchange')->default(0);
            $table->double('buy_value')->default(0);

            $table->double('current_exchange')->default(0);
            $table->double('current_value')->default(0);

            // Sell Stop Enabled
            $table->boolean('sell_stop')->default(0);

            // Sell Stop Exchange Reference
            $table->boolean('sell_stop_reference')->default(0);

            // Amount available to sell
            $table->double('sell_stop_amount')->default(0);

            // We need to reach this price before sell
            $table->double('sell_stop_max_exchange')->default(0);
            $table->double('sell_stop_max_value')->default(0);
            $table->double('sell_stop_max_percent')->default(0);
            $table->dateTime('sell_stop_max_at')->nullable();
            $table->boolean('sell_stop_max_executable')->default(0);

            // We will sell at this price only if sell_stop_max_exchange is reached
            $table->double('sell_stop_min_exchange')->default(0);
            $table->double('sell_stop_min_value')->default(0);
            $table->double('sell_stop_min_percent')->default(0);
            $table->dateTime('sell_stop_min_at')->nullable();
            $table->boolean('sell_stop_min_executable')->default(0);

            // Buy Stop Enabled
            $table->boolean('buy_stop')->default(0);

            // Buy Stop Exchange Reference
            $table->boolean('buy_stop_reference')->default(0);

            // Amount available to spend
            $table->double('buy_stop_amount')->default(0);

            // We will buy at this price only if buy_stop_min_exchange is reached
            $table->double('buy_stop_max_exchange')->default(0);
            $table->double('buy_stop_max_value')->default(0);
            $table->double('buy_stop_max_percent')->default(0);
            $table->dateTime('buy_stop_max_at')->nullable();
            $table->boolean('buy_stop_max_executable')->default(0);

            // We need to reach this price before buy
            $table->double('buy_stop_min_exchange')->default(0);
            $table->double('buy_stop_min_value')->default(0);
            $table->double('buy_stop_min_percent')->default(0);
            $table->dateTime('buy_stop_min_at')->nullable();
            $table->boolean('buy_stop_min_executable')->default(0);

            $table->boolean('sell_stoploss')->default(0);

            $table->double('sell_stoploss_exchange')->default(0);
            $table->double('sell_stoploss_value')->default(0);
            $table->double('sell_stoploss_percent')->default(0);

            $table->dateTime('sell_stoploss_at')->nullable();
            $table->boolean('sell_stoploss_executable')->default(0);

            $table->boolean('custom')->default(0);
            $table->boolean('crypto')->default(0);
            $table->boolean('trade')->default(0);
            $table->boolean('visible')->default(0);
            $table->boolean('enabled')->default(0);

            $table->dateTime('processing_at')->nullable();

            $this->dateTimeCreatedAt($table);
            $this->dateTimeUpdatedAt($table);

            $table->unsignedBigInteger('currency_id');
            $table->unsignedBigInteger('order_buy_stop_id')->nullable();
            $table->unsignedBigInteger('order_sell_stop_id')->nullable();
            $table->unsignedBigInteger('platform_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('user_id');
        });

        Schema::create('wallet_history', function (Blueprint $table) {
            $table->id();

            $table->string('address')->index();
            $table->string('name');

            $table->json('payload')->nullable();

            $this->dateTimeCreatedAt($table);
            $this->dateTimeUpdatedAt($table);

            $table->unsignedBigInteger('currency_id');
            $table->unsignedBigInteger('platform_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('wallet_id');
        });
    }

    /**
     * @return void
     */
    protected function upKeys(): void
    {
        Schema::table('currency', function (Blueprint $table) {
            $table->unique(['code', 'platform_id']);

            $this->foreignOnDeleteCascade($table, 'platform');
        });

        Schema::table('exchange', function (Blueprint $table) {
            $table->index(['created_at', 'product_id']);

            $this->foreignOnDeleteCascade($table, 'platform');
            $this->foreignOnDeleteCascade($table, 'product');
        });

        Schema::table('order', function (Blueprint $table) {
            $this->foreignOnDeleteCascade($table, 'platform');
            $this->foreignOnDeleteCascade($table, 'product');
            $this->foreignOnDeleteCascade($table, 'user');
            $this->foreignOnDeleteSetNull($table, 'wallet');
        });

        Schema::table('platform_user', function (Blueprint $table) {
            $this->foreignOnDeleteCascade($table, 'platform');
            $this->foreignOnDeleteCascade($table, 'user');
        });

        Schema::table('product', function (Blueprint $table) {
            $this->foreignOnDeleteCascade($table, 'currency', 'currency_base_id');
            $this->foreignOnDeleteCascade($table, 'currency', 'currency_quote_id');
            $this->foreignOnDeleteCascade($table, 'platform');
        });

        Schema::table('product_user', function (Blueprint $table) {
            $this->foreignOnDeleteCascade($table, 'platform');
            $this->foreignOnDeleteCascade($table, 'product');
            $this->foreignOnDeleteCascade($table, 'user');
        });

        Schema::table('ticker', function (Blueprint $table) {
            $this->foreignOnDeleteCascade($table, 'currency');
            $this->foreignOnDeleteCascade($table, 'platform');
            $this->foreignOnDeleteCascade($table, 'product');
            $this->foreignOnDeleteCascade($table, 'user');
        });

        Schema::table('transaction', function (Blueprint $table) {
            $this->foreignOnDeleteCascade($table, 'currency');
            $this->foreignOnDeleteCascade($table, 'platform');
            $this->foreignOnDeleteCascade($table, 'user');
            $this->foreignOnDeleteCascade($table, 'wallet');
        });

        Schema::table('transaction_quote', function (Blueprint $table) {
            $this->foreignOnDeleteCascade($table, 'currency');
            $this->foreignOnDeleteCascade($table, 'exchange');
            $this->foreignOnDeleteCascade($table, 'platform');
            $this->foreignOnDeleteCascade($table, 'user');
            $this->foreignOnDeleteCascade($table, 'wallet');
        });

        Schema::table('user', function (Blueprint $table) {
            $this->foreignOnDeleteCascade($table, 'language');
        });

        Schema::table('user_session', function (Blueprint $table) {
            $this->foreignOnDeleteCascade($table, 'user');
        });

        Schema::table('wallet', function (Blueprint $table) {
            $this->foreignOnDeleteCascade($table, 'currency');
            $this->foreignOnDeleteSetNull($table, 'order', 'order_buy_stop_id');
            $this->foreignOnDeleteSetNull($table, 'order', 'order_sell_stop_id');
            $this->foreignOnDeleteCascade($table, 'platform');
            $this->foreignOnDeleteCascade($table, 'product');
            $this->foreignOnDeleteCascade($table, 'user');
        });

        Schema::table('wallet_history', function (Blueprint $table) {
            $this->foreignOnDeleteCascade($table, 'currency');
            $this->foreignOnDeleteCascade($table, 'platform');
            $this->foreignOnDeleteCascade($table, 'product');
            $this->foreignOnDeleteCascade($table, 'user');
            $this->foreignOnDeleteCascade($table, 'wallet');
        });
    }
};
