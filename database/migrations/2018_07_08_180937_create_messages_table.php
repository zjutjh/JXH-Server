<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
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

            $table->string('title')->comment('通知主题');
            $table->string('informer')->comment('通知者');
            $table->longText('content')->comment('通知内容');
            $this->text('notify_content');
            $table->integer('view')->default(0)->comment('阅读次数');
            $table->boolean('is_send')->default(false)->comment('是否发送');
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
        Schema::dropIfExists('messages');
    }
}
