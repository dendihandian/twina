<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::firstOrNew([
            'id' => 1,
        ]);

        $user->name = 'Dendi Handian';
        $user->email = 'admin@twina.test';
        $user->password = Hash::make('password');
        $user->save();
    }
}
