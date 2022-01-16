<?php

namespace Bendt\Report\Data\Report;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
{
    /**
     * Transform the resource model Report into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
			'key' => $this->key,
			'name' => $this->name,
			'report_column' => $this->report_column,
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
