<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('announcement', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->string('category'); // event, advisory, emergency, meeting, program
            $table->string('type')->default('announcement'); // announcement, notice, alert
            $table->string('status')->default('published'); // published, draft, archived
            $table->foreignId('created_by')->constrained('users');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_urgent')->default(false);
            $table->integer('views')->default(0);
            $table->string('featured_image')->nullable();
            $table->json('attachments')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes for faster queries
            $table->index('category');
            $table->index('status');
            $table->index('published_at');
            $table->index('is_urgent');
        });
    }

    public function down()
    {
        Schema::dropIfExists('announcement');
    }
};