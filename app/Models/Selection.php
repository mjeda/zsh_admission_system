<?php

namespace App\Models;

use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Selection extends Model
{
    use HasFactory, Loggable;

    protected $guarded = false;

    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
