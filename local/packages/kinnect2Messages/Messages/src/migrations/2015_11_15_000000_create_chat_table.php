<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('conversation_id');
            $table->integer('user_id');
            $table->string('title');
            $table->text('body');
            $table->string('attachment_type');
            $table->integer('attachment_id');
            $table->timestamps();
        });

        Schema::create('messages_conversations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('recipients');
            $table->string('title');
            $table->tinyInteger('locked');
            $table->string('resource_type');
            $table->integer('resource_id');
            $table->timestamps();
        });

        Schema::create('messages_recipients', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('conversation_id');
            $table->integer('inbox_message_id');
            $table->dateTime('inbox_updated');
            $table->tinyInteger('inbox_read');
            $table->tinyInteger('inbox_deleted');
            $table->tinyInteger('outbox_deleted');
            $table->integer('outbox_message_id');
            $table->dateTime('outbox_updated');
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
        Schema::drop('messages');
        Schema::drop('messages_conversations');
        Schema::drop('messages_recipients');
    }
}
