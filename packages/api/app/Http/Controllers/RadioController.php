<?php

namespace App\Http\Controllers;

use App\Radio;
use Validator;
use Illuminate\Http\Request;
use App\Exceptions\Handler;
use Illuminate\Support\Facades\File;

class RadioController extends Controller
{
    /**
     * Display a listing of radios.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $radios = Radio::where('user_id', '!=', $request->auth->id)->where('active', 1)->get();

        return response()->json($radios);
    }

    /**
     * Display a listing of user radios.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexUserRadios(Request $request)
    {
        $radios = $request->auth->radios;

        return response()->json($radios);
    }

    /**
     * Store a new radio.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'genre' => 'required',
            'playlist_id' => 'required',
        ]);

        $radio = new Radio;

        $radio->name = $request->input('name');
        $radio->description = $request->input('description');
        $radio->playlist_id = $request->input('playlist_id');

        $request->auth->radios()->save($radio);

        return response()->json('Created radio.');
    }

    /**
     * Activate a radio.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function activate(Request $request, $id)
    {
        $radio = $request->auth->radios->find($id);
        $radio->active = $radio->active === 1 ? 0 : 1;

        $radio->save();

        return response()->json($radio);
    }

    /**
     * Stream a radio.
     *
     * @param int $id
     * @return void
     */
    public function stream($id)
    {
        $radio = Radio::find($id);

        if ($radio->active === 0) {
            return;
        }

        $settings = [
            'buffer_size' => 16384,
            'max_listen_time' => 14400,
        ];

        $songs = $radio->playlist->songs;

        $playfiles = [];

        foreach ($songs as $song) {
            $filesize = filesize($song->path);


            $playfile = [
                'filepath' => $song->path,
                'filesize' => $filesize,
                'playtime' => ($filesize * 8) / (128 * 1024),
                'audiostart' => 45,
                'audioend' => $filesize,
                'audiolength' => $filesize - 45,
            ];

            $playfiles[] = $playfile;
        }

        $start_time = microtime(true);

        $total_playtime = 0;
        foreach ($playfiles as $playfile) {
            $total_playtime += $playfile['playtime'];
        }

        $play_sum = 0;
        $play_pos = $start_time % $total_playtime;
        $i = 0;
        foreach ($playfiles as $i => $playfile) {
            $play_sum += $playfile['playtime'];
            if ($play_sum > $play_pos) {
                break;
            }
        }

        if (!$i) {
            return;
        }

        $track_pos = ($playfiles[$i]['playtime'] - $play_sum + $play_pos)
            * $playfiles[$i]['audiolength'] / $playfiles[$i]['playtime'];

        header('Content-Type: audio/mpeg');

        /* $o = $i; */
        // @phpstan-ignore-next-line
        $old_buffer = substr(
            file_get_contents(
                $playfiles[$i]['filepath']
            ),
            $playfiles[$i]['audiostart'] + $track_pos,
            $playfiles[$i]['audiolength'] - $track_pos
        );

        while (time() - $start_time < $settings['max_listen_time']) {
            $i = ++$i % count($playfiles);
            // @phpstan-ignore-next-line
            $buffer = $old_buffer.substr(
                file_get_contents($playfiles[$i]['filepath']),
                $playfiles[$i]['audiostart'],
                $playfiles[$i]['audiolength']
            );

            for ($j = 0; $j < floor(strlen($buffer) / $settings['buffer_size']); $j++) {
                echo substr($buffer, $j * $settings['buffer_size'], $settings['buffer_size']);
            }

            /* $o = $i; */
            $old_buffer = substr($buffer, $j * $settings['buffer_size']);
        }
    }

    /**
     * Remove the specified radio from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        try {
            // Search in the playlist of the authenticated user
            $radio = $request->auth->radios->find($id);

            // No playlist found
            if (empty($radio)) {
                return response()->json(['message' => 'Could not find radio.'], 404);
            }

            $radio->delete();

            return response()->json(['message' => 'Deleted radio.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Could not delete radio.'], 400);
        }
    }
}
