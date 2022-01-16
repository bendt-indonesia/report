<?php

namespace Bendt\Report\Data\ReportColumn;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportColumnResource extends JsonResource
{
    /**
     * Transform the resource model ReportColumn into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
			'report_id' => $this->report_id,
			'report' => $this->report,
			'table_name' => $this->table_name,
			'column_name' => $this->column_name,
			'sort_no' => $this->sort_no,
			'label' => $this->label,
			'align' => $this->align,
			'formatter' => $this->formatter,
			'is_checked' => $this->is_checked,
			'created_at' => $this->created_at,
			'created_by_id' => $this->created_by_id,
			'created_by' => $this->created_by,
			'updated_at' => $this->updated_at,
			'updated_by_id' => $this->updated_by_id,
			'updated_by' => $this->updated_by,
			'deleted_at' => $this->deleted_at,
			'deleted_by_id' => $this->deleted_by_id,
			'deleted_by' => $this->deleted_by,
        ];
    }
}
