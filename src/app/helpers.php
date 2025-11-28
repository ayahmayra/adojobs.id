<?php

use App\Models\Setting;

if (!function_exists('setting')) {
    /**
     * Get setting value by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }
}

if (!function_exists('site_logo')) {
    /**
     * Get site logo URL
     *
     * @return string|null
     */
    function site_logo()
    {
        $logo = Setting::get('site_logo');
        return $logo ? asset('storage/' . $logo) : null;
    }
}

if (!function_exists('site_favicon')) {
    /**
     * Get site favicon URL
     *
     * @return string|null
     */
    function site_favicon()
    {
        $favicon = Setting::get('site_favicon');
        return $favicon ? asset('storage/' . $favicon) : null;
    }
}

if (!function_exists('site_name')) {
    /**
     * Get site name
     *
     * @return string
     */
    function site_name()
    {
        return Setting::get('site_name', 'AdoJobs.id');
    }
}

if (!function_exists('site_description')) {
    /**
     * Get site description
     *
     * @return string
     */
    function site_description()
    {
        return Setting::get('site_description', 'Platform pencarian kerja terbaik di Pulau Bengkalis');
    }
}

