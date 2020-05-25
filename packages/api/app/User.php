<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Check if user has the role.
     *
     * @param string $role
     * @return boolean
     */
    public function hasRole($role)
    {
        // TODO: use a user roles table
        // User roles descending more rights
        // example: admin has the same rights as user and broadcaster,
        // but user does not have the same rights as admin an broadcaster
        $roles = [
            'user',
            'broadcaster',
            'admin'
        ];

        $indexOfRole = array_search($role, $roles);
        $indexOfUserRole = array_search($this->role, $roles);

        if ($indexOfRole <= $indexOfUserRole) {
            return true;
        }

        return false;
    }

    /**
     * Get the playlists of the users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Playlist>
     */
    public function playlists()
    {
        return $this->hasMany('App\Playlist');
    }

    /**
     * songs
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Song>
     */
    public function songs()
    {
        return $this->hasMany('App\Song');
    }

    public function radios()
    {
        return $this->hasMany('App\Radio');
    }
}
