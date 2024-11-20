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
        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            $table->integer('rate');
            $table->text('comment')->nullable();
            $table->integer('like')->default(0);
            $table->boolean('is_confirmed')->default(false);
           $table->foreignId('event_id')->constrained('events')->onDelete('cascade');//done
           $table->foreignId('user_id')->constrained('users')->onDelete('cascade');//done
           $table->foreignId('place_id')->constrained('places')->onDelete('cascade');//done
           $table->foreignId('planner_id')->constrained('planners')->onDelete('cascade');//done
        //    $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');//done
        //    $table->foreignId('company_id')->nullable()->constrained()->onDelete('set null');
        //    $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');//done

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rates');
    }
};
