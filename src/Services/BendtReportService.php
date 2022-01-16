<?php
namespace Bendt\Report\Services;

use Bendt\Report\Enums\ReportType;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class BendtReportService
{
    protected $debug = false;
    protected $limit = 5000;
    protected $user = false;
    protected $query = false;
    protected $output = 'dl';
    protected $file_type = 'excel';
    protected $orientation = PageSetup::ORIENTATION_PORTRAIT;
    protected $paper = 'a4';
    protected $autoSize = true;
    protected $columns = [];
    protected $filters = [];
    protected $keywords = [];
    protected $labels = [];
    protected $rawWhereColumns = [];

    //declared only custom view, default using report.general
    protected $report_view = [

    ];

    /**
     * Display a listing of the resource Invoice
     *
     * @param  Array $settings
     * @key file
     * @key orientation
     * @key output
     * @key limit
     * @key auto
     * @key user_id => valid App/User id
     * @key query
     * @key keywords
     * @key filters
     *
     */
    public function __construct($settings = [])
    {
        if (isset($settings['file'])) $this->file_type = $settings['file'];
        if (isset($settings['orientation']) && strtolower($settings['orientation']) === 'landscape')
            $this->orientation = PageSetup::ORIENTATION_LANDSCAPE;
        if (isset($settings['output'])) $this->output = $settings['output'];
        if (isset($settings['limit'])) $this->limit = (int)$settings['limit'];
        if (isset($settings['auto'])) $this->autoSize = (int)$settings['auto'];
        if (isset($settings['user_id'])) $this->user = User::find($settings['user_id']);

        if (isset($settings['query'])) {
            $this->query = $settings['query'];
            $this->columns = explode(',',v['query']);
        }
        if (isset($settings['keywords'])) $this->keywords = (Array) json_decode($settings['keywords']);
        if (isset($settings['filters'])) $this->filters = (Array) json_decode($settings['filters']);

        $this->debug = config('bendt-report.debug',false);

        if($this->debug) {
            Log::info('========================== Bendt Report Settings =============================');
            Log::info($settings);
            Log::info('======================= Columns, Keywords, Filters ===========================');
            Log::info($this->columns);
            Log::info($this->keywords);
            Log::info($this->filters);
            Log::info('==============================================================================');
        }
    }

    public function generateReportFileName($key)
    {
        $format = config('bendt-report.file_names')[$key];

        if ($this->user) {
            $format = str_replace('USER', Str::snake($this->user->name), $format);
        } else {
            $format = str_replace('_USER', '', $format);
        }

        $format = str_replace('YYYY', date('Y'), $format);
        $format = str_replace('MM', date('m'), $format);
        $format = str_replace('DD', date('m'), $format);
        $format = str_replace('HH', date('h'), $format);
        $format = str_replace('MM', date('i'), $format);
        $format = str_replace('SS', date('s'), $format);

        switch ($this->file_type) {
            case 'pdf':
                $format .= '.pdf';
                break;
            case 'csv':
                $format .= '.csv';
                break;

            default:
                $format .= '.xlsx';
                break;
        }

        return $format;
    }

    public function generateFiltered($key = null)
    {
        $filter = '';
        if ($this->user) {
            $filter .= 'Staff Name ' . $this->user->name . ', ';
        }

        if (strlen($filter) === 1) {
            return '-';
        }

        return substr($filter, 0, -2);
    }

    public function groupColumnsByTable($report_columns)
    {
        $groupedByTable = collect($report_columns)->groupBy('table_name')->toArray();
        foreach ($groupedByTable as $table_name => $columns) {
            $groupedByTable[$table_name] = collect($columns)->orderBy('sort_no')->keyBy('column_name')->toArray();
        }

        return $groupedByTable;
    }

    public function generatePeriode($key = null)
    {

        return false;
    }

    public function checkQueryGroupBy($columns, $queryColumns = [])
    {
        foreach ($columns as $col) {
            //Default Columns
            if (count($queryColumns) === 0 && $col['is_checked'] && $col['is_group']) {
                return true;
            } else if (in_array($col['table_name'] . '.' . $col['column_name'], $queryColumns)) {
                return true;
            }
        }
        return false;
    }

    public function generateSelectQuery($key)
    {
        $query = '';
        $report = stores('report')[$key];

        if ($this->query) {
            $groupedByTable = $this->groupColumnsByTable($report['report_column']);
            $lowercase = strtolower($this->query);
            $token = strtok($lowercase, ',');

            while ($token !== false) {
                $process = trim($token);
                $tableColumn = explode('.', $process);

                if (count($tableColumn) < 2) {
                    $column_name = isset($tableColumn[0]) ? $tableColumn[0] : 'Unknown';
                    abt('81003', 'Ambiguous Field ' . $column_name);
                }

                $table_name = $tableColumn[0];
                $column_name = $tableColumn[1];
                if (!isset($groupedByTable[$table_name][$column_name])) {
                    abt('81006', 'Table ' . $table_name . ' atau Kolom ' . $column_name . ' tidak tersedia.');
                }

                $col = $groupedByTable[$table_name][$column_name];

                if ($col['raw_query'] !== null) {
                    $query .= $col['raw_query'] . ' as ' . $table_name . '_' . $column_name . ',';
                    $this->rawWhereColumns[$process] =  $table_name . '_' . $column_name;
                } else {
                    $query .= $table_name . '.' . $column_name . ' as ' . $table_name . '_' . $column_name . ',';
                }

                $token = strtok(",");
            }
        } else {
            foreach ($report['report_column'] as $col) {
                if ($col['is_checked']) {
                    $table_name = $col['table_name'];
                    $column_name = $col['column_name'];

                    if ($col['raw_query'] === null) {
                        $query .= $table_name . '.' . $column_name . ' as ' . $table_name . '_' . $column_name . ',';
                    } else {
                        $query .= $col['raw_query'] . ' as ' . $table_name . '_' . $column_name . ',';
                        $this->rawWhereColumns[$table_name . '.' . $column_name] =  $table_name . '_' . $column_name;
                    }

                }
            }
        }

        $query = strlen($query) > 0 ? substr($query, 0, -1) : '*';
        if($this->debug) {
            Log::info('========================== SQL Queries =============================');
            Log::info($query);
            Log::info('==============================================================================');
        }
        return DB::raw($query);
    }

    public function generateReportColumns($key)
    {
        $fields = [];
        $report = stores('report')[$key];
        if ($this->query) {
            $groupedByTable = $this->groupColumnsByTable($report['report_column']);
            $lowercase = strtolower($this->query);
            $token = strtok($lowercase, ',');
            while ($token !== false) {
                $process = trim($token);
                $tableColumn = explode('.', $process);

                if (count($tableColumn) < 2) {
                    $column_name = isset($tableColumn[0]) ? $tableColumn[0] : 'Unknown';
                    abt('81001', 'Ambiguous Field ' . $column_name);
                }

                $table_name = $tableColumn[0];
                $column_name = $tableColumn[1];
                if (!isset($groupedByTable[$table_name][$column_name])) {
                    abt('81004', 'Table ' . $table_name . ' atau Kolom ' . $column_name . ' tidak tersedia.');
                }

                $col = $groupedByTable[$table_name][$column_name];
                $fields[] = [
                    'label' => $col['label'] ? $col['label'] : Str::title($column_name),
                    'column_name' => $table_name . '_' . $column_name,
                    'align' => $col['align'],
                    'formatter' => $col['formatter'],
                ];
                $this->labels[$process] = $col['label'] ? $col['label'] : Str::title($column_name);

                $token = strtok(",");
            }
        } else {
            foreach ($report['report_column'] as $col) {
                if ($col['is_checked']) {
                    $table_name = $col['table_name'];
                    $column_name = $col['column_name'];
                    $fields[] = [
                        'label' => $col['label'] ? $col['label'] : Str::title($column_name),
                        'column_name' => $table_name . '_' . $column_name,
                        'align' => $col['align'],
                        'formatter' => $col['formatter'],
                    ];

                    $this->columns[] =  $table_name . '.' . $column_name;
                }
            }
        }
        return $fields;
    }

    public function generateGroupQuery($key, $queryBuilder)
    {
        $report = stores('report')[$key];
        $query = '';

        if ($this->query) {
            $groupedByTable = $this->groupColumnsByTable($report['report_column']);
            $lowercase = strtolower($this->query);
            $queryColumns = explode(',', $lowercase);

            $check = $this->checkQueryGroupBy($report['report_column'], $queryColumns);
            if (!$check) return $queryBuilder;

            $token = strtok($lowercase, ',');
            while ($token !== false) {
                $process = trim($token);
                $tableColumn = explode('.', $process);

                if (count($tableColumn) < 2) {
                    $column_name = isset($tableColumn[0]) ? $tableColumn[0] : 'Unknown';
                    abt('81002', 'Ambiguous Field ' . $column_name);
                }

                $table_name = $tableColumn[0];
                $column_name = $tableColumn[1];
                if (!isset($groupedByTable[$table_name][$column_name])) {
                    abt('81005', 'Table ' . $table_name . ' atau Kolom ' . $column_name . ' tidak tersedia.');
                }

                $col = $groupedByTable[$table_name][$column_name];
                if (!$col['is_group']) {
                    $query .= $table_name . '_' . $column_name . ',';
                }
                $token = strtok(",");
            }
        } else {
            $check = $this->checkQueryGroupBy($report['report_column']);
            if (!$check) return $queryBuilder;

            foreach ($report['report_column'] as $col) {
                if ($col['is_checked'] && !$col['is_group']) {
                    $table_name = $col['table_name'];
                    $column_name = $col['column_name'];
                    $query .= $table_name . '_' . $column_name . ',';
                }
            }
        }

        if (strlen($query) === 0) return $queryBuilder;

        $query = substr($query, 0, -1);
        if (env('DEBUG_REPORT_GROUP')) dd($query);
        return $queryBuilder->groupBy(DB::raw($query));
    }

    public function generateReportTitle($key)
    {
        $report = stores('report')[$key];
        return $report['name'];
    }

    public function finalize($data, $key)
    {
        //Check whether the query need group by query
        $data = $this->filterRequestQuery($data);
        $data = $this->generateGroupQuery($key, $data);
        $data = $this->filterHavingRequestQuery($data);


        //Limit the query
        if ($this->limit) $data = $data->limit($this->limit);

        $data = $data->get();
        Log::info($data);
        if (env('DEBUG_REPORT_DATA_RESULTS')) dd($data);

        return [
            'autoSize' => $this->autoSize,
            'orientation' => $this->orientation,
            'title' => $this->generateReportTitle($key),
            'period' => $this->generatePeriode($key),
            'filter' => $this->generateFiltered($key),
            'file_name' => $this->generateReportFileName($key),
            'columns' => $this->generateReportColumns($key),
            'data' => $data,
            'view' => isset($this->report_view[$key]) ? $this->report_view[$key] : 'report.general',

            'labels' => $this->labels,
            'filters' => $this->filters,
            'keywords' => $this->keywords,
        ];
    }

    public function output($reportClass, $data)
    {

        switch ($this->output) {
            case 'stream':
                $pdf = PDF::loadView($data['view'], $data)
                    ->setPaper($this->paper, $this->orientation);
                return $pdf->stream($data['file_name']);
            case 'html':
                return view($data['view'], $data);
            case 'download':
                return Excel::download(new $reportClass($data), $data['file_name']);
            default:
                Excel::store(new $reportClass($data), 'public/report/'.$data['file_name']);

                return response()->json([
                    'success' => true,
                    'data' => [
                        'file_download_url' => asset('storage/report/'.$data['file_name'])
                    ]
                ], 200);
        }

    }
}
