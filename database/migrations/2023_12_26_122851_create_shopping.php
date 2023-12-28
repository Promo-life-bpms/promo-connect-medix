<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopping extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shoppings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('address_id')->references('id')->on('addresses');
            $table->boolean('iva_by_item')->default(false);
            $table->boolean('show_total')->default(true);
            $table->text('logo')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
        });

        Schema::create('shopping_information', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('landline');
            $table->string('cell_phone');
            $table->string('oportunity');
            $table->string('rank');
            $table->string('department')->nullable();
            $table->text('information')->nullable();
            $table->decimal('tax_fee', 10, 2)->nullable();
            $table->integer('shelf_life')->nullable();
            $table->timestamps();
        });

        Schema::create('shopping_techniques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shopping_id')->constrained('shoppings');
            $table->string('name')->nullable();
            $table->string('material')->nullable();
            $table->string('technique')->nullable();
            $table->string('size')->nullable();
            $table->timestamps();
        });


        Schema::create('shopping_products', function (Blueprint $table) {
            $table->id();
            $table->text('product');
            $table->text('technique');
            $table->decimal('prices_techniques', 8, 2)->nullable();
            $table->integer('color_logos');
            $table->decimal('costo_indirecto', 8, 2);
            $table->decimal('utilidad', 8, 2)->nullable();
            $table->integer('dias_entrega');
            $table->integer('cantidad')->nullable();
            $table->decimal('precio_unitario', 8, 2)->nullable();
            $table->decimal('precio_total', 12, 2)->nullable();
            $table->boolean('shopping_by_scales')->default(false);
            $table->text('scales_info')->nullable();
            $table->foreignId('shopping_id')->constrained('shoppings');
            $table->timestamps();
        });

        Schema::create('shopping_discounts', function (Blueprint $table) {
            $table->id();
            $table->boolean('discount')->default(false);
            $table->string('type')->nullable();
            $table->decimal('value', 10, 2)->nullable();
            $table->timestamps();
        });

        Schema::create('shopping_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shopping_id')->constrained('shoppings');
            $table->foreignId('shopping_information_id')->constrained('shopping_information');
            $table->foreignId('shopping_discount_id')->constrained('shopping_discounts');
            $table->string('type');
            $table->timestamps();
        });

        Schema::create('shopping_update_product', function (Blueprint $table) {
            $table->foreignId('shopping_update_id')->constrained('shopping_updates');
            $table->foreignId('shopping_product_id')->constrained('shopping_products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shopping_update_product');
        Schema::dropIfExists('shopping_updates');
        Schema::dropIfExists('shopping_discounts');
        Schema::dropIfExists('shopping_products');
        Schema::dropIfExists('shopping_techniques');
        Schema::dropIfExists('shopping_information');
        Schema::dropIfExists('shopping');
        
    }
}
