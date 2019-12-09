<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchandiseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchandise', function (Blueprint $table) {
            $table->increments('id');

            // - C (Create)：建立中
            // - S (Sell)：可販售
            $table->string('status', 1)->default('C');
            $table->string('name', 80)->nullable();
            $table->text('introduction');
            $table->text('introduction_en');
            $table->string('photo', 50)->nullable();
            $table->integer('price')->default(0);

            // 商品剩餘數量
            $table->integer('remain_count')->default(0);
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
        Schema::dropIfExists('merchandise');
    }
}
