<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indent_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('indent_id');
            $table->unsignedInteger('product_id');
            $table->integer('qty');
            $table->datetime('accepted_at')->nullable();
            $table->string('accepted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('indent_details');
    }
}
