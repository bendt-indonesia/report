<?php

namespace Bendt\Report\Models;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Bendt\Report\Models\Traits\BelongsToCreatedByTrait;
use Bendt\Report\Models\Traits\BelongsToUpdatedByTrait;
use Bendt\Report\Models\Traits\BelongsToDeletedByTrait;
use Bendt\Report\Models\Traits\ScopeActiveTrait;

class ReportColumn extends BaseModel {

	use SoftCascadeTrait, SoftDeletes, BelongsToCreatedByTrait, BelongsToUpdatedByTrait, BelongsToDeletedByTrait, ScopeActiveTrait;

	protected $table = 'report_column';

	protected $processed = ['sort_no'];

	protected $files = [];

	const FILE_PATH = "/report_column/";

	public function report() {
		return $this->belongsTo(Report::class);
	}

}