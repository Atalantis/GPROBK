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
        Schema::create('custom_field_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('custom_field_definition_id')->constrained()->onDelete('cascade');
            $table->morphs('customizable'); // customizable_id and customizable_type
            $table->text('value')->nullable();
            $table->timestamps();

            $table->unique(['custom_field_definition_id', 'customizable_id', 'customizable_type'], 'custom_field_value_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_field_values');
    }
};
