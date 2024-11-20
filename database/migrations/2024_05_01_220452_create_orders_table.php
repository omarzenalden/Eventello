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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->decimal('cost_planner', 8, 2)->default(0.00);
            $table->enum('payment_status', ['paid', 'unpaid'])->default('unpaid');
            $table->date('date_of_expiration')->nullable(); // يمكن تعيين تاريخ انتهاء الصلاحية
            $table->unsignedBigInteger('pending_request_id');
            $table->foreign('pending_request_id')->references('id')->on('pending_requests')->onDelete('cascade');
            $table->unsignedBigInteger('requirement_id')->nullable();
           $table->foreign('requirement_id')->references('id')->on('requirements')->onDelete('cascade');//done
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('pending_requests');
    }
};

