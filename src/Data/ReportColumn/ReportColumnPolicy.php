<?php

namespace Bendt\Report\Data\ReportColumn;

use App\User;
use App\Models\ReportColumn as Model;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportColumnPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User $user
     * @param  \App\Models\ReportColumn $model
     * @return mixed
     */
    public function view(User $user, Model $model)
    {
        return $user->hasAnyRole(['VIEW_REPORT_COLUMN','VIEW_REPORT']);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User $user
     * @return mixed
     */
    public function store(User $user)
    {
        return $user->hasAnyRole(['STORE_REPORT_COLUMN','STORE_REPORT']);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User $user
     * @param  \App\Models\ReportColumn $model
     * @return mixed
     */
    public function update(User $user, Model $model)
    {
        return $user->hasAnyRole(['UPDATE_REPORT_COLUMN','UPDATE_REPORT']);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User $user
     * @param  \App\Models\ReportColumn $model
     * @return mixed
     */
    public function destroy(User $user, Model $model)
    {
        return $user->hasAnyRole(['DESTROY_REPORT_COLUMN','DESTROY_REPORT']);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User $user
     * @param  \App\Models\ReportColumn $model
     * @return mixed
     */
    public function restore(User $user, Model $model)
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User $user
     * @param  \App\Models\ReportColumn $model
     * @return mixed
     */
    public function forceDelete(User $user, Model $model)
    {
        return false;
    }
}
