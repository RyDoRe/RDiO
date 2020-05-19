<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call('UsersTableSeeder');
        // Dev Seeder
        /* $this->call('ArtistSeeder'); */
        /* $this->call('SongSeeder'); */
        /* $this->call('PlaylistSeeder'); */
        /* $this->call('PlaylistSongSeeder'); */
    }
}
