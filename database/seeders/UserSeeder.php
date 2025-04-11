<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name'              => 'Merchant User',
                'email'             => 'merchant@mailinator.com',
                'email_verified_at' => date('Y-m-d H:i:s'),
                'password'          => Hash::make('password'),
                'role_id'           => 1,
                'created_by'        => "Seeder",
                'updated_by'        => "Seeder"
            ],
            [
                'name'              => 'Customer User',
                'email'             => 'customer@mailinator.com',
                'email_verified_at' => date('Y-m-d H:i:s'),
                'password'          => Hash::make('password'),
                'role_id'           => 2,
                'created_by'        => "Seeder",
                'updated_by'        => "Seeder"
            ]
        ];

        foreach ($users as $user) {
            DB::table('users')->insert($user);
        }
    }
}
