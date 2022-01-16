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

class ReportType
{
    const
        TEXT = 'TEXT',
        NUMBER = 'NUMBER',
        BOOLEAN = 'BOOLEAN',
        DATE = 'DATE',
        DATETIME = 'DATETIME',
        TIME = 'TIME',
        MONTH = 'MONTH',

        //Same Behavioural
        ASYNC = 'ASYNC',
        LIST = 'LIST',
        CHECKBOX = 'CHECKBOX';

    const
        ALIGN_RIGHT = 'right',
        ALIGN_LEFT = 'left',
        ALIGN_CENTER = 'center';

    const
        FORMAT_DATE = 'date',
        FORMAT_DATETIME = 'datetime',
        FORMAT_TIME = 'time',
        FORMAT_NUMBER = 'number',
        FORMAT_CURRENCY = 'currency',
        FORMAT_UPPERCASE = 'uppercase',
        FORMAT_LOWERCASE = 'lowercase',
        FORMAT_CAPITALIZE = 'capitalize',
        FORMAT_ITALIC = 'italic',
        FORMAT_BOLD = 'bold',
        FORMAT_UNDERLINE = 'underline',
        FORMAT_BOOLEAN = 'bool',
        FORMAT_RUPIAH = 'idr';

    //======================================================LIST==============================================================
    //Default Filter LIST => 'endpoint|map_label_col_name(optional)|map_value_id(optional)'
    //endpoint: disini adalah nama tabel / api, boleh menggunakan slash, contoh 'member/search'
    //map_label_col_name(optional): nama kolom yang akan digunakan sebagai label ( option label ) contoh: 'first_name,last_name'
    //map_value_id(optional): nama kolom yang akan digunakan sebagai search value / keywords. if blank then using 'id' +==> ini harusnya mengikuti column_name di report_column
    //========================================================================================================================
    //Contoh 3: 'option|option-slug|selected_default_value(optional)'
    //option disini adalah pengecualian untuk pencarian menggunakan option
    //option-slug merupakan KEY dari Enums / nama option yg ter-register di StoreMapper
    //selected_default_value merupakan value dari option_detail yang mau di set sebagai default ( ini terdaftar juga di Enums )
    //========================================================================================================================
    //Contoh 4: 'custom|yes,1|no,0|Kadang kadang,2'
    //custom_list bearti ini menggunakan list tersendiri
    //yes,1 => bearti yes sebagai label, dan 1 sebagai value nya
    //dst..
    //filter type: EQUALS
    //========================================================================================================================
    //Filling Guide for default_value
    //NULL                                                          => endpoint is /api/[col.table_name], label is [col.column_name], and value is 'id'
    //branch                                                        => endpoint is /api/branch, label is [col.column_name], and value is 'id'
    //branch|branch_name                                            => endpoint is /api/branch, label is 'branch_name' and value is 'id'
    //branch|branch_name|branch_code                                => endpoint is /api/branch, label is 'branch_name' and value is 'branch_code'
    //branch|code,branch_name                                       => endpoint is /api/branch, label is 'code - branch_name' and value is 'id'
    //branch|code,branch_name|branch_code                           => endpoint is /api/branch, label is 'code - branch_name' and value is 'branch_code'
    //product/list|sku,product_name                                 => endpoint is /api/product/list, label is 'sku - product_name' and value is 'id'
    //========================================================================================================================


    //======================================================CHECKBOX==============================================================
    //Contoh 1: 'option|option-slug|multiple_default_value(optional, use comma)'
    //option disini adalah pengecualian untuk pencarian menggunakan option
    //option-slug merupakan KEY dari Enums / nama option yg ter-register di StoreMapper
    //selected_default_value merupakan value dari option_detail yang mau di set sebagai default ( ini terdaftar juga di Enums )
    //========================================================================================================================
    //Contoh 2: 'custom|yes,1|no,0|Kadang kadang,2'
    //custom_list bearti ini menggunakan list tersendiri
    //yes,1 => bearti yes sebagai label, dan 1 sebagai value nya
    //dst..
    //filter type: EQUALS
    //========================================================================================================================
    //Contoh 3: 'branch|branch_name|value(optional)'
    //branch disini adalah nama tabel / apir
    //name disini adalah nama field dari table branch yang mau dicetak sebagai label
    //value (optional) adalah nama field yang mau digunakan sebagai option value ( default nya id, jika tidak ada )
    //Contoh 1: mendapatkan list dr tabel branch dengan label menggunakan field 'name' dan value nya adalah 'id'
    //========================================================================================================================

