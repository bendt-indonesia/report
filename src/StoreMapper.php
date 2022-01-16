<?php

namespace Bendt\Report;

use Bendt\Report\Models\Report;

class StoreMapper
{
    public static function MAP($key)
    {
        switch ($key)
        {
            case 'report':
                return function() {
                    return Report::with('report_column')->get()->keyBy('key')->toArray();
                };
            default:
                return null;
        }
    }
}
