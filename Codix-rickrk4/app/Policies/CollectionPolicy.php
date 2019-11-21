<?php

namespace App\Policies;

use App\User;
use App\Collection;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Request;

class CollectionPolicy
{
    use HandlesAuthorization;

    public function before($user){
        if($user->isAdmin())
            return true;
    }

    /**
     * Determine whether the user can view any collections.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(?User $user)
    {
        return (!security_settings['require_authentication']['value'] && security_settings['default_rule']['value']) || !is_null($user);
    }

    /**
     * Determine whether the user can view the collection.
     *
     * @param  \App\User  $user
     * @param  \App\Collection  $collection
     * @return mixed
     */
    public function view(?User $user, Collection $collection)
    {
        return (!security_settings['require_authentication']['value'] && security_settings['default_rule']['value'] && is_null($user)) || $collection->readableBy($user);
    }

    /**
     * Determine whether the user can create collections.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the collection.
     *
     * @param  \App\User  $user
     * @param  \App\Collection  $collection
     * @return mixed
     */
    public function update(User $user, Collection $collection)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the collection.
     *
     * @param  \App\User  $user
     * @param  \App\Collection  $collection
     * @return mixed
     */
    public function delete(User $user, Collection $collection)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the collection.
     *
     * @param  \App\User  $user
     * @param  \App\Collection  $collection
     * @return mixed
     */
    public function restore(User $user, Collection $collection)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the collection.
     *
     * @param  \App\User  $user
     * @param  \App\Collection  $collection
     * @return mixed
     */
    public function forceDelete(User $user, Collection $collection)
    {
        return $user->isAdmin();
    }



}
