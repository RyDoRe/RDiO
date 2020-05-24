<?php

namespace App\Http\Controllers;

use App\Song;
use App\Artist;
use App\Playlist;
use Validator;
use Illuminate\Http\Request;
use App\Exceptions\Handler;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Traits\PlaylistSongTrait;

class SongController extends Controller
{
    use PlaylistSongTrait;
    /**
     * upload
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {

        $this->validate($request, [
            'title' => 'required',
            'thumbnail' => 'nullable|sometimes|image',
            'genre' => 'required',
            'rating' => 'numeric|between:0,5',
            'path' => 'required|file|mimes:mpga',
            'artist' => 'required',
        ]);

        $artist = strtolower($request->input('artist'));
        $artistId = '';
        $artistQuery = Artist::where('name', $artist)->first();
        if (empty($artistQuery)) {
            $newArtist = new Artist;
            $newArtist->name = $artist;

            $newArtist->save();
            $artistId = $newArtist->id;
        } else {
            $artistId = $artistQuery->id;
        }

        $title = strtolower($request->input('title'));
        $songQuery = Song::where('title', $title)->where('user_id', $request->auth->id)
        ->where('artist_id', $artistId)->first();

        if (!empty($songQuery)) {
            return response()->json(['Song' => ['Song already exists!']], 418);
        }


        $upload_dir_thumbnails = '';
        $songs_thumbnail_name = '';

        if (!empty($request->file('thumbnail'))) {
            $upload_dir_thumbnails = storage_path() . '/app/' . $request->auth->id . '/thumbnails/';
            $songs_thumbnail_name = $request->input('title') . '_thumbnail_' . time() . '.' .
            $request->file('thumbnail')->extension();

            if (!is_dir($upload_dir_thumbnails)) {
                File::makeDirectory($upload_dir_thumbnails, 0751, true);
            }

            $request->file('thumbnail')->move($upload_dir_thumbnails, $songs_thumbnail_name);
        } else {
            $upload_dir_thumbnails = storage_path() . '/app/defaultimages/';
            $songs_thumbnail_name =  'logo.jpg';
        }

        $upload_dir_songs = storage_path() . '/app/' . $request->auth->id . '/songs/';
        $songs_name = $request->input('title') . '_' . time() . '.mp3';

        if (!is_dir($upload_dir_songs)) {
            File::makeDirectory($upload_dir_songs, 0751, true);
        }

        $request->file('path')->move($upload_dir_songs, $songs_name);

        $songPath = $upload_dir_songs . $songs_name;
        $thumbnailPath = $upload_dir_thumbnails . $songs_thumbnail_name;


        $song = new Song;
        $song->title = $request->input('title');
        $song->thumbnail = $thumbnailPath;
        $song->genre = $request->input('genre');
        $song->rating = $request->input('rating');
        $song->path = $songPath;
        $song->artist_id = $artistId;
        $song->user_id = $request->auth->id;

        $song->save();
        return response()->json(['message' => 'Uploaded song.']);
    }
    
    /**
     * showAllUserSongs
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showAllUserSongs(Request $request)
    {
        // Search in the playlist of the authenticated user
        $songs = $request->auth->songs()->with('artist')->get();

        // No playlist found
        if (empty($songs)) {
            return response()->json(['message' => 'Could not find songs.'], 404);
        }

        return response()->json($songs);
    }
    
    /**
     * deleteSong
     *
     * @param  Request $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteSong(Request $request, $id)
    {

        //Remove The database entry
        //remove the songfile
        //remove the thumbnail if it exists
        

        try {
            // Search in the playlist of the authenticated user
            $songs = $request->auth->songs->find($id);

            // No playlist found
            if (empty($songs)) {
                return response()->json(['message' => 'Could not find song.'], 404);
            }

            File::delete($songs->path);
            File::delete($songs->thumbnail);

            
            $playlistSongRelations = DB::table('playlist_song')->where('song_id', $id)->get();

            foreach ($playlistSongRelations as $playlistSongrelation) {
                try {
                    $this->unbindPlaylistSong($request, $playlistSongrelation->playlist_id, $id);
                } catch (\Exception $e) {
                    return response()->json(['message' => 'Could not remove song from playlist.'], 400);
                }
            }

            $songs->delete();

            return response()->json(['message' => 'Deleted Song'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Could not delete song'], 400);
        }
    }
}
