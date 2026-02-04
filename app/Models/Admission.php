<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    use HasFactory, Loggable;
    use Searchable;

    protected $searchableFields = ['*'];

    protected $guarded = false;

    public function intake(){
        return $this->belongsTo(Intake::class);
    }

    public function selectedCourse(){
        // return $this->hasOne(SelectedCourse::class);
        return $this->hasMany(SelectedCourse::class);
    }

    public function controlNumber(){
        return $this->hasOne(ControlNumber::class)->where('type', 'APPLICATION');
    }

    public function registrationControlNumber()
    {
        return $this->hasOne(ControlNumber::class)->where('type', 'REGISTRATION');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function getFullNameAttribute()
    {
        return $this->first_name .' '. $this->last_name;
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function selection()
    {
        return $this->hasOne(Selection::class);
    }

    public function maleAdmissions()
    {
        Admission::query();
    }
}
