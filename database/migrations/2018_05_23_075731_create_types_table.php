<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('types', function (Blueprint $table) {//大类
            $table->increments('id');
            $table->integer("is_types")->comment('类型:1收入,2支出');
            $table->integer('parent')->default(0)->comment('小类');
            $table->string("name")->unique()->comment("名称");
            $table->text("desc")->nullable()->comment("说明");
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('types_closure', function (Blueprint $table) {
            $table->unsignedInteger('ancestor');
            $table->unsignedInteger('descendant');
            $table->unsignedTinyInteger('distance');
            $table->primary(['ancestor', 'descendant']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('types');
    }
}
