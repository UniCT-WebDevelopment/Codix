<?php

namespace App\Policies;

use App\User;
use App\Comic;
use Illuminate\Auth\Access\HandlesAuthorization;

class ComicPolicy
{
    use HandlesAuthorization;


    public function before(User $user){
        if($user && $user->isAdmin())
            return true;
    }

    /**
     * Determine whether the user can view any comics.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(?User $user)
    {

        return !is_null($user) || (!security_settings['require_authentication']['value'] && security_settings['default_rule']['value']);

        return (security_settings['require_authentication']['value'] && !is_null($user)) || !is_null($user) || security_settings['default_rule']['value'];
    }

    /**
     * Determine whether the user can view the comic.
     *
     * @param  \App\User  $user
     * @param  \App\Comic  $comic
     * @return mixed
     */
    public function view(?User $user, Comic $comic)
    {
        return (!security_settings['require_authentication']['value'] && security_settings['default_rule']['value'] && is_null($user)) || $comic->readableBy($user);
    }

    /**
     * Determine whether the user can create comics.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the comic.
     *
     * @param  \App\User  $user
     * @param  \App\Comic  $comic
     * @return mixed
     */
    public function update(User $user/*, Comic $comic*/)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the comic.
     *
     * @param  \App\User  $user
     * @param  \App\Comic  $comic
     * @return mixed
     */
    public function delete(User $user, Comic $comic)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the comic.
     *
     * @param  \App\User  $user
     * @param  \App\Comic  $comic
     * @return mixed
     */
    public function restore(User $user, Comic $comic)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the comic.
     *
     * @param  \App\User  $user
     * @param  \App\Comic  $comic
     * @return mixed
     */
    public function forceDelete(User $user, Comic $comic)
    {
        return $user->isAdmin();
    }
}
