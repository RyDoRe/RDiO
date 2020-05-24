<?php

namespace App\Http\Controllers;

use App\Song;
use App\Artist;
use Validator;
use Illuminate\Http\Request;
use App\Exceptions\Handler;
use Illuminate\Support\Facades\File;

class SongController extends Controller
{
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
        $songQuery = Song::where('title', $title)->where('user_id', $request->auth->id)->where('artist_id', $artistId)->first();

        if (!empty($songQuery)) {
            return response()->json(['Song' => ['Song already exists!']], 418);
        }


        $upload_dir_thumbnails = '';
        $songs_thumbnail_name = '';

        if (!empty($request->file('thumbnail'))) {
            $upload_dir_thumbnails = storage_path() . '/app/' . $request->auth->id . '/thumbnails/';
            $songs_thumbnail_name = $request->input('title') . '_thumbnail_' . time() . '.' . $request->file('thumbnail')->extension();

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
}
