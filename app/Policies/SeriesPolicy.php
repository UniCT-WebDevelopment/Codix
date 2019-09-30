<?php

namespace App\Policies;

use App\User;
use App\Series;
use Illuminate\Auth\Access\HandlesAuthorization;

class SeriesPolicy
{
    use HandlesAuthorization;
    

    public function before(User $user){
        if($user && $user->isAdmin())
            return true;
    }

    /**
     * Determine whether the user can view any series.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(?User $user)
    {
        return (!security_settings['require_authentication']['value'] && security_settings['default_rule']['value']) || !is_null($user);
    }

    /**
     * Determine whether the user can view the series.
     *
     * @param  \App\User  $user
     * @param  \App\Series  $series
     * @return mixed
     */
    public function view(?User $user, Series $series)
    {
        return (!security_settings['require_authentication']['value'] && security_settings['default_rule']['value'] && is_null($user)) || $series->readableBy($user);
    }

    /**
     * Determine whether the user can create series.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the series.
     *
     * @param  \App\User  $user
     * @param  \App\Series  $series
     * @return mixed
     */
    public function update(User $user, Series $series)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the series.
     *
     * @param  \App\User  $user
     * @param  \App\Series  $series
     * @return mixed
     */
    public function delete(User $user, Series $series)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the series.
     *
     * @param  \App\User  $user
     * @param  \App\Series  $series
     * @return mixed
     */
    public function restore(User $user, Series $series)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the series.
     *
     * @param  \App\User  $user
     * @param  \App\Series  $series
     * @return mixed
     */
    public function forceDelete(User $user, Series $series)
    {
        return $user->isAdmin();
    }
}
