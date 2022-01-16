<?php

use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

return [

    /*
    |--------------------------------------------------------------------------
    | Bendt Report Services Configuration
    |--------------------------------------------------------------------------
    |
    */

    'debug' => false,
    'titles' => \Bendt\Report\Enums\ExampleReportList::$REPORT_TITLE,
    'file_names' => \Bendt\Report\Enums\ExampleReportList::$REPORT_FILE_NAME,
    'cache' => [
        'keys' => 'wLNkWkayeq6VR29fpeKMgYx87u',
        'timeout' => 3600, //seconds
    ],
];
