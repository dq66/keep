<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string("name",20)->comment('生意伙伴名称');
            $table->string("telephone",20)->nullable()->comment("联系电话");
            $table->string("fax",20)->nullable()->comment("传真");
            $table->string('email')->nullable()->comment('邮箱');
            $table->text("address")->nullable()->comment("地址");
            $table->text("desc")->nullable()->comment("备注");
            $table->softDeletes();
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
        Schema::dropIfExists('customers');
    }
}
