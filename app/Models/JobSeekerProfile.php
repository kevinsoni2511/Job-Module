<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobSeekerProfile extends Model
{
    use HasFactory, SoftDeletes;

    // ─── Fillable ─────────────────────────────────────────────────────────────
    protected $fillable = [
        'user_id',
        'full_name',
        'phone',
        'profile_photo',
        'resume',
        'date_of_birth',
        'gender',
        'current_job_title',
        'current_company',
        'total_experience_years',
        'current_salary',
        'expected_salary',
        'bio',
        'skills',
        'education',
        'city',
        'state',
        'country',
        'linkedin_url',
        'portfolio_url',
        'is_actively_looking',
        'is_profile_visible',
    ];

    // ─── Casts ────────────────────────────────────────────────────────────────
    protected $casts = [
        'date_of_birth'       => 'date',
        'is_actively_looking' => 'boolean',
        'is_profile_visible'  => 'boolean',
        'current_salary'      => 'decimal:2',
        'expected_salary'     => 'decimal:2',
    ];

    // ─── Appends (auto-include in JSON response) ──────────────────────────────
    protected $appends = [
        'profile_photo_url',
        'resume_url',
        'skills_array',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function educations()
    {
        return $this->hasMany(JobSeekerEducation::class, 'user_id', 'user_id')
                    ->orderBy('start_year', 'desc');
    }

    public function experiences()
    {
        return $this->hasMany(JobSeekerExperience::class, 'user_id', 'user_id')
                    ->orderBy('start_date', 'desc');
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeVisible($query)
    {
        return $query->where('is_profile_visible', true);
    }

    public function scopeActiveLooking($query)
    {
        return $query->where('is_actively_looking', true);
    }

    public function scopeSearch($query, string $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('full_name',          'like', "%{$keyword}%")
              ->orWhere('current_job_title', 'like', "%{$keyword}%")
              ->orWhere('skills',            'like', "%{$keyword}%")
              ->orWhere('city',              'like', "%{$keyword}%");
        });
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    public function getProfilePhotoUrlAttribute(): ?string
    {
        return $this->profile_photo
            ? asset('storage/' . $this->profile_photo)
            : null;
    }

    public function getResumeUrlAttribute(): ?string
    {
        return $this->resume
            ? asset('storage/' . $this->resume)
            : null;
    }

    public function getSkillsArrayAttribute(): array
    {
        return $this->skills
            ? array_map('trim', explode(',', $this->skills))
            : [];
    }

    public function getFullLocationAttribute(): string
    {
        return collect([$this->city, $this->state, $this->country])
            ->filter()
            ->implode(', ');
    }

    public function getAgeAttribute(): ?int
    {
        return $this->date_of_birth
            ? $this->date_of_birth->age
            : null;
    }
}