<?php
use Carbon\Carbon;

/**
 * Write code on Method
 *
 * @return response()
 */
if (!function_exists('convertYmdToMdy')) {
    function convertYmdToMdy($date)
    {
        return Carbon::createFromFormat('Y-m-d', $date)->format('m-d-Y');
    }
}


if (!function_exists('isStoreOpen')) {
    function isStoreOpen($businessHours, $day, $time, $format = 'g:i A')
    {
        $currentTime = Carbon::now();

        $day = !empty($day) ? $day :  $currentTime->format("l");
        $time = !empty($time) ? $time :  $currentTime->format('H:i');

        $currentTime = strtotime($time);
        $currentTime = (Carbon::createFromTimestamp($currentTime)->format("H") * 3600) + (Carbon::createFromTimestamp($currentTime)->format("i") * 60);

        $businessHours = (is_array($businessHours)) ? $businessHours : json_decode($businessHours, true);
        $startTime = false;
        $endTime = false;
        $isOpen = false;

        if (is_array($businessHours)) {
            foreach ($businessHours as $businesshr) {
                $today = $businesshr['days'];
                if (strtolower($day) == strtolower($today)) {
                    $startTime = (int) $businesshr['opening_time'];
                    $endTime = (int) $businesshr['closing_time'];
                    if($businesshr['status']){
                        if ($currentTime >= $startTime && $currentTime <= $endTime) {
                            $isOpen = true;
                        }
                    }
                }
            }
        }
        return [
            'start_time'    => Carbon::createFromTimestamp($startTime,'UTC')->format($format),
            'end_time'      => Carbon::createFromTimestamp($endTime,'UTC')->format($format),
            'is_open'       => $isOpen,
        ];
    }
}



if (!function_exists('getMinutes')) {
    function getMinutes($time_string)
    {
        $parts = explode(":", $time_string);
        $hours = intval($parts[0]);
        $minutes = intval($parts[1]);
        return $hours * 60 + $minutes;
    }
}

if (!function_exists('getTime')) {
    function getTime()
    {
        $currentTime = Carbon::now();
        // $time = !empty(request()->time) ? $time :  $currentTime->format('H:i');
        $time = $currentTime->format('H:i');
        $currentTime = strtotime($time);
        $currentTime = (Carbon::createFromTimestamp($currentTime)->format("H") * 3600) + (Carbon::createFromTimestamp($currentTime)->format("i") * 60);
        return  $currentTime;
    }
}

if (!function_exists('getLog')) {
    function getLog()
    {
        \Log::info(json_encode(request()->all()));
        \Log::info(url()->current());
        \Log::info(request()->header('Authorization'));
        return [
            "request" => request()->all(),
            "url" => url()->current(),
            'token' => request()->header('Authorization'),
        ];
    }
}
