<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('subtasks', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('assignee_id');
        });
    }

    public function down()
    {
        Schema::table('subtasks', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
