<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Chat Sessions (conversations)
        Schema::create('chat_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title')->default('New Chat');
            $table->string('model')->default('gpt-4o');
            $table->boolean('is_vibe_coding')->default(false);
            $table->timestamps();
        });

        // Chat Messages
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_session_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['user', 'assistant']); // user or AI
            $table->longText('content');
            $table->string('attachment_path')->nullable();
            $table->string('attachment_type')->nullable(); // image, pdf
            $table->string('attachment_name')->nullable();
            $table->boolean('has_code')->default(false);
            $table->longText('code_content')->nullable();
            $table->string('code_language')->nullable();
            $table->integer('token_used')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('chat_sessions');
    }
};
