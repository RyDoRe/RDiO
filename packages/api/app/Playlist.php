<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'user_id',
    ];

    /**
     * The songs that belong to the playlist.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<\App\Song>
     */
    public function songs()
    {
        return $this->belongsToMany('App\Song')->withTimestamps()->withPivot('id', 'song_order')->orderBy('song_order');
    }
}
