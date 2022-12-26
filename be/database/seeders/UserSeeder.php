<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@orionesolutions.in',
            'password' => Hash::make('Admin@123'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        $this->command->info("Admin Credential");
        $this->command->line('Email:admin@orionesolutions.in , password: Admin@123');
    }
}
