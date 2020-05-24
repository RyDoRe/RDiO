<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    //
    /**
     * artist
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Artist>
     */
    public function artist()
    {
        return $this->belongsTo('App\Artist', 'artist_id');
    }
}
