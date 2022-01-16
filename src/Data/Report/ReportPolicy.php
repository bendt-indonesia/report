<?php

namespace Bendt\Report\Data\Report;

use App\User;
use App\Models\Report as Model;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User $user
     * @param  \App\Models\Report $model
     * @return mixed
     */
    public function view(User $user, Model $model)
    {
        return $user->hasAnyRole(['VIEW_REPORT','VIEW_REPORT_COLUMN']);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User $user
     * @return mixed
     */
    public function store(User $user)
    {
        return $user->hasAnyRole(['STORE_REPORT']);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User $user
     * @param  \App\Models\Report $model
     * @return mixed
     */
    public function update(User $user, Model $model)
    {
        return $user->hasAnyRole(['UPDATE_REPORT']);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User $user
     * @param  \App\Models\Report $model
     * @return mixed
     */
    public function destroy(User $user, Model $model)
    {
        return $user->hasAnyRole(['DESTROY_REPORT']);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User $user
     * @param  \App\Models\Report $model
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
     * @param  \App\Models\Report $model
     * @return mixed
     */
    public function forceDelete(User $user, Model $model)
    {
        return false;
    }
}
