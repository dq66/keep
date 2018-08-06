<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("is_types")->comment('类型:1收入,2支出');
            $table->integer('types_id')->unsigned()->comment('所属大类');
            $table->foreign('types_id')->references('id')->on('types');
            $table->integer('parent_id')->comment('所属小类');
            $table->double('money')->default(0)->comment('金额');
            $table->integer('accounts_id')->unsigned()->comment('资产账户');
            $table->foreign('accounts_id')->references('id')->on('accounts');
            $table->integer('customers_id')->unsigned()->comment('生意伙伴');
            $table->foreign('customers_id')->references('id')->on('customers');
            $table->integer('projects_id')->unsigned()->comment('业务项目');
            $table->foreign('projects_id')->references('id')->on('projects');
            $table->integer('staffs_id')->unsigned()->comment('经手人');
            $table->foreign('staffs_id')->references('id')->on('staffs');
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
        Schema::dropIfExists('incomes');
    }
}
