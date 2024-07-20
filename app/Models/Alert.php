<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'pet_id',
        'detection',
        'confidence',
        'img_url'
    ];

    public function getCreatedAtForHumansAttribute()
    {
        Carbon::setLocale('pt_BR');
        return Carbon::parse($this->attributes['created_at'])->diffForHumans();
    }
}
