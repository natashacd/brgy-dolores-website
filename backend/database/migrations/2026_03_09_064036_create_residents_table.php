<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('resident_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->boolean('status')
                ->default(1);
            $table->timestamps();
        });

        Schema::create('resident_address', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->string('house_no', 100);
            $table->string('purok', 100)
                ->nullable();
            $table->string('sitio', 255);
            $table->text('latitude');
            $table->text('longitude');
            $table->timestamps();
        });

        Schema::create('resident_information', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->string('first_name');
            $table->string('middle_name')
                ->nullable();
            $table->string('last_name');
            $table->string('suffix')
                ->nullable();
            $table->string('contact_number');
            $table->enum('sex', ['Male', 'Female']);
            $table->string('birth_place');
            $table->string('birth_date');
            $table->enum('civil_status', ['Single', 'Married', 'Widowed', 'Annulled', 'Divorced', 'Legally Separated']);
            $table->string('religion')
                ->nullable();
            $table->string('pwd')
                ->nullable();
            $table->string('nationality');
            $table->string('occupation')
                ->nullable();
            $table->string('educational_attainment')
                ->nullable();
            $table->string('emergency_contact_person');
            $table->string('emergency_contact_number');
            $table->string('emergency_contact_relationship');
            $table->string('image_path')
                ->nullable();
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('resident_status');
        Schema::dropIfExists('resident_address');
        Schema::dropIfExists('resident_information');
    }
};
