<?php

namespace Bendt\Report\Data\ReportColumn;

use App\Models\ReportColumn;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ReportColumnCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $this->collection->transform(function (ReportColumn $model) {
            return (new ReportColumnResource($model))->additional($this->additional);
        });

        return parent::toArray($request);
    }
}
