<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("is_types")->comment('类型:1转入,2转出');
            $table->float('money')->default(0)->comment('金额');
            $table->integer('turn_out')->comment('转出账户');
            $table->integer('accounts_id')->unsigned()->comment('转入账户');
            $table->foreign('accounts_id')->references('id')->on('accounts');
            $table->integer('users_id')->unsigned()->comment('记账人');
            $table->foreign('users_id')->references('id')->on('users');
            $table->text('desc')->nullable()->comment('说明');
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
        Schema::dropIfExists('transfers');
    }
}
