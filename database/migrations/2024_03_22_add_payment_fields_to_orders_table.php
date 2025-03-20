<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('order_type', ['dine-in', 'online'])->default('dine-in')->after('status');
            $table->enum('payment_status', ['pending', 'completed'])->default('pending')->after('order_type');
            $table->string('customer_phone')->nullable()->change();
            $table->text('address')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['order_type', 'payment_status']);
            $table->string('customer_phone')->nullable(false)->change();
            $table->text('address')->nullable(false)->change();
        });
    }
};
