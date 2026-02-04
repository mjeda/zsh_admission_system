<?php

namespace App\Models;

use App\Models\Scopes\Searchable;
use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory, Loggable;
    use Searchable;

    protected $searchableFields = ['*'];

    protected $guarded = false;

    public function controlNumber(){
        return $this->belongsTo(ControlNumber::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
