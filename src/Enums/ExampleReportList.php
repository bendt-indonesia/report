<?php
/*
 *
  ____                 _ _     _____           _                       _
 |  _ \               | | |   |_   _|         | |                     (_)
 | |_) | ___ _ __   __| | |_    | |  _ __   __| | ___  _ __   ___  ___ _  __ _
 |  _ < / _ \ '_ \ / _` | __|   | | | '_ \ / _` |/ _ \| '_ \ / _ \/ __| |/ _` |
 | |_) |  __/ | | | (_| | |_   _| |_| | | | (_| | (_) | | | |  __/\__ \ | (_| |
 |____/ \___|_| |_|\__,_|\__| |_____|_| |_|\__,_|\___/|_| |_|\___||___/_|\__,_|

 Please don't modify this file because it may be overwritten when re-generated.
 */

namespace Bendt\Report\Enums;

class ExampleReportList
{
    const
        invoice = 'invoice',
        invoiceDetail = 'invoiceDetail';

    public static $REPORT_TITLE = [
        self::invoice => 'Laporan Penjualan',
        self::invoiceDetail => 'Laporan Penjualan Detail',
    ];

    public static $REPORT_FILE_NAME = [
        self::invoice => 'Laporan_Penjualan',
        self::invoiceDetail => 'Laporan_Penjualan Detail',
    ];

}
