<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuestsTable extends Migration
{
    public function up()
    {
        Schema::create('guests', function (Blueprint $table) {
            $table->id('idguest');
            $table->string('name', 45);
            $table->string('email', 45);
            $table->string('phone', 45);
            $table -> string('address');
            $table->foreignId('requirement_id')->constrained('requirements')->onDelete('cascade');//done
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('guests');
    }
}
