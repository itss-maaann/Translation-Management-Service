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
        Schema::create('translation_tag', function (Blueprint $table): void {
            $table
                ->foreignId('translation_id')
                ->constrained('translations')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table
                ->foreignId('tag_id')
                ->constrained('tags')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->primary(['translation_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translation_tag');
    }
};
