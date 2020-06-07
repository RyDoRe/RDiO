<?php

namespace App\Http\Controllers;

use App\Radio;
use Validator;
use Illuminate\Http\Request;
use App\Exceptions\Handler;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;

class RadioController extends Controller
{
    /**
     * Display a listing of radios.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $radios = Radio::where('user_id', '!=', $request->auth->id)->where('active', 1)->with('user')->get();

        return response()->json($radios);
    }

    /**
     * Display a listing of user radios.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexUserRadios(Request $request)
    {
        $radios = $request->auth->radios()->with('user')->get();

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
     * @return mixed
     */
    public function stream($id)
    {
        $radio = Radio::find($id);

        // Check if radio is activated
        if ($radio->active === 0) {
            return;
        }

        $songs = $radio->playlist->songs;

        // Stream settings
        $settings = [
          'buffer_size' => 16384,
          'max_listen_time' => 14400,
        ];

        $playfiles = [];

        // Load song metadata
        foreach ($songs as $song) {
            if (file_exists($song->path)) {
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
        }

        $start_time = microtime(true);

        // Calc total playtime
        $total_playtime = 0;
        foreach ($playfiles as $playfile) {
            $total_playtime += $playfile['playtime'];
        }

        // Calc playtime based on play position
        $play_sum = 0;
        $play_pos = $start_time % $total_playtime;
        $i = -1;
        foreach ($playfiles as $i => $playfile) {
            $play_sum += $playfile['playtime'];
            if ($play_sum > $play_pos) {
                break;
            }
        }

        if ($i === -1) {
            return;
        }

        // Calc track position
        $track_pos = ($playfiles[$i]['playtime'] - $play_sum + $play_pos)
          * $playfiles[$i]['audiolength'] / $playfiles[$i]['playtime'];

        // Load buffer based on track position
        // @phpstan-ignore-next-line
        $old_buffer = substr(
            file_get_contents(
                $playfiles[$i]['filepath']
            ),
            $playfiles[$i]['audiostart'] + $track_pos,
            $playfiles[$i]['audiolength'] - $track_pos
        );

        // Return buffer in Streamed Response
        return response()->stream(function () use ($start_time, $settings, $i, $playfiles, $old_buffer) {
            while (time() - $start_time < $settings['max_listen_time']) {
              // Load next song
                $i = ++$i % count($playfiles);
              // @phpstan-ignore-next-line
                $buffer = $old_buffer.substr(
                    file_get_contents($playfiles[$i]['filepath']),
                    $playfiles[$i]['audiostart'],
                    $playfiles[$i]['audiolength']
                );

              // Split the buffer into small parts
                for ($j = 0; $j < floor(strlen($buffer) / $settings['buffer_size']); $j++) {
                    echo substr($buffer, $j * $settings['buffer_size'], $settings['buffer_size']);
                    ob_flush();
                    flush();
                }
              // Throttle the connection to minimize
              // the bandwidth. The value depends
              // more or less on the server resources.
                sleep(2);

              // Load next part of the buffer
                $old_buffer = substr($buffer, $j * $settings['buffer_size']);
            }
        }, 200, ['Cache-Control' => 'no-cache', 'Content-Type' => 'audio/mpeg']);
    }

    /**
     * Get user favorites.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFavorites(Request $request)
    {
        $favorites = $request->auth->favorites()->get();

        return response()->json($favorites);
    }

    /**
     * Toggle user favorites.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleFavorite(Request $request)
    {
        $this->validate($request, [
          'radio_id' => 'required|numeric',
        ]);

        $radio = Radio::find($request->input('radio_id'));

        if (empty($radio)) {
            return response()->json(['message' => 'Could not find radio.'], 404);
        }

        $favorite = $request->auth->favorites()->find($request->input('radio_id'));

        // Toggle favorites by deleting existing one or attach a new one
        if (empty($favorite)) {
            // @phpstan-ignore-next-line
            $radio->favorites()->attach($request->auth->id);
            return response()->json(['message' => 'Added Favorite.']);
        }

        // @phpstan-ignore-next-line
        $request->auth->favorites()->detach($radio->id);
        return response()->json(['message' => 'Removed Favorite.']);
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
            // find the specific radio
            $radio = $request->auth->radios->find($id);

            // No radio found
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