    //======================================================ASYNC==============================================================
    //Valid example:
    //endpoint|labels|value(id)

    //splitted[0] = endpoint api || table_name
    //splitted[1] = label_key(s) Ex: ( branch_name | branch_code,branch_name )
    //splitted[2] = option_value_key >> if unset col.column_name

    //Filling Guide for default_value
    //NULL                                                          => endpoint is /api/[col.table_name]/search, label is [col.column_name], and value is 'id'
    //branch                                                        => endpoint is /api/branch, label is [col.column_name], and value is 'id'
    //branch|branch_name                                            => endpoint is /api/branch, label is 'branch_name' and value is 'id'
    //branch|branch_name|branch_code                                => endpoint is /api/branch, label is 'branch_name' and value is 'branch_code'
    //branch|code,branch_name                                       => endpoint is /api/branch, label is 'code - branch_name' and value is 'id'
    //branch|code,branch_name|branch_code                           => endpoint is /api/branch, label is 'code - branch_name' and value is 'branch_code'
    //product/list|sku,product_name                                 => endpoint is /api/product/list, label is 'sku - product_name' and value is 'id'
    //========================================================================================================================

    //======================================================NUMBER==============================================================
    //Contoh 1: '3' atau '=3'
    //Contoh 2: '!=3'
    //Contoh 3: '>3'
    //Contoh 4: '>=3'
    //Contoh 5: '<3'
    //Contoh 6: '<=3'
    //filter type: EQUALS, NOT EQUALS, GREATER THAN, GREATER THAN EQUALS, LESS THAN, LESS THAN EQUALS
    //==========================================================================================================================

    //======================================================DATE==============================================================
    //Default value: 'TODAY|YYYY-MM-DD' // pilih salah satu
    //filter type: DATE, BEFORE DATE, AFTER DATE
    //==========================================================================================================================

    //======================================================DATE_BETWEEN==============================================================
    //Default value: 'THIS_WEEK,THIS_MONTH,THIS_YEAR,RANGE_*'
    //filter type: DATE BETWEEN
    //==========================================================================================================================

    //======================================================DATETIME_BETWEEN==============================================================
    //Contoh 1: '0000,NOW|TODAY' => hari ini dari jam 00 sampai jam sekarang
    //Contoh 2: '0000,NOW|THIS_WEEK' => hari ini dari jam 00 sampai jam sekarang
    //Contoh 3: '0000,NOW|RANGE_1_DAY_AGO'
    //Contoh 4: '0900,NOW|RANGE_*'
    //filter type: DATETIME BETWEEN
    //==========================================================================================================================

    //======================================================TIME_BETWEEN==============================================================
    //Contoh 1: '0000,2315' => hari ini dari jam 00 sampai 23:15
    //Contoh 2: '0000,NOW' => hari ini dari jam 00 sampai jam sekarang
    //filter type: TIME BETWEEN
    //==========================================================================================================================

    const
        DF_TODAY = 'TODAY',
        DF_NOW = 'NOW',
        DF_THIS_WEEK = 'THIS_WEEK',
        DF_THIS_MONTH = 'THIS_MONTH',
        DF_THIS_QUARTER = 'THIS_QUARTER',
        DF_THIS_SEMESTER = 'THIS_SEMESTER',
        DF_THIS_YEAR = 'THIS_YEAR',

