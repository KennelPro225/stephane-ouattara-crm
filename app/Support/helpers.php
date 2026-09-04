<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

if (! function_exists('setting')) {
    /**
     * Settings are persisted in the 'settings' table (source of truth) with a
     * cache read-through — they used to live only in Cache::forever(), which
     * meant editable site content vanished whenever the cache was cleared or
     * the driver changed.
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever("settings.{$key}", function () use ($key, $default) {
            return Setting::where('key', $key)->value('value') ?? $default;
        });
    }
}

if (! function_exists('set_setting')) {
    function set_setting(string $key, mixed $value): void
    {
        Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("settings.{$key}");
    }
}
