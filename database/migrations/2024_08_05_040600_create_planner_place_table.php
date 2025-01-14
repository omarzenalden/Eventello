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
        Schema::create('planner_place', function (Blueprint $table) {
              $table->id();
              $table->foreignId('place_id')->constrained('places')->onDelete('cascade');
              $table->foreignId('planner_id')->constrained('planners')->onDelete('cascade');
              $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planner_place');
    }
};
