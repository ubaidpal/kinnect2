<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        Schema::create('store_product_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('owner_id');
            $table->integer('category_parent_id')->default(0);
            $table->timestamps();
        });

        Schema::create('store_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->float('price', 11, 2);
            $table->text('description');
            $table->float('discount', 11, 2);
            $table->float('length', 11, 2);
            $table->double('width', 11, 2);
            $table->double('height', 11, 2);
            $table->integer('quantity');
            $table->integer('owner_id');

            $table->integer('category_id')->unsigned();

            $table->foreign('category_id')
                  ->references('id')
                  ->on('store_product_categories')->onDelete('cascade');

            $table->integer('sub_category_id')->unsigned();
            $table->foreign('sub_category_id')
                  ->references('id')
                  ->on('store_product_categories')->onDelete('cascade');

            $table->timestamps();
        });


        Schema::create('store_product_reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->text('description');
            $table->integer('owner_id');
            $table->integer('product_id');
            $table->integer('rating')->default(0);
            $table->timestamps();
        });

        Schema::create('store_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id');
            $table->integer('delivery_address_id');
            $table->integer('payment_type');
            $table->integer('status');
            $table->float('total', 11, 2);
            $table->float('discount', 11, 2);
            $table->timestamps();
        });

        Schema::create('store_order_items', function (Blueprint $table) {
            $table->increments('id');
            // test
            $table->float('product_price', 11, 2);
            $table->float('product_discount', 11, 2);

            $table->integer('quantity');

            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')
                  ->references('id')
                  ->on('store_products')->onDelete('cascade');

            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')
                  ->references('id')
                  ->on('store_orders')->onDelete('cascade');

            $table->timestamps();
        });

        Schema::create('store_delivery_addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('country_id');

            $table->string('first_name');
            $table->string('last_name');
            // Its mandatory to unsigned foreign key.
            $table->integer('order_id')->unsigned();

            $table->foreign('order_id')
                  ->references('id')
                  ->on('store_orders')->onDelete('cascade');

            $table->text('st_address_1');
            $table->text('st_address_2');
            $table->string('city');
            $table->string('state');
            $table->string('zip_code');
            $table->string('phone_number');
            $table->string('email');
            $table->timestamps();
        });
        Schema::create('store_product_features', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('key_feature_type');

            $table->integer('pr_id')->unsigned();
            $table->string('title');
            $table->text('detail');

            $table->foreign('pr_id')
                  ->references('id')
                  ->on('store_products')->onDelete('cascade');

            $table->timestamps();

        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('store_products');
        Schema::drop('store_product_categories');
        Schema::drop('store_product_reviews');

        Schema::drop('store_orders');
        Schema::drop('store_order_items');
        Schema::drop('store_delivery_addresses');
        Schema::drop('store_product_features');
    }
}
