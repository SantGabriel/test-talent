<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // users
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email',100)->unique();
            $table->string('password');
            $table->string('role');
            $table->timestamps();
        });

        // gateways
        Schema::create('gateways', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->integer('priority')->default(0);
            $table->timestamps();
        });

        // clients
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamps();
        });

        // products
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('amount', 10, 2);
            $table->timestamps();
        });

        // transactions
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client')->constrained('clients','id');
            $table->foreignId('gateway')->constrained('gateways','id');
            $table->string('external_id')->nullable();
            $table->string('status');
            $table->decimal('amount', 10, 2);
            $table->string('card_last_numbers', 4)->nullable();
            $table->timestamps();
        });

        // transaction_products
        Schema::create('transaction_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists('transaction_products');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('products');
        Schema::dropIfExists('clients');
        Schema::dropIfExists('gateways');
        Schema::dropIfExists('users');
    }
};
