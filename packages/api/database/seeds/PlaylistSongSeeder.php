<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlaylistSongSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($p = 1; $p <= 10; $p++) {
            $s_max = mt_rand(1, 50);
            for($s = 1; $s <= $s_max; $s++) {
                DB::table('playlist_song')->insert([
                    'playlist_id' => $p,
                    'song_id' => $s,
                    'song_order' => $s,
                ]);
            }
        }
    }
}
