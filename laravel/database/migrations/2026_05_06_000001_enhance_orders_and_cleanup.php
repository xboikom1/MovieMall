<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('password_reset_tokens');

        Schema::table('order_souvenirs', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->after('souvenir_id');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_street', 150)->nullable()->after('delivery_address_id');
            $table->string('shipping_city', 100)->nullable()->after('shipping_street');
            $table->string('shipping_postal_code', 20)->nullable()->after('shipping_city');
            $table->string('shipping_country', 100)->nullable()->after('shipping_postal_code');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['shipping_street', 'shipping_city', 'shipping_postal_code', 'shipping_country']);
        });

        Schema::table('order_souvenirs', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }
};
