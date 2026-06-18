<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_post_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('job_seeker_profile_id')->nullable()->constrained()->nullOnDelete();
            $table->string('resume')->nullable();
            $table->text('cover_letter')->nullable();
            $table->enum('status', ['applied','under_review','shortlisted','interview_scheduled','rejected','hired','withdrawn'])->default('applied');
            $table->text('recruiter_notes')->nullable();
            $table->timestamp('status_updated_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['job_post_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};