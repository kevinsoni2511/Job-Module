<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobSeekerEducation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','degree','institution','field',
        'start_year','end_year','is_current','description',
    ];

    protected $casts = ['is_current' => 'boolean'];

    public function user() { return $this->belongsTo(User::class); }
}