<?php

namespace Database\Seeders;

use App\Helpers\UserHelper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StarterPackSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'login' => 'admin',
            'password' => UserHelper::hashPassword('superadminpass'),
            'active' => 1,
            'role' => 'admin',
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
