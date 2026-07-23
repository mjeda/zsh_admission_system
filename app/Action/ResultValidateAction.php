<?php

namespace App\Action;

use App\Models\Admission;
use App\Models\Course;
use App\Models\Result;
use Illuminate\Support\Facades\Log;

class ResultValidateAction
{
    public function handle($admission, $course_id = null)
    {
        $grades = [
            'A+' => 7,
            'A'  => 6,
            'B+' => 5,
            'B'  => 4,
            'C'  => 3,
            'D'  => 2,
            'E'  => 1,
            'F'  => 0,
        ];

        $passes = [];
        $points = [];

        // Get all results for this admission
        $results = Result::where('admission_id', $admission)->get();

        foreach ($results as $result) {
            $payload = json_decode($result->result_payload);
            foreach ($payload->subjects ?? [] as $subject) {
                $index = array_search(trim($subject->subject_name), array_map(fn($p) => trim($p['title']), $passes));
                if ($index !== false) {
                    if ($grades[$subject->grade] > $grades[$passes[$index]['value']]) {
                        $passes[$index]['value'] = $subject->grade;
                    }
                } else {
                    $passes[] = [
                        'title' => $subject->subject_name,
                        'value' => $subject->grade,
                    ];
                }
            }
        }

        $courses = $course_id
            ? Course::with('courseCriterias')->where('id', $course_id)->get()
            : Course::with('courseCriterias')->get();

        $applicable_courses = [];

        foreach ($courses as $course) {
            $course_passes = 0;
            $criteria_passed = [];

            if ($course->filtered === 'Any except excluded in criteria') {
                foreach ($passes as $pass) {
                    if ($grades[$pass['value']] >= 2) {
                        $is_excluded = $course->courseCriterias
                            ->where('filter', 'Exclude')
                            ->contains('title', $pass['title']);

                        if (!$is_excluded) {
                            $course_passes++;
                            $points[] = $grades[$pass['value']];
                        }
                    }
                }
            } else {
                $must_include = $course->courseCriterias->where('filter', 'Include');
                $advantage     = $course->courseCriterias->where('filter', 'Advantage');
                $alternatives  = $course->courseCriterias->where('filter', 'Alternative');

                // Check 'Include'
                foreach ($must_include as $criteria) {
                    $required_title = $criteria->title;
                    $required_grade = $criteria->value;

                    $index = collect($passes)->search(function ($item) use ($required_title) {
                        return trim($item['title']) == trim($required_title);
                    });

                    if ($index !== false && $grades[$passes[$index]['value']] >= $grades[$required_grade]) {
                        $course_passes++;
                        $points[] = $grades[$passes[$index]['value']];
                        $criteria_passed[] = $required_title;
                    } else {
                        // Check alternative if exists
                        $alt_criterias = $alternatives->where('alternative_for', $required_title);
                        if ($alt_criterias) {
                            foreach($alt_criterias as $alt_criteria) {
                                $alt_index = array_search(trim($alt_criteria->title), array_map(fn($p) => trim($p['title']), $passes));
                                if ($alt_index !== false && $grades[$passes[$alt_index]['value']] >= $grades[$alt_criteria->value]) {
                                    $course_passes++;
                                    $points[] = $grades[$passes[$alt_index]['value']];
                                    $criteria_passed[] = $alt_criteria->title;
                                }
                            }
                        }
                    }
                }

                // If not all required includes passed, skip course
                if ($course_passes < $must_include->count()) {
                    continue;
                }

                // Check 'Advantage' (if any required)
                $adv_required = $course->advantage_required ?? 0;
                $adv_count = 0;

                foreach ($advantage as $criteria) {
                    if ($adv_count >= $adv_required) break;

                    $index = array_search(trim($criteria->title), array_map(fn($p) => trim($p['title']), $passes));
                    if ($index !== false && $grades[$passes[$index]['value']] >= $grades[$criteria->value]) {
                        $course_passes++;
                        $points[] = $grades[$passes[$index]['value']];
                        $criteria_passed[] = $criteria->title;
                        $adv_count++;
                    }
                }

                // Add extra subjects (not already counted) if still short
                if ($course_passes < $course->passes) {
                    foreach ($passes as $pass) {
                        if ($grades[$pass['value']] >= 2 && !in_array($pass['title'], $criteria_passed))
                        {
                            $is_excluded = $course->courseCriterias->where('filter', 'Exclude')->contains('title', $pass['title']);

                            if (!$is_excluded) {
                                $course_passes++;
                                $points[] = $grades[$pass['value']];
                                if ($course_passes >= $course->passes) break;
                            }
                        }
                    }
                }
            }

            if ($course_passes >= $course->passes) {
                $applicable_courses[] = $course->id;
            }
        }

        Admission::where('id', $admission)->update([
            'points' => array_sum($points),
        ]);

        Log::info('Student Grades:', $passes);
        Log::info('Applicable Courses:', $applicable_courses);

        return $applicable_courses;
    }
}
