<?php

namespace Database\Seeders;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Seed the databse with the "list" of roles
     *
     * @return void
     */
    public function run(): void
    {
        Capsule::table('roles')->insert([
            'name' => 'publisher',
        ]);
    }
}
