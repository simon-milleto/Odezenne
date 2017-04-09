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
        DB::table('settings')->insert([
            'label' => 'soundcloud_client_id',
            'value' => 'aeb5b3f63ac0518f8362010439a77ca1'
        ]);
    }
}
