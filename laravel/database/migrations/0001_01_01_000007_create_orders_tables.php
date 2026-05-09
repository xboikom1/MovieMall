<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('session_id', 128)->nullable();
            $table->string('guest_email', 254)->nullable();
            $table->string('guest_first_name', 60)->nullable();
            $table->string('guest_last_name', 60)->nullable();
            $table->string('guest_phone', 16)->nullable();
            $table->enum('status', ['draft', 'paid'])->default('draft');
            $table->enum('delivery_type', ['courier', 'locker', 'pickup'])->nullable();
            $table->foreignId('delivery_address_id')->nullable()->constrained('delivery_addresses')->nullOnDelete();
            $table->string('shipping_street', 150)->nullable();
            $table->string('shipping_city', 100)->nullable();
            $table->string('shipping_postal_code', 20)->nullable();
            $table->string('shipping_country', 100)->nullable();
            $table->decimal('subtotal', 10, 2)->nullable();
            $table->decimal('shipping_cost', 10, 2)->nullable();
            $table->decimal('tax', 10, 2)->nullable();
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('order_tickets', function (Blueprint $table) {
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('ticket_id')->constrained('tickets')->cascadeOnDelete();
            $table->primary(['order_id', 'ticket_id']);
        });

        Schema::create('order_souvenirs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('souvenir_id')->constrained('souvenirs')->cascadeOnDelete();
            $table->decimal('price', 10, 2)->notNull();
            $table->integer('quantity')->notNull();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('invoice_number', 50)->notNull();
            $table->timestamp('issued_at')->notNull();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('order_souvenirs');
        Schema::dropIfExists('order_tickets');
        Schema::dropIfExists('orders');
    }
};
