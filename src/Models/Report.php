<?php

namespace Bendt\Report\Models;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Bendt\Report\Models\Traits\BelongsToCreatedByTrait;
use Bendt\Report\Models\Traits\BelongsToUpdatedByTrait;
use Bendt\Report\Models\Traits\BelongsToDeletedByTrait;
use Bendt\Report\Models\Traits\ScopeActiveTrait;

class Report extends BaseModel {

	use SoftCascadeTrait, SoftDeletes, BelongsToCreatedByTrait, BelongsToUpdatedByTrait, BelongsToDeletedByTrait, ScopeActiveTrait;

	protected $table = 'report';

	protected $softCascade = ['report_column'];

	protected $files = [];

	const FILE_PATH = "/report/";

	public function report_column() {
		return $this->hasMany(ReportColumn::class)->orderBy('sort_no','asc');
	}

}