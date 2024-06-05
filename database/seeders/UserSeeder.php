<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\RolePermission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //admin

        $role = Role::factory()->create([
            "name" => "admin",
        ]);

        User::factory()->create([
            'email' => 'oussama.kniouan@gmail.com',
            'password' => Hash::make("123456789"),
            'role_id' => $role->id,
        ]);

        //user

        $role = Role::factory()->create([
            "name" => "client",
        ]);

        User::factory()->create([
            'email' => 'client@medinamall.com',
            'password' => Hash::make("123456789"),
            'role_id' => $role->id,
        ]);
        
        $role = Role::factory()->create([
            "name" => "freelancer",
        ]);

        User::factory()->create([
            'email' => 'freelancer@medinamall.com',
            'password' => Hash::make("123456789"),
            'role_id' => $role->id,
        ]);
    }
}
