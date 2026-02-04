<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlNumber extends Model
{
    use HasFactory;
    use Searchable;
    protected $searchableFields = ['control_number'];

    protected $fillable = ["admission_id", "control_number", "amount", "expire_date", "type"];

    protected $dates = ['expire_date'];

    public function admission(){
        return $this->belongsTo(Admission::class);
    }
    public function payment(){
        return $this->hasOne(Payment::class);
    }
}
