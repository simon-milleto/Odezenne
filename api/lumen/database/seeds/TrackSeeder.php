<?php

use Illuminate\Database\Seeder;

class TrackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tracks')->insert([
            'track_id' => '305230891',
            'title' => 'On Nait On Vit On Meurt',
            'artwork_url' => 'https://i1.sndcdn.com/artworks-000205417685-jtt455-large.jpg',
            'track_url' => 'https://soundcloud.com/odezenne/10-on-nait-on-vit-on-meurt',
            'stream_url' => 'https://api.soundcloud.com/tracks/305230891',
            'total_time' => '228488',
        ]);

        DB::table('tracks')->insert([
            'track_id' => '305230906',
            'title' => 'Bouche À Lèvres',
            'artwork_url' => 'https://i1.sndcdn.com/artworks-000205417702-1jxork-large.jpg',
            'track_url' => 'https://soundcloud.com/odezenne/02-bouche-a-levres',
            'stream_url' => 'https://api.soundcloud.com/tracks/305230906/stream',
            'total_time' => '229350',
        ]);

        DB::table('tracks')->insert([
            'track_id' => '305230900',
            'title' => 'Souffle Le Vent',
            'artwork_url' => 'https://i1.sndcdn.com/artworks-000205417695-i77tln-large.jpg',
            'track_url' => 'https://soundcloud.com/odezenne/05-souffle-le-vent',
            'stream_url' => 'https://api.soundcloud.com/tracks/305230900/stream',
            'total_time' => '192983',
        ]);
    }
}
