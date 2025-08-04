<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userData = [
            [
                'name'=>'manager',
                'email'=>'manager@gmail.com',
                'role'=>'manager',
                'password'=>bcrypt('12345'),
            ],
            [
                'name'=>'staff',
                'email'=>'staff@gmail.com',
                'role'=>'staff',
                'password'=>bcrypt('12345'),
            ],
            [
                'name'=>'super_admin',
                'email'=>'super_admin@gmail.com',
                'role'=>'super_admin',
                'password'=>bcrypt('superadmin'),
            ]
        ];
        foreach($userData as $key => $val){
            User::create($val);
        }
    }
}
