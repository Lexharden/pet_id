<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('species', 20);
            $table->string('breed')->nullable();
            $table->string('sex', 20)->nullable();
            $table->date('birth_date_approx')->nullable();
            $table->string('color')->nullable();
            $table->text('markings')->nullable();
            $table->text('description')->nullable();
            $table->text('approach_instructions')->nullable();
            $table->string('status', 20)->default('home');
            $table->string('primary_photo_path')->nullable();
            $table->string('primary_photo_disk', 50)->nullable();
            $table->text('lost_reward')->nullable();
            $table->text('lost_instructions')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'status']);
        });

        Schema::create('pet_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('disk', 50)->default('pets-private');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            $table->index(['pet_id', 'is_primary']);
        });

        Schema::create('pet_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_id')->constrained()->cascadeOnDelete();
            $table->string('type', 20);
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('show_phone')->default(false);
            $table->boolean('show_whatsapp')->default(false);
            $table->boolean('show_email')->default(false);
            $table->timestamps();
        });

        Schema::create('pet_emergency_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_id')->unique()->constrained()->cascadeOnDelete();
            $table->text('allergies')->nullable();
            $table->text('urgent_medication')->nullable();
            $table->text('condition')->nullable();
            $table->text('immediate_instructions')->nullable();
            $table->string('vet_name')->nullable();
            $table->string('vet_phone')->nullable();
            $table->boolean('show_allergies')->default(false);
            $table->boolean('show_urgent_medication')->default(false);
            $table->boolean('show_condition')->default(false);
            $table->boolean('show_immediate_instructions')->default(false);
            $table->boolean('show_vet')->default(false);
            $table->timestamps();
        });

        Schema::create('pet_public_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_id')->unique()->constrained()->cascadeOnDelete();
            $table->boolean('show_name')->default(true);
            $table->boolean('show_photo')->default(false);
            $table->boolean('show_species')->default(true);
            $table->boolean('show_breed')->default(false);
            $table->boolean('show_sex')->default(false);
            $table->boolean('show_color')->default(false);
            $table->boolean('show_markings')->default(false);
            $table->boolean('show_description')->default(false);
            $table->boolean('show_approach_instructions')->default(false);
            $table->boolean('show_contacts')->default(false);
            $table->boolean('show_emergency')->default(false);
            $table->timestamps();
        });

        Schema::create('user_notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->boolean('scan_alerts')->default(true);
            $table->boolean('found_pet_alerts')->default(true);
            $table->boolean('email_enabled')->default(true);
            $table->unsignedSmallInteger('alert_throttle_minutes')->default(15);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_notification_preferences');
        Schema::dropIfExists('pet_public_settings');
        Schema::dropIfExists('pet_emergency_summaries');
        Schema::dropIfExists('pet_contacts');
        Schema::dropIfExists('pet_photos');
        Schema::dropIfExists('pets');
    }
};
