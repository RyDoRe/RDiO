<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Radio extends Model
{
    /**
     * The playlist that belong to the radio.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Playlist>
     */
    public function playlist()
    {
        return $this->belongsTo('App\Playlist');
    }


        /**
     * The user that belong to the radio.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\User>
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
