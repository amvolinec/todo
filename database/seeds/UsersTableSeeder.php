<?php
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create 10 users using the user factory
        factory(App\User::class, 10)->create();

        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@amin.com',
            'role' => 'role1',
            'password' => app('hash')->make('123456'),
        ]);
    }
}