<?php

namespace Bendt\Report\Data\Report;

use App\Models\Report;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ReportCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $this->collection->transform(function (Report $model) {
            return (new ReportResource($model))->additional($this->additional);
        });

        return parent::toArray($request);
    }
}
