<?php

namespace App\Http\Controllers;

use App\Radio;
use Validator;
use Illuminate\Http\Request;
use App\Exceptions\Handler;
use Illuminate\Support\Facades\File;

class RadioController extends Controller
{
    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'genre' => 'required',
            'playlist_id' => 'required',
        ]);

        $name = $request->input('name');
        $radiopathname = strtolower(str_replace(' ', '', $name));

        $radio = $request->auth->radios->where('path', $radiopathname)->first();

        if (!empty($radio)) {
            return response()->json(['radio' => ['Radio already exists.']]);
        }

        $description = $request->input('description');
        $genre = $request->input('genre');
        $playlist_id = $request->input('playlist_id');

        $upload_dir = storage_path() . '/app/' . $request->auth->id . '/radios';
        
        // Get base config stub
        $stub = File::get(storage_path() . '/app/defaultConfig.xml');

        // Create upload dir if not existing
        if(!is_dir($upload_dir)) {
            File::makeDirectory($upload_dir, 0751, true);
        }
        
        $playlistPath = $upload_dir . '/playlist_' . $radiopathname . '.m3u';

        $stub = str_replace(
            ['RDIONAME', 'RDIOPATHNAME', 'RDIODESCRIPTION', 'RDIOGENRE', 'RDIOPLAYLISTPATH'],
            [$name, $radiopathname, $description, $genre, $playlistPath],
            $stub,
        );

        File::put($upload_dir . '/config_' . $radiopathname . '.xml', $stub);

        $playlist = $request->auth->playlists()->with('songs')->find($playlist_id);

        $playlistContent = '';
        foreach($playlist->songs as $song) {
            $playlistContent = $playlistContent . $song->path . "\r\n";
        }

        File::put($playlistPath, $playlistContent);

        $radio = new Radio;

        $radio->name = $name;
        $radio->description = $request->input('description');
        $radio->path = $radiopathname;
        $radio->icon = 'default.icon';
        $radio->playlist_id = $request->input('playlist_id');
        $radio->user_id = $request->auth->id;

        $radio->save();

    }

    public function start($id)
    {
        $cmd = 'ezstream -c ' . storage_path() . '/app/1/radios/config_radio1.xml';
        $outputfile = storage_path() . '/log.txt';
        $pidfile = storage_path() . '/pid.txt';
        exec(sprintf("%s > %s 2>&1 & echo $! >> %s", $cmd, $outputfile, $pidfile));
    }
}