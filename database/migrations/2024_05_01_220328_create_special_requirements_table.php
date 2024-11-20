<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('special_requirements', function (Blueprint $table) {
            $table->id();
            $table -> string('food',45);
            $table -> string('decor',45);
            $table -> boolean('photographer',);
            $table -> integer('chairs',);
            $table -> integer('max_value');
            $table -> boolean('lighting');
            $table -> boolean('dj');
            $table -> string('car');
            $table -> boolean('the_band');
            $table->text('comments');
            $table -> string('Additions');
            $table->foreignId('requirement_id')->constrained('requirements')->onDelete('cascade');//done
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('special_requirements');
    }
};