        DF_RANGE_1_DAY_AGO = 'RANGE_1_DAY_AGO',
        DF_RANGE_2_DAY_AGO = 'RANGE_2_DAY_AGO',
        DF_RANGE_3_DAY_AGO = 'RANGE_3_DAY_AGO',
        DF_RANGE_4_DAY_AGO = 'RANGE_4_DAY_AGO',
        DF_RANGE_5_DAY_AGO = 'RANGE_5_DAY_AGO',
        DF_RANGE_6_DAY_AGO = 'RANGE_6_DAY_AGO',
        DF_RANGE_10_DAY_AGO = 'RANGE_10_DAY_AGO',
        DF_RANGE_15_DAY_AGO = 'RANGE_15_DAY_AGO',
        DF_RANGE_20_DAY_AGO = 'RANGE_20_DAY_AGO',
        DF_RANGE_25_DAY_AGO = 'RANGE_25_DAY_AGO',
        DF_RANGE_1_WEEK_AGO = 'RANGE_1_WEEK_AGO',
        DF_RANGE_2_WEEK_AGO = 'RANGE_2_WEEK_AGO',
        DF_RANGE_3_WEEK_AGO = 'RANGE_3_WEEK_AGO',

        DF_RANGE_1_MONTH_AGO = 'RANGE_1_MONTH_AGO',
        DF_RANGE_2_MONTH_AGO = 'RANGE_2_MONTH_AGO',
        DF_RANGE_3_MONTH_AGO = 'RANGE_3_MONTH_AGO',
        DF_RANGE_4_MONTH_AGO = 'RANGE_4_MONTH_AGO',
        DF_RANGE_5_MONTH_AGO = 'RANGE_5_MONTH_AGO',
        DF_RANGE_6_MONTH_AGO = 'RANGE_6_MONTH_AGO',
        DF_RANGE_7_MONTH_AGO = 'RANGE_7_MONTH_AGO',
        DF_RANGE_8_MONTH_AGO = 'RANGE_8_MONTH_AGO',
        DF_RANGE_9_MONTH_AGO = 'RANGE_9_MONTH_AGO',
        DF_RANGE_10_MONTH_AGO = 'RANGE_10_MONTH_AGO',
        DF_RANGE_11_MONTH_AGO = 'RANGE_11_MONTH_AGO',
        DF_RANGE_12_MONTH_AGO = 'RANGE_12_MONTH_AGO',

        DF_RANGE_1ST_QUARTER = 'RANGE_1ST_QUARTER',
        DF_RANGE_2ND_QUARTER = 'RANGE_2ND_QUARTER',
        DF_RANGE_3RD_QUARTER = 'RANGE_3RD_QUARTER',
        DF_RANGE_4TH_QUARTER = 'RANGE_4TH_QUARTER',
        DF_RANGE_1ST_SEMESTER = 'RANGE_1ST_SEMESTER',
        DF_RANGE_2ND_SEMESTER = 'RANGE_2ND_SEMESTER';

    public static $FORMATTER = [
        self::FORMAT_DATE => 'd M Y',
        self::FORMAT_DATETIME => 'd M Y, H:i',
        self::FORMAT_TIME => 'H:i',
        self::FORMAT_NUMBER => 0, //comma
        self::FORMAT_CURRENCY => ['Rp. ',0], //currency=> [symbol,comma]
        self::FORMAT_RUPIAH => true,
        self::FORMAT_UPPERCASE => true,
        self::FORMAT_LOWERCASE => true,
        self::FORMAT_CAPITALIZE => true,
        self::FORMAT_ITALIC => true,
        self::FORMAT_BOLD => true,
        self::FORMAT_UNDERLINE => true,
        self::FORMAT_BOOLEAN => true,
    ];

    public static $FILTERS = [
        self::TEXT => 'TEXT',                             //Default Filter Text
        self::NUMBER => 'NUMBER',                           //Default Filter Angka
        self::BOOLEAN => 'BOOLEAN',
        self::DATE => 'DATE',                             //Default Filter Tanggal
        self::DATETIME => 'DATETIME',                             //Default Filter Tanggal
        self::TIME => 'TIME',                             //Default Filter Tanggal
        self::LIST => 'LIST',
        self::CHECKBOX => 'CHECKBOX',
    ];

