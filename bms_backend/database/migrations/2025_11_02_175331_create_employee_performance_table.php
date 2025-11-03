<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_performance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('users')->onDelete('cascade');
            $table->decimal('total_score', 5, 2)->default(0); // 0-100 scale
            $table->enum('rating_label', ['weak', 'average', 'strong', 'excellent'])->default('average');
            $table->json('breakdown')->nullable(); // Store detailed scores
            $table->timestamp('last_updated_at');
            $table->timestamps();

            $table->unique('employee_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_performance');
    }
};
