<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'icon', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($m) {
            if (empty($m->slug)) {
                $m->slug = strtolower(trim(preg_replace('/[^A-Za-z0-9]+/', '-', $m->name), '-'));
            }
        });
    }

    public function jobPosts() { return $this->hasMany(JobPost::class); }

    public function scopeActive($q) { 
        return $q->where('is_active', true);
    }
}