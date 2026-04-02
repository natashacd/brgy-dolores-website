<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedule_summon', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')
                ->constrained('lupon_cases')
                ->cascadeOnDelete();
            $table->string('date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_summon');
    }
};
