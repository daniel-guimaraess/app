<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analysis extends Model
{
    use HasFactory;

    protected $fillable = ['analysis'];

    public function getCreatedAtForHumansAttribute()
    {
        Carbon::setLocale('pt_BR');
        return Carbon::parse($this->attributes['created_at'])->diffForHumans();
    }
}
