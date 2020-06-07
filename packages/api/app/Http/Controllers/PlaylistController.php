<?php

namespace App\Http\Controllers;

use App\Playlist;
use App\Song;
use Validator;
use Illuminate\Http\Request;
use App\Exceptions\Handler;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\PlaylistSongTrait;
use Illuminate\Support\Facades\DB;

class PlaylistController extends Controller
{
    use PlaylistSongTrait;
    /**
     * Display a listing of playlists.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // Get the playlists of the authenticated user.
        $playlists = $request->auth->playlists()->withCount('songs')->get();
        return response()->json($playlists);
    }

    /**
     * Store a new playlist.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        try {
            $playlist = new Playlist;
            $playlist->name = $request->input('name');
            $playlist->user_id = $request->auth->id;

            $playlist->save();

            return response()->json([
                'playlist' => $playlist,
                'message' => 'Playlist stored successful'
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to store playlist!'], 409);
        }
    }

    /**
     * Display the specified playlist.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        // Show the specified playlist of the authenticated user
        $playlist = $request->auth->playlists()->with('songs.artist')->find($id);

        // No playlist found
        if (empty($playlist)) {
            return response()->json(['message' => 'Could not find playlist.'], 404);
        }

        return response()->json($playlist);
    }

    /**
     * Update the specified playlist.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        // Search the specified playlist of the authenticated user
        $playlist = $request->auth->playlists->find($id);

        // No playlist found
        if (empty($playlist)) {
            return response()->json(['message' => 'Could not find playlist.'], 404);
        }

        $playlist->name = $request->input('name');

        $playlist->save();

        return response()->json($playlist, 200);
    }

    /**
     * Remove the specified playlist from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        try {
            // Search the specified playlist of the authenticated user
            $playlist = $request->auth->playlists->find($id);

            // No playlist found
            if (empty($playlist)) {
                return response()->json(['message' => 'Could not find playlist.'], 404);
            }

            $playlist->delete();

            return response()->json(['message' => 'Deleted playlist.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Could not delete playlist.'], 400);
        }
    }

    /**
     * Update the specified song in playlist.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSong(Request $request, $id)
    {
        try {
            $this->validate($request, [
                'currentPosition' => 'required|numeric',
                'newPosition' => 'required|numeric',
            ]);

            $currentPosition = $request->input('currentPosition');
            $newPosition = $request->input('newPosition');

            // Search the specified playlist of the authenticated user
            $playlist = $request->auth->playlists->find($id);

            // No playlist found
            if (empty($playlist)) {
                return response()->json(['message' => 'Could not find playlist.'], 404);
            }


            $song1 = $playlist->songs()->wherePivot('song_order', $currentPosition)->first();
            $song2 = $playlist->songs()->wherePivot('song_order', $newPosition)->first();

            echo $song1;
            echo $song2;

            // No song found
            if (empty($song1) || empty($song2)) {
                return response()->json(['message' => 'Could not find song.'], 404);
            }

            $song1->pivot->song_order = $newPosition;
            $song2->pivot->song_order = $currentPosition;

            echo $song1;
            echo $song2;

            //switch song positions
            try {
                DB::beginTransaction();
                DB::table('playlist_song')
                ->where('song_order', $currentPosition)->where('playlist_id', $playlist->id)->where('id', $song1->pivot->id)
                ->update(['song_order' => $newPosition]);
  
                DB::table('playlist_song')
                ->where('song_order', $newPosition)->where('playlist_id', $playlist->id)->where('id', $song2->pivot->id)
                ->update(['song_order' => $currentPosition]);
                DB::commit();
            } catch (\PDOException $e) {
                // Woopsy
                DB::rollBack();
            }

            return response()->json(['message' => 'Updated song in playlist.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Could not update song in playlist.'], 400);
        }
    }

    /**
     * Remove the specified song from playlist.
     *
     * @param int $playlistId
     * @param int $songId
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeSong(Request $request, $playlistId, $songId)
    {
        try {
            $this->unbindPlaylistSong($request, $playlistId, $songId);
            return response()->json(['message' => 'Removed song from playlist.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Could not remove song from playlist.'], 400);
        }
    }
    
    /**
     * addSongToPlaylist
     *
     * @param  Request $request
     * @param  int $playlistId
     * @param  int $songId
     * @return \Illuminate\Http\JsonResponse
     */
    public function addSongToPlaylist(Request $request, $playlistId, $songId)
    {
        // Search the specified playlist of the authenticated user
        $playlist = $request->auth->playlists()->withCount('songs')->find($playlistId);

        // No playlist found
        if (empty($playlist)) {
            return response()->json(['message' => 'Could not find playlist.'], 404);
        }

        //attach song to the playlist
        $playlist->songs()->attach($songId, ['song_order' => $playlist->songs_count + 1]);

        return response()->json('Song was successfully added to playlist.');
    }
}
