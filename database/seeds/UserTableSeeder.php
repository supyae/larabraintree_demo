<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('en_GB');
        $faker->addProvider(new Faker\Provider\en_GB\Address($faker));

        DB::table('users')->insert([
            'name'     => 'supyae',
            'email'    => 'supyae@nexlabs.co',
            'password' => bcrypt('password')
        ]);
    }
}
