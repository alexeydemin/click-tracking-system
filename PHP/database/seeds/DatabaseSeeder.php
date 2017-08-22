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
        Eloquent::unguard();

        $pub1 = DB::table('users')->insertGetId([
            'name' => "Publisher_" . str_random(10),
            'email' => str_random(10).'@gmail.com',
            'password' => bcrypt('secret'),
        ]);
		$pub2 = DB::table('users')->insertGetId([
            'name' => "Publisher_" . str_random(10),
            'email' => str_random(10).'@gmail.com',
            'password' => bcrypt('secret'),
        ]);
		$adv1 = DB::table('users')->insertGetId([
            'name' => "Advertiser" . str_random(10),
            'email' => str_random(10).'@gmail.com',
            'password' => bcrypt('secret'),
        ]);
		$adv2 = DB::table('users')->insertGetId([
            'name' => "Advertiser_" . str_random(10),
            'email' => str_random(10).'@gmail.com',
            'password' => bcrypt('secret'),
        ]);

		for ( $x=0; $x<10; $x++ ) {
			$pubForInsert = $pub1;
			$advForInsert = $adv1;
			if ( $x%2==0){
				$pubForInsert = $pub2;
				$advForInsert = $adv2;
			}

			DB::table('placements')->insert([
				'user_id' => $pubForInsert,
				'name' => "folder_" . str_random(10),				
        	]);

			DB::table('folders')->insert([
				'user_id' => $advForInsert,
				'name' => "folder_" . str_random(10),				
        	]);			
		}

		//add all the fake data
        $path = 'database/click_data.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Clicks added.');

    }
}
