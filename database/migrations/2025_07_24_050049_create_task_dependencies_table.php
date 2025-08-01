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
        Schema::create('task_dependencies', function (Blueprint $table) {
            $table->unsignedBigInteger('prerequisite_task_id');
            $table->unsignedBigInteger('dependent_task_id');

            $table->foreign('prerequisite_task_id')->references('id')->on('tasks')->onDelete('cascade');
            $table->foreign('dependent_task_id')->references('id')->on('tasks')->onDelete('cascade');

            $table->primary(['prerequisite_task_id', 'dependent_task_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_dependencies');
    }
};
