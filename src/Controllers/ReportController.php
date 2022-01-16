<?php

namespace Bendt\Report\Controllers;

use Bendt\Report\Enums\ExampleReportList;
use Bendt\Report\Data\Report\RequestGenerateReport;
use Bendt\Report\Services\BendtReportService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReportController
{
    public function invoiceReport(RequestGenerateReport $request)
    {
        $report = new BendtReportService($request->all());
        return $this->invoiceData($report);
    }

    public function invoiceData($report) {
        $table = 'invoice';
        $key = ExampleReportList::invoice;
        $select = $report->generateSelectQuery($key);
        $data = DB::table($table)
            ->select(DB::raw($select))
            ->leftJoin('invoice_shipping', 'invoice_shipping.invoice_id', '=', $table.'.id')
            ->leftJoin('promo', 'promo.id', '=', $table.'.promo_id')
            ->leftJoin('member', 'member.id', '=', $table.'.member_id')
            ->leftJoin('option_detail as invoice_status', 'invoice_status.id', '=', $table.'.invoice_status_id')
            ->leftJoin('option_detail as payment_gateway', 'payment_gateway.id', '=', $table.'.payment_gateway_id')
            ->leftJoin('option_detail as payment_method', 'payment_method.id', '=', $table.'.payment_method_id')
            ->leftJoin('option_detail as shipping_method', 'shipping_method.id', '=', $table.'.shipping_method_id')
            ->whereNull('invoice.deleted_at')
            ->orderBy('invoice_date');

        return $report->finalize($data, $key);
    }
}
