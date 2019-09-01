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
        $usersCount = (int)$this->command->ask('How many users would you like?', 20);

        if($usersCount < 1)
        {
            $this->command->info('There has to be at least one user');
            $this->command->info('Adding one user to DB');
            $usersCount = 1;
        }

        factory(App\User::class)->states('john-doe')->create();
        factory(App\User::class, $usersCount)->create();
    }
}
