<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scan_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->string('channel', 20);
            $table->timestamp('scanned_at');
            $table->string('ip_hash', 64)->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->string('result', 30);
            $table->boolean('alert_sent')->default(false);
            $table->timestamps();

            $table->index(['tag_id', 'scanned_at']);
            $table->index('scanned_at');
        });

        Schema::create('found_pet_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_id')->constrained()->cascadeOnDelete();
            $table->foreignId('scan_event_id')->nullable()->constrained()->nullOnDelete();
            $table->string('finder_name')->nullable();
            $table->string('finder_phone')->nullable();
            $table->text('message')->nullable();
            $table->string('status', 20)->default('pending');
            $table->timestamp('reported_at');
            $table->timestamps();

            $table->index(['pet_id', 'status']);
        });

        Schema::create('owner_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pet_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 50);
            $table->json('payload')->nullable();
            $table->string('channel', 20)->default('email');
            $table->timestamp('sent_at');
            $table->timestamps();

            $table->index(['user_id', 'sent_at']);
            $table->index(['pet_id', 'type', 'sent_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('owner_alerts');
        Schema::dropIfExists('found_pet_reports');
        Schema::dropIfExists('scan_events');
    }
};
