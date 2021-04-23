<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreProductTable extends Migration
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
            $table->double('weight', 11, 2);
            $table->double('height', 11, 2);
            $table->integer('quantity');
            $table->integer('sold');
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

//TODO: write migration for albums table

        Schema::create('store_product_reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->text('description');
            $table->integer('owner_id');
            $table->integer('product_id');
            $table->integer('rating')->default(0);
            $table->integer('is_revised')->default(0);
            $table->integer('is_revise_request')->default(0);
            $table->timestamps();
        });

        Schema::create('store_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id');
            $table->integer('seller_id');
            $table->integer('delivery_address_id');
            $table->integer('payment_type');
            $table->enum('is_deleted', [0,1])->default(0);
            $table->integer('status');
            $table->float('total_price', 11, 2);
            $table->float('total_shiping_cost', 11, 2);
            $table->float('total_discount', 11, 2);
            $table->float('total_quantity', 11, 2);
            $table->dateTime('approved_date');
            $table->dateTime('shiping_date');
            $table->dateTime('received_date');
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

            $table->enum('is_deleted', [0, 1])->default(0);

            $table->string('first_name');
            $table->string('last_name');

            // Its mandatory to unsigned foreign key.
            $table->integer('order_id')->unsigned();
            $table->integer('user_id')->unsigned();

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


        Schema::create('store_order_dispute', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('is_order_recieved');//is_order_recieved
            $table->integer('payment_claimed');//amount_claimed
            $table->text('reason');//
            $table->text('detail');
            $table->text('reason_attachment');
            $table->integer('owner_id');
            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')
                  ->references('id')
                  ->on('store_orders')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('store_order_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('order_id');
            $table->integer('gateway_id');
            $table->string('gateway_timestamp');
            $table->string('type' , 255);
            $table->string('state' , 255);
            $table->string('gateway_transaction_id', 255);
            $table->string('gateway_parent_transaction_id', 255);
            $table->string('gateway_order_id', 255);
            $table->decimal('amount' ,8, 2);
            $table->string('currency', 255);
            $table->timestamps();
        });

        Schema::create('user_withdrawals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('withdrawal_type')->comment = "For example: Order payment refund";
            $table->integer('withdrawal_type_id')->comment = "For example: withdrawal_type_id will be order_id";
            $table->decimal('withdrawal_amount' ,8, 2);
            $table->decimal('refund_amount' ,8, 2);
            $table->timestamps();
        });

        Schema::create('store_albums', function (Blueprint $table) {
            $table->bigIncrements('album_id');
            $table->string('title');
            $table->string('description');
            $table->string('owner_type');
            $table->integer('owner_id');
            $table->integer('category_id');
            $table->integer('photo_id');
            $table->integer('view_count');
            $table->integer('comment_count');
            $table->tinyInteger('search');
            $table->string('type');
            $table->timestamps();
        });

        Schema::create('store_album_photos', function (Blueprint $table) {
            $table->bigIncrements('photo_id');
            $table->integer('album_id')->unsigned();
            $table->string('title');
            $table->integer('parent_id');
            $table->string('description');
            $table->integer('order');
            $table->string('owner_type');
            $table->integer('owner_id');
            $table->integer('file_id');
            $table->integer('view_count');
            $table->integer('comment_count');
            $table->timestamps();
        });

        Schema::create('store_storage_files', function (Blueprint $table) {
            $table->bigIncrements('file_id');
            $table->integer('parent_file_id')->nullable();
            $table->string('type' ,16)->nullable();
            $table->string('parent_type' , 32)->nullable();
            $table->integer('parent_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('storage_path');
            $table->string('extension' , 8);
            $table->string('name')->nullable();
            $table->string('mime_type' , 64);
            $table->string('mime_major' , 50);
            $table->integer('size');
            $table->string('hash' , 64);
            $table->tinyInteger('is_temp');
            $table->timestamps();
        });

        Schema::create('store_order_status_log', function (Blueprint $table) {
            $table->increments( 'id' );
            $table->integer('user_id');
            $table->string('ip' , 255);
            $table->integer('status_changed_from');
            $table->integer('status_changed_to');
            $table->timestamps();
        });

        Schema::create('store_product_statics', function (Blueprint $table) {
            $table->increments( 'id' );

            //Statics type information
            $table->string('stat_type');

            //Viewer (User) information
            $table->integer('user_id');
            $table->integer('user_type');
            $table->integer('user_age');
            $table->integer('user_gender');
            $table->integer('user_region');
            $table->string('user_ip' , 255);

            //Product related information
            $table->integer('product_owner_id');
            $table->integer('product_id');

            $table->timestamps();
        });

        Schema::create('profile_page_stats', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('owner_id');
            $table->timestamps();
        });

        Schema::create('store_order_delivery_info', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('seller_id');
            $table->integer('order_id');

            $table->string('courier_service_name');
            $table->string('courier_service_url');
            $table->string('order_tracking_number');
            $table->string('delivery_estimated_time');
            $table->date('date_to_be_delivered');
            $table->integer('delivery_charges_paid');

            $table->timestamps();
        });

        Schema::create('store_product_shipping_cost', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('region_id');
            $table->foreign('region_id')
                  ->references('id')
                  ->on('store_product_shipping_regions')->onDelete('cascade');

            $table->enum('status', ['1', '0'])->default(1);
            $table->double('shipping_cost');

            $table->integer('product_id');
            $table->foreign('product_id')
                  ->references('id')
                  ->on('store_products')->onDelete('cascade');

            $table->timestamps();
        });

        Schema::create('store_product_shipping_countries', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('product_id');
            $table->foreign('product_id')
                  ->references('id')
                  ->on('store_products')->onDelete('cascade');

            $table->integer('country_id');

            $table->timestamps();
        });

        Schema::create('store_product_shipping_regions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');

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
        Schema::drop('store_order_dispute');
        Schema::drop('store_order_transactions');
        Schema::drop('user_withdrawals');
        Schema::drop('store_albums');
        Schema::drop('store_album_photos');
        Schema::drop('store_storage_files');
        Schema::drop('store_order_status_log');
        Schema::drop('store_product_statics');
        Schema::drop('profile_page_stats');
        Schema::drop('store_order_delivery_info');
        Schema::drop('store_product_shipping_cost');
        Schema::drop('store_product_shipping_countries');
        Schema::drop('store_product_shipping_regions');
    }
}
