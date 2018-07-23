<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("is_types")->comment('类型:1应收,2应付');
            $table->integer('types_id')->unsigned()->comment('所属大类');
            $table->foreign('types_id')->references('id')->on('types');
            $table->integer('parent_id')->comment('所属小类');
            $table->integer('money')->default(0)->comment('金额');
            $table->string('term')->comment('期限');
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
        Schema::dropIfExists('payments');
    }
}
