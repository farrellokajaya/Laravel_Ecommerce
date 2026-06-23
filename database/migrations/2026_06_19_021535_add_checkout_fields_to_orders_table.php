<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('receiver_name')->nullable()->after('id');
            $table->integer('quantity')->default(1)->after('product_id');
            $table->decimal('unit_price', 10, 2)->default(0)->after('quantity');
            $table->decimal('total_price', 10, 2)->default(0)->after('unit_price');
            $table->string('stripe_payment_id')->nullable()->after('payment_status');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'receiver_name',
                'quantity',
                'unit_price',
                'total_price',
                'stripe_payment_id',
            ]);
        });
    }
};