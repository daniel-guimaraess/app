<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

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

    protected $appends = ['pet_name'];

    protected function petName(): Attribute
    {
        return Attribute::make(
        
        get: fn (mixed $value, array $attributes) => Pet::where('id', $attributes['pet_id'])->value('name'));   
    }
}
