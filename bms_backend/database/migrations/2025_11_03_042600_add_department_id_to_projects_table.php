<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->after('branch_id')->constrained('departments')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
        });
    }
};