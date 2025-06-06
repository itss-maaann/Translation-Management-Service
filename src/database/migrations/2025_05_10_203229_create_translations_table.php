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
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId('locale_id')
                ->constrained('locales')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('key')->index();
            $table->text('value');
            $table->timestamps();
            $table->unique(['locale_id', 'key']);
            if (Schema::getConnection()->getDriverName() === 'mysql') {
                $table->fullText('value');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
