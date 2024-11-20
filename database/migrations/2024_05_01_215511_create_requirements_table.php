<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequirementsTable extends Migration
{
    public function up()
    {
        Schema::create('requirements', function (Blueprint $table) {
            $table->id();
            $table->string('description', 500)->nullable(false);
            $table->integer('cost_range')->nullable(false);
            $table->string('status', 45)->nullable(false);
            $table->string('date', 45)->nullable(false);
            $table->string('time', 45)->nullable(false);
            $table->string('max_value', 45)->nullable(false);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('requirements');

    }

}
            //order one to one orders
            //order one to one events

