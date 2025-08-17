<?php

use Carbon\Carbon;

if (!function_exists('formatStartEndTime')) {
    function formatStartEndTime($start, $end)
    {
        return date('H:i', strtotime($start)) . ' - ' . date('H:i', strtotime($end));
    }
}

if (!function_exists('formatCreatedAt')) {
    function formatCreatedAt($createdAt)
    {
        return Carbon::parse($createdAt)
            ->setTimezone('Asia/Jakarta')
            ->format('d-m-Y H:i');
    }
}