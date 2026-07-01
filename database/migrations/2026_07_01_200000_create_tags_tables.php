<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tag_batches', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('product_type', 50)->default('plate');
            $table->unsignedInteger('quantity');
            $table->timestamp('manufactured_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tag_batch_id')->constrained()->cascadeOnDelete();
            $table->string('public_token', 64)->unique();
            $table->string('status', 20)->default('manufactured');
            $table->timestamp('qr_printed_at')->nullable();
            $table->timestamp('nfc_programmed_at')->nullable();
            $table->timestamps();

            $table->index('status');
        });

        Schema::create('tag_activation_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tag_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('code_hash');
            $table->timestamp('used_at')->nullable();
            $table->foreignId('used_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('tag_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pet_id')->constrained()->cascadeOnDelete();
            $table->timestamp('assigned_at');
            $table->timestamp('unassigned_at')->nullable();
            $table->string('reason')->nullable();
            $table->timestamps();

            $table->index(['tag_id', 'unassigned_at']);
            $table->index(['pet_id', 'unassigned_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tag_assignments');
        Schema::dropIfExists('tag_activation_codes');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('tag_batches');
    }
};
