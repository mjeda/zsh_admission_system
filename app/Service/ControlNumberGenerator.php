<?php

namespace App\Service;

use App\Models\ControlNumber;
use Carbon\Carbon;

class ControlNumberGenerator
{
    public static function generate():  string {
        do {
            $today = date('Y-m-d');
            $last_count = 1;
            $rows_found = ControlNumber::whereDate('created_at', $today)->get();
            if ($rows_found) {
                $last_count = $rows_found->count() + 1;
            }

            #Generate Unique
            $key = rand(10, 99);
            $key2 = rand(3, 9);
            $current_count = substr(str_repeat(0, 3).$last_count, - 3);
            $unique = '31'.$key2.date('mdy').$current_count;

            $found = ControlNumber::where('control_number', $unique)->count();
        } while ($found > 0);

        return $unique;
    }
}
