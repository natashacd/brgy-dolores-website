<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('visitor_counter', function (Blueprint $table) {
            $table->id();
            $table->integer('count')->default(0);
            $table->string('date')->unique(); // Store date as YYYY-MM-DD
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('visitor_counter');
    }
};