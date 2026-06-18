<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_seeker_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('full_name');
            $table->string('phone')->nullable();
            $table->string('profile_photo')->nullable();
            $table->string('resume')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male','female','other','prefer_not_to_say'])->nullable();
            $table->string('current_job_title')->nullable();
            $table->string('current_company')->nullable();
            $table->integer('total_experience_years')->nullable();
            $table->decimal('current_salary', 12, 2)->nullable();
            $table->decimal('expected_salary', 12, 2)->nullable();
            $table->text('bio')->nullable();
            $table->string('skills')->nullable();
            $table->string('education')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->boolean('is_actively_looking')->default(true);
            $table->boolean('is_profile_visible')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_seeker_profiles');
    }
};