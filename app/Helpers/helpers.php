<?php

if (!function_exists('formatStartEndTime')) {
    function formatStartEndTime($start, $end)
    {
        return date('H:i', strtotime($start)) . ' - ' . date('H:i', strtotime($end));
    }
}