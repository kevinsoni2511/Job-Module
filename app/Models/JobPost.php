<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobPost extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id','job_category_id','title','slug','description','requirements',
        'responsibilities','benefits','job_type','work_mode','experience_level',
        'experience_min_years','experience_max_years','salary_min','salary_max',
        'salary_currency','is_salary_visible','location','city','state','country',
        'vacancies','application_deadline','status','published_at','closed_at','views_count',
    ];

    protected $casts = [
        'is_salary_visible'    => 'boolean',
        'application_deadline' => 'date',
        'published_at'         => 'datetime',
        'closed_at'            => 'datetime',
        'salary_min'           => 'decimal:2',
        'salary_max'           => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($m) {
            if (empty($m->slug)) {
                $m->slug = strtolower(trim(preg_replace('/[^A-Za-z0-9]+/', '-', $m->title), '-'))
                    . '-' . substr(md5(uniqid()), 0, 6);
            }
        });
    }

    public function company()      { return $this->belongsTo(Company::class); }
    public function category()     { return $this->belongsTo(JobCategory::class, 'job_category_id'); }
    public function applications() { return $this->hasMany(JobApplication::class); }
    public function savedByUsers() { return $this->belongsToMany(User::class, 'saved_jobs'); }

    public function scopeActive($q)         { return $q->where('status', 'active'); }
    public function scopeClosed($q)         { return $q->where('status', 'closed'); }
    public function scopeSearch($q, $k)
    {
        return $q->where(fn($q) => $q->where('title', 'like', "%{$k}%")
            ->orWhere('description', 'like', "%{$k}%")
            ->orWhere('city', 'like', "%{$k}%"));
    }

    public function incrementViews(): void  { $this->increment('views_count'); }
    public function publish(): void         { $this->update(['status' => 'active', 'published_at' => now()]); }
    public function close(): void           { $this->update(['status' => 'closed', 'closed_at' => now()]); }
}