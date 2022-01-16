<?php

/**
 * Store Helper
 *
 * @param string $key
 *
 * @return \Bendt\Store
 */
if (!function_exists('stores')) {
    function stores($key)
    {
        $stores = \Bendt\Store::get($key);
        if(!$stores) dd('Store ['.$key.'] not found!');
        return \Bendt\Store::get($key);
    }
}

