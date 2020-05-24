<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait PlaylistSongTrait
{

    /**
     * Unbind relation between the specified song and playlist.
     *
     * @param int $playlistId
     * @param int $songId
     * @return mixed
     */
    protected function unbindPlaylistSong(Request $request, $playlistId, $songId)
    {
        try {
            // Search in the playlist of the authenticated user
            $playlist = $request->auth->playlists->find($playlistId);

            // No playlist found
            if (empty($playlist)) {
                return response()->json(['message' => 'Could not find playlist.'], 404);
            }

            $song = $playlist->songs->find($songId);

            // No song found
            if (empty($song)) {
                return response()->json(['message' => 'Could not find song.'], 404);
            }

            $playlist->songs()->detach($songId);

            $songs = $playlist->songs()->wherePivot('song_order', '>', $song->pivot->song_order)->get();
            foreach ($songs as $_song) {
                $playlist->songs()->updateExistingPivot($_song->id, ['song_order' => $_song->pivot->song_order - 1]);
            }

            return true;
        } catch (\Exception $e) {
            return $e;
        }
    }
}
