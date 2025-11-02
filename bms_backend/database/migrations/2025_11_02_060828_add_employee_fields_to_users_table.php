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
        Schema::table('users', function (Blueprint $table) {
            $table->string('designation')->nullable()->after('phone');
            $table->enum('performance_rating', ['weak', 'average', 'good', 'excellent'])->default('average')->after('designation');
            $table->date('join_date')->nullable()->after('performance_rating');
            $table->foreignId('manager_id')->nullable()->constrained('users')->onDelete('set null')->after('join_date');
            $table->text('address')->nullable()->after('manager_id');
            $table->date('date_of_birth')->nullable()->after('address');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('date_of_birth');
            $table->decimal('salary', 10, 2)->nullable()->after('gender');
            $table->string('grade')->nullable()->after('salary');
            $table->json('documents')->nullable()->after('grade'); // Store document paths/IDs
            $table->json('performance_history')->nullable()->after('documents'); // Store performance records
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['manager_id']);
            $table->dropColumn([
                'designation',
                'performance_rating',
                'join_date',
                'manager_id',
                'address',
                'date_of_birth',
                'gender',
                'salary',
                'grade',
                'documents',
                'performance_history'
            ]);
        });
    }
};
