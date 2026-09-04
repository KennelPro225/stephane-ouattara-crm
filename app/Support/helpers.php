<?php

use Illuminate\Support\Facades\Cache;

if (! function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        return Cache::get("settings.{$key}", $default);
    }
}

if (! function_exists('set_setting')) {
    function set_setting(string $key, mixed $value): void
    {
        Cache::forever("settings.{$key}", $value);
    }
}
