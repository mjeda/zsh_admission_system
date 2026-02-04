<?php

namespace App\Models;

use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intake extends Model
{
    use HasFactory, Loggable;
    protected $fillable = ["title", "expire_date", "round", "status","year", "type"];

    public function admissions(){
        return $this->hasMany(Admission::class);
    }
}
