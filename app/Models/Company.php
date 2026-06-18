<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id','name','slug','email','phone','website','logo','cover_image',
        'industry','company_size','founded_year','description','address',
        'city','state','country','pincode','status','verified_at',
    ];

    protected $casts = ['verified_at' => 'datetime'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($m) {
            if (empty($m->slug)) {
                $m->slug = strtolower(trim(preg_replace('/[^A-Za-z0-9]+/', '-', $m->name), '-'))
                    . '-' . substr(md5(uniqid()), 0, 5);
            }
        });
    }

    public function user()        { return $this->belongsTo(User::class); }
    public function jobPosts()    { return $this->hasMany(JobPost::class); }
    public function activeJobPosts() { return $this->hasMany(JobPost::class)->where('status', 'active'); }

    public function scopeActive($q) { 
        return $q->where('status', 'active'); 
    }
    public function scopeSearch($q, $k)
    {
        return $q->where(fn($q) => $q->where('name', 'like', "%{$k}%")
            ->orWhere('industry', 'like', "%{$k}%")
            ->orWhere('city', 'like', "%{$k}%"));
    }
}