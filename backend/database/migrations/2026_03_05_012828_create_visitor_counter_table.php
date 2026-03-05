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
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('page_url')->default('/');
            $table->timestamp('visited_at')->useCurrent();
            $table->timestamps();
            
            // Optional: Add index for faster queries
            $table->index('visited_at');
            $table->index('ip_address');
        });
    }

    public function down()
    {
        Schema::dropIfExists('visitor_counter');
    }
};