    public static $FILTERS_OPERAND_LABEL = [
        'date_between' => 'Date Between',
        'contains' => 'Contains',
        'exact' => 'Exact',
        'start' => 'Starts With',
        'end' => 'Ends With',
        'includes' => 'Includes',
        'excludes' => 'Excepts',
    ];

    public static $DEFAULT_FILTERS = [
        self::DF_TODAY => 'TODAY',
        self::DF_NOW => 'NOW',
        self::DF_THIS_WEEK => 'THIS_WEEK',
        self::DF_THIS_MONTH => 'THIS_MONTH',
        self::DF_THIS_QUARTER => 'THIS_QUARTER',
        self::DF_THIS_SEMESTER => 'THIS_SEMESTER',
        self::DF_THIS_YEAR => 'THIS_YEAR',
        self::DF_RANGE_1_DAY_AGO => 'RANGE_1_DAY_AGO',
        self::DF_RANGE_2_DAY_AGO => 'RANGE_2_DAY_AGO',
        self::DF_RANGE_3_DAY_AGO => 'RANGE_3_DAY_AGO',
        self::DF_RANGE_4_DAY_AGO => 'RANGE_4_DAY_AGO',
        self::DF_RANGE_5_DAY_AGO => 'RANGE_5_DAY_AGO',
        self::DF_RANGE_6_DAY_AGO => 'RANGE_6_DAY_AGO',
        self::DF_RANGE_10_DAY_AGO => 'RANGE_10_DAY_AGO',
        self::DF_RANGE_15_DAY_AGO => 'RANGE_15_DAY_AGO',
        self::DF_RANGE_20_DAY_AGO => 'RANGE_20_DAY_AGO',
        self::DF_RANGE_25_DAY_AGO => 'RANGE_25_DAY_AGO',
        self::DF_RANGE_1_WEEK_AGO => 'RANGE_1_WEEK_AGO',
        self::DF_RANGE_2_WEEK_AGO => 'RANGE_2_WEEK_AGO',
        self::DF_RANGE_3_WEEK_AGO => 'RANGE_3_WEEK_AGO',
        self::DF_RANGE_1_MONTH_AGO => 'RANGE_1_MONTH_AGO',
        self::DF_RANGE_2_MONTH_AGO => 'RANGE_2_MONTH_AGO',
        self::DF_RANGE_3_MONTH_AGO => 'RANGE_3_MONTH_AGO',
        self::DF_RANGE_4_MONTH_AGO => 'RANGE_4_MONTH_AGO',
        self::DF_RANGE_5_MONTH_AGO => 'RANGE_5_MONTH_AGO',
        self::DF_RANGE_6_MONTH_AGO => 'RANGE_6_MONTH_AGO',
        self::DF_RANGE_7_MONTH_AGO => 'RANGE_7_MONTH_AGO',
        self::DF_RANGE_8_MONTH_AGO => 'RANGE_8_MONTH_AGO',
        self::DF_RANGE_9_MONTH_AGO => 'RANGE_9_MONTH_AGO',
        self::DF_RANGE_10_MONTH_AGO => 'RANGE_10_MONTH_AGO',
        self::DF_RANGE_11_MONTH_AGO => 'RANGE_11_MONTH_AGO',
        self::DF_RANGE_12_MONTH_AGO => 'RANGE_12_MONTH_AGO',
        self::DF_RANGE_1ST_QUARTER => 'RANGE_1ST_QUARTER',
        self::DF_RANGE_2ND_QUARTER => 'RANGE_2ND_QUARTER',
        self::DF_RANGE_3RD_QUARTER => 'RANGE_3RD_QUARTER',
        self::DF_RANGE_4TH_QUARTER => 'RANGE_4TH_QUARTER',
        self::DF_RANGE_1ST_SEMESTER => 'RANGE_1ST_SEMESTER',
        self::DF_RANGE_2ND_SEMESTER => 'RANGE_2ND_SEMESTER',
    ];
}
