<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobApplication extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'job_post_id',
        'user_id',
        'job_seeker_profile_id',
        'resume',
        'cover_letter',
        'status',
        'recruiter_notes',
        'status_updated_at',
    ];

    protected $casts = [
        'status_updated_at' => 'datetime',
    ];

    const STATUS_APPLIED             = 'applied';
    const STATUS_UNDER_REVIEW        = 'under_review';
    const STATUS_SHORTLISTED         = 'shortlisted';
    const STATUS_INTERVIEW_SCHEDULED = 'interview_scheduled';
    const STATUS_REJECTED            = 'rejected';
    const STATUS_HIRED               = 'hired';
    const STATUS_WITHDRAWN           = 'withdrawn';

    // ─── Relationships ───────────────────────────────────────────────────────
    public function jobPost()
    {
        return $this->belongsTo(JobPost::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seekerProfile()
    {
        return $this->belongsTo(JobSeekerProfile::class, 'job_seeker_profile_id');
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────
    public function scopeShortlisted($query)
    {
        return $query->where('status', self::STATUS_SHORTLISTED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────
    public function updateStatus(string $status, ?string $notes = null): void
    {
        $this->update([
            'status'            => $status,
            'recruiter_notes'   => $notes ?? $this->recruiter_notes,
            'status_updated_at' => now(),
        ]);
    }

    public function getResumeUrlAttribute(): ?string
    {
        return $this->resume ? asset('storage/' . $this->resume) : null;
    }
}