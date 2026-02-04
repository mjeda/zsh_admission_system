<?php

namespace App\Models;

use Haruncpi\LaravelUserActivity\Traits\Loggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory, Loggable;
    protected $fillable = ["code", "tag", "name", "filtered", "passes","status", "type"];

    public function courseCriterias(){
        return $this->hasMany(CourseCriteria::class);
    }

    public function selectCourse(){
        return $this->hasMany(SelectedCourse::class);
    }

    public function selectAdmissionCourse(){
        return $this->hasMany(SelectedCourse::class)->whereHas('admission', function ($admission){
            $admission->where('intake_id', Intake::query()->latest()->first()?->id);
        });
    }

    public function firstSelectAdmissionCourse()
    {
        return $this->hasMany(SelectedCourse::class)->where('priority', 1);
    }

    // Relationship: secondSelectAdmissionCourse with priority 2
    public function secondSelectAdmissionCourse()
    {
        return $this->hasMany(SelectedCourse::class)->where('priority', 2);
    }

    // Relationship: thirdSelectAdmissionCourse with priority 3
    public function thirdSelectAdmissionCourse()
    {
        return $this->hasMany(SelectedCourse::class)->where('priority', 3);
    }

    // Method to get the counts with a specific intake ID
    public static function getCoursesWithAdmissionCounts($intake_id)
    {
        return self::withCount([
            'selectAdmissionCourse' => function (Builder $query) use ($intake_id) {
                $query->whereHas('admission', function ($admissionQuery) use ($intake_id) {
                    $admissionQuery->where('intake_id', $intake_id);
                });
            },
            'firstSelectAdmissionCourse' => function (Builder $query) use ($intake_id) {
                $query->whereHas('admission', function ($admissionQuery) use ($intake_id) {
                    $admissionQuery->where('intake_id', $intake_id);
                });
            },
            'secondSelectAdmissionCourse' => function (Builder $query) use ($intake_id) {
                $query->whereHas('admission', function ($admissionQuery) use ($intake_id) {
                    $admissionQuery->where('intake_id', $intake_id);
                });
            },
            'thirdSelectAdmissionCourse' => function (Builder $query) use ($intake_id) {
                $query->whereHas('admission', function ($admissionQuery) use ($intake_id) {
                    $admissionQuery->where('intake_id', $intake_id);
                });
            }
        ])->get();
    }

    public function getCriteriaAttribute()
    {
        return $this->courseCriterias->where('filter','include')->pluck('title')->implode(', ');
    }

    public function selectedStudentCourse(){
        return $this->hasOne(Selection::class);
    }

    public static function getCoursesWithSelectedStudentCount($intake_id)
    {
        // Fetch courses with count of selectedStudentCourse filtered by intake and status
        return self::withCount([
            'selectedStudentCourse' => function (Builder $query) use ($intake_id) {
                // Apply the intake filter and status condition
                $query->whereHas('admission', function ($admissionQuery) use ($intake_id) {
                    $admissionQuery->where('intake_id', $intake_id)->where('status', 'SELECTED');
                });
            }
        ])->get();
    }
}
