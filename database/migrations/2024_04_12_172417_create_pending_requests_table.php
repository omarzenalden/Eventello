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
        Schema::create('pending_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('planner_id') ->constrained('planners')->onDelete('cascade');
            $table->foreignId('user_id')    ->constrained('users')   ->onDelete('cascade');
            $table->foreignId('place_id')   ->constrained('places')  ->onDelete('cascade');
            $table->foreignId('event_id')   ->constrained('events')  ->onDelete('cascade');
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_requests');
    }
};
