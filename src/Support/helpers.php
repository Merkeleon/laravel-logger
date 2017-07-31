<?php

if (!function_exists('merkeleon_log_add'))
{
    function merkeleon_log_add($fileName, $event, $data)
    {
        app(\Merkeleon\Logger\Logger::class)->add($fileName, $event, $data);
    }
}

if (!function_exists('merkeleon_log_search'))
{
    function merkeleon_log_add($fileName, $event)
    {
        app(\Merkeleon\Logger\Logger::class)->search($fileName, $event);
    }
